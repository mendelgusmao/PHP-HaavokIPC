<?php 

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * HaavokIPC is the core class
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

    include "lib/Includes.php";
    
    class HaavokIPC extends Dependencies {
        
        /**
         * The class can't use public/private/protected
         * because visibility and encapsulation are
         * features from PHP 5 and the proposal of this
         * library is to be multiversion
         */

        var $application;
        var $output;
        var $stdout;
        var $errors;
        var $export_options = array();
        var $debug_backtrace;
        var $configuration;
        var $runner;

        function __construct ($application = null) {
            
            $this->HaavokIPC($application);
            
        }
         
        function HaavokIPC ($application = null) {

            $this->runner = new Runner;
            $this->application = $application;

            define("HIPC_IS_BACKEND", 1 == get_cfg_var("hipc_backend"));
            define("HIPC_ON_WINDOWS", strtolower(substr(PHP_OS, 0, 3)) == "win");
           
        }

        function initialize () {

            if (HIPC_IS_BACKEND) {

                $this->configuration = Configuration::retrieve(HIPC_APPLICATION);
                
                if (!isset($this->driver)) {

                    $driver = get_cfg_var("hipc_driver");
                    $serializer = get_cfg_var("hipc_serializer");
                    
                    if (!class_exists($driver))
                        trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                            "Driver '{$driver}' not found or not loaded in Includes.php."), E_USER_ERROR);
                   
                    if (!class_exists($serializer))
                        trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                            "Serializer '{$serializer}' not found or not loaded in Includes.php."), E_USER_ERROR);
                            
                    $this->driver = new $driver(new $serializer);

                }

                set_error_handler(array(&$this, "error"));
                define("HIPC_FORCE_NO_OUTPUT", 1 == get_cfg_var("hipc_no_output"));
                
                if (HIPC_FORCE_NO_OUTPUT)
                    ob_start();

            } 
            else {

                $this->configuration = Configuration::retrieve($this->application);
                
                if (isset($this->call)) {
                    
                    $this->calls = new CallsQueue;
                    $this->calls->enqueue($this->call);
                    
                }
                
                if (is_null($this->driver))
                    trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                        "Cannot initialize with no driver."), E_USER_ERROR);

                if ($this->configuration["logging"] && !is_writable(dirname($this->configuration["logfile"])))
                    trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                        "Cannot initialize. Directory '" . dirname($this->configuration["logfile"]) . "' is not writable."), E_USER_ERROR);
					
            }

            $this->driver->initialize($this);

            register_shutdown_function(
                array(&$this, HIPC_IS_BACKEND ? "export" : "__destruct")
            );

            $this->log("php " . PHP_VERSION . " start (pid:" . getmypid() . ")");
        }

        function __destruct () {
            
            static $destructed;
            
            if ($destructed)
            	return;
            
            if (!HIPC_IS_BACKEND)
                $this->driver->delete();

            $this->log(
                sprintf("php %s end%s",
                        PHP_VERSION,
                        (!HIPC_IS_BACKEND ? "\n" . str_repeat("-", 70) : ""))
            );
        
            $destructed = true;
            
        }

        function execute ($callback = true) {

            $this->initialize();
            
            if (HIPC_IS_BACKEND) {

                if ($this->import()) {
                    $this->log("start calls");
                    $this->calls->process();
                    $this->log("end calls");
                }
                
            }
            else {
            
                if (!realpath($this->application)) {

                    $this->log("cannot execute: script not found");
                    trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                        "Cannot execute. Application file '{$this->application}' not found!"), E_USER_ERROR);

                }
                else {

                    $this->application = hipc_path($this->application);

                    if ($this->configuration["prepend_ipc_class"]) {
                        
                        $prepend_string = $this->configuration["prepend_string"];
                        
                        $prepend_string = hipc_path($prepend_string);
                        
                        $runner_params[$this->configuration["prepend_argument"]] = "\"{$prepend_string}\"";

                    }                    
                    
                    $runner_params = array_merge($runner_params, 
                        array(
                            "-d hipc_backend" => 1,
                            "-d hipc_id" => $this->id(),
                            "-d hipc_driver" => get_class($this->driver),
                            "-d hipc_serializer" => get_class($this->driver->serializer),
                            "-d hipc_no_output" => isset($this->export_options[HIPC_EXPORT_FORCE_NO_OUTPUT]) ? 1 : 0,
                            "-f \"{$this->application}\""
                        )
                    );
                    
                    $this->log("start execute");
                    $this->export();
                    
                    $this->runner->initialize(
                        $this->configuration["executable"],
                        $runner_params
                    );

                    $this->stdout = $this->runner->run();
                    $this->log("end execute");

                    if ($this->import()) {
                        if ($this->calls && $callback) {
                            $this->log("start callbacks");
                            $this->calls->process_callbacks();
                            $this->log("end callbacks");
                        }
                    }
                }
            }
        }

        function set_export_options ($options, $value = 1) {

            if (!is_array($options))
                $options = array($options => $value);

            foreach ($options as $option => $value)
                if ($option > 0) {
                    $this->export_options[$option] = $value;
                }
                else {
                    trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                        "Invalid option '{$export_option}'"), E_USER_ERROR);
                }

        }

        function export () {

            $this->log("start export");
            $export_output = false;

            if ($this->driver->valid()) {

                $exports["_EXPORT_OPTIONS"] = $this->export_options;
                $exports["_CALLS"] = $this->calls;
                $exports["_ERRORS"] = $this->errors;
                $exports["_OUTPUT"] = null;

                foreach ($this->export_options as $export_option => $export_option_value) {

                    if (
                        $export_option_value != HIPC_EXPORT_WAY_BOTH
                        && (
                            (HIPC_IS_BACKEND && $export_option_value == HIPC_EXPORT_WAY_F2B)
                            || (!HIPC_IS_BACKEND && $export_option_value == HIPC_EXPORT_WAY_B2F)
                        )
                    ) continue;

                    switch ($export_option) {
                        
                        case HIPC_EXPORT_GLOBALS:
                            foreach ($GLOBALS as $name => $value)
                                if (!is_object($value) && !is_resource($value))
                                    $exports["GLOBALS"][$name] = $value;
                        break;

                        case HIPC_EXPORT_REQUEST:
                            $exports["_REQUEST"] = $_REQUEST;
                        break;

                        case HIPC_EXPORT_POST:
                            $exports["_POST"] = $_POST;
                        break;

                        case HIPC_EXPORT_GET:
                            $exports["_GET"] = $_GET;
                        break;

                        case HIPC_EXPORT_SERVER:
                            $exports["_SERVER"] = $_SERVER;
                        break;

                        case HIPC_EXPORT_COOKIE:
                            $exports["_COOKIE"] = $_COOKIE;
                        break;

                        case HIPC_EXPORT_SESSION:
                            $exports["_SESSION"] = $_SESSION;
                        break;
                    
                        case HIPC_EXPORT_CONSTANTS:
                            $exports["_CONSTANTS"] = get_defined_constants();
                        break;

                        case HIPC_EXPORT_HEADERS:
                            $exports["_HEADERS"] = HIPC_IS_BACKEND && function_exists("headers_list") ? headers_list() : array();
                        break;
                    
                        case HIPC_EXPORT_OUTPUT:
                            $export_output = true;
                        break;
                        
                        case HIPC_EXPORT_ENV:
                            $exports["_ENV"] = $_ENV;
                        break;

                        case HIPC_EXPORT_FILES:
                            $exports["_FILES"] = $_FILES;
                        break;

                        case HIPC_EXPORT_DEBUG:
                            $exports["_DEBUG"] = HIPC_IS_BACKEND ? debug_backtrace() : array();
                        break;
                    
                    }
                }
                
                if (HIPC_IS_BACKEND && (HIPC_FORCE_NO_OUTPUT || $export_output))
                    $exports["_OUTPUT"] = ob_get_clean();
                
                $this->driver->set($exports);

                $this->log("end export");
            }
            else {
                trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                    "Cannot export. Driver resource is not valid anymore."), E_USER_ERROR);
            }
            
        }

        function import () {

            if ($this->driver->valid()) {

                $data = $this->driver->get();

                $this->log("start import");

                if (is_array($data))
                    foreach ($data as $name => $value) {

                        if ("_SESSION" == $name && !$value)
                            continue;

                        if ("_EXPORT_OPTIONS" == $name)
                            $this->export_options = $value;

                        if ("_HEADERS" == $name && !headers_sent())
                            foreach ($value as $header)
                                header($header);

                        if ("_CONSTANTS" == $name)
                            foreach ($value as $constant => $constant_value)
                                if (!defined($constant))
                                    define($constant, $constant_value);

                        if ("_CALLS" == $name)
                            $this->calls = $value;

                        if ("_ERRORS" == $name)
                            $this->errors = $value;

                        if ("_OUTPUT" == $name && !HIPC_IS_BACKEND)
                            $this->output = $value;

                        if ("_DEBUG" == $name)
                            $this->debug_backtrace = $value;

                        global $$name;
                        $$name = $value;
                    }

                $this->log("end import");

                return $data;
            }
            else {
                trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                    "Cannot import. Driver resource is not valid anymore."), E_USER_ERROR);
            }
        }

        function log ($str) {

            if ($this->configuration["logging"]) {

                static $logfile;

                if (!$logfile)
                    if (!$logfile = @fopen($this->configuration["logfile"], "a+"))
                        trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                            "Cannot log. Error opening log file '" . $this->configuration["logfile"] . "' for writing."));

                fwrite($logfile,
                       sprintf("%s %s %s%s%s\n",
                               time(),
                               $this->id(),
                               PHP_VERSION,
                               (HIPC_IS_BACKEND ? "\t\t" : "\t"),
                               $str)
                );
            }
        }

        function error ($errno, $errstr, $errfile, $errline) {

            static $errors;

            if (!$errors)
                $errors = array(
                    E_WARNING => "WARNING",
                    E_NOTICE => "NOTICE",
                    E_USER_ERROR => "USER_ERROR",
                    E_USER_WARNING => "USER_WARNING",
                    E_USER_NOTICE => "USER_NOTICE",
                    E_STRICT => "STRICT",
                    E_RECOVERABLE_ERROR => "RECOVERABLE_ERROR",
                    E_DEPRECATED => "DEPRECATED",
                    E_USER_DEPRECATED => "USER_DEPRECATED"
                );

            $errlevel = $errors[$errno];

            $this->errors[] = array(
                "level" => $errlevel,
                "message" => $errstr,
                "file" => $errfile,
                "line" => $errline
            );

            if ($errno != E_NOTICE)
                $this->log(sprintf("%s: %s %s:%s", $errlevel, $errstr, $errfile, $errline));

        }

        function id () {

            static $id;

            if (empty($id))
                $id = HIPC_IS_BACKEND
                    ? get_cfg_var("hipc_id")
                    : uniqid($this->configuration["id_prefix"] . getmypid(), true);

            return $id;
            
        }

    }

?>
