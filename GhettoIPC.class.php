<?php 

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * GhettoIPC is the main class
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.3
     *
     */

    include "Includes.php";
    
    class GhettoIPC {
        
        /**
         * The class can't use public/private/protected
         * because visibility and encapsulation are
         * features from PHP 5 and the proposal of this
         * library is to be multiversion
         */

        var $application;
        var $calls;
        var $output;
        var $output2;
        var $errors;
        var $driver;
        var $runner;
        var $export_options;
        var $debug_backtrace;

        function __construct ($driver = null, $application = null, $calls = null) {
            
            $this->GhettoIPC($driver, $application, $calls);
            
        }
         
        function GhettoIPC ($driver = null, $application = null, $calls = null) {

            $this->driver = $driver;
            $this->application = $application;
            $this->calls = $calls;
            $this->export_options = array();

            $this->initialize();
            
        }

        function initialize () {

            define("GIPC_IS_BACKEND", 1 == get_cfg_var("php-ghetto-ipc-backend"));

            if (GIPC_IS_BACKEND) {

                if (is_null($this->driver)) {

                    $driver = get_cfg_var("php-ghetto-ipc-driver");

                    if (class_exists($driver)) {
                        $this->driver = new $driver;
                    }
                    else {
                        trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                            "Driver '{$driver}' not found or not loaded in Includes.php."), E_USER_ERROR);
                    }
                    
                }

                set_error_handler(array(&$this, "error"));
                define("GIPC_FORCE_NO_OUTPUT", 1 == get_cfg_var("php-ghetto-ipc-no-output"));
                
                if (GIPC_FORCE_NO_OUTPUT)
                    ob_start();

            } 
            else {

                if (is_null($this->driver))
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                        "Cannot initialize with no driver."), E_USER_ERROR);

                if (GIPC_LOG && !is_writable(dirname(GIPC_LOGFILE)))
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                        "Cannot initialize. Directory '" . dirname(GIPC_LOGFILE) . "' is not writable."), E_USER_ERROR);
					
            }

            $this->driver->initialize($this->id());

            register_shutdown_function(
                array(&$this, GIPC_IS_BACKEND ? "export" : "__destruct")
            );

            $this->_log("php " . PHP_VERSION . " start (pid:" . getmypid() . ")");
        }

        function __destruct () {
            
            static $destructed;
            
            if ($destructed)
            	return;
            
            if (!GIPC_IS_BACKEND)
                $this->driver->delete();

            $this->_log(
                sprintf("php %s end%s",
                        PHP_VERSION,
                        (!GIPC_IS_BACKEND ? "\n" . str_repeat("-", 70) : ""))
            );
        
            $destructed = true;
            
        }

        function execute ($callback = true) {

            if (GIPC_IS_BACKEND) {

                $this->import();
                
                $this->_log("start calls");
                $this->calls->process();
                $this->_log("end calls");
                
            }
            else {
            
                if (!realpath($this->application)) {

                    $this->_log("cannot execute: script not found");
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                        "Cannot execute. Application file '{$this->application}' not found!"), E_USER_ERROR);

                }
                else {

                    $this->application = escapeshellcmd(
                        str_replace("\\", "/", realpath($this->application)));

                    $this->_log("start execute");

                    $this->export();

                    $runner_params = array();

                    if (GIPC_PREPEND_IPC_CLASS)
                        $runner_params["-d auto_prepend_file"] = "\"" . str_replace("\\", "/", __FILE__) . "\"";

                    $runner_params = array_merge(
                        $runner_params,
                        array(
                            "-d php-ghetto-ipc-backend" => 1,
                            "-d php-ghetto-ipc-id" => $this->id(),
                            "-d php-ghetto-ipc-driver" => get_class($this->driver),
                            "-d php-ghetto-ipc-force-no-output" => isset($this->export_options[GIPC_EXPORT_FORCE_NO_OUTPUT]) ? 1 : 0,
                            "-f \"{$this->application}\""
                        )
                    );

                    $this->runner = new Runner(
                        $this,
                        GIPC_BACKEND_BIN,
                        $runner_params
                    );

                    $this->output2 = $this->runner->run();
                    $this->_log("end execute");

                    if ($this->import())
                        if ($this->calls && $callback) {
                            $this->_log("start callbacks");
                            $this->calls->process_callbacks();
                            $this->_log("end callbacks");
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
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                        "Invalid option '{$export_option}'"), E_USER_ERROR);
                }

        }

        function export () {

            $this->_log("start export");
            $export_output = false;

            if ($this->driver->valid()) {

                $exports["_EXPORT_OPTIONS"] = $this->export_options;
                $exports["_CALLS"] = $this->calls;
                $exports["_ERRORS"] = $this->errors;
                $exports["_OUTPUT"] = null;

                foreach ($this->export_options as $export_option => $export_option_value) {

                    if (
                        $export_option_value != GIPC_EXPORT_WAY_BOTH
                        && (
                            (GIPC_IS_BACKEND && $export_option_value == GIPC_EXPORT_WAY_F2B)
                            || (!GIPC_IS_BACKEND && $export_option_value == GIPC_EXPORT_WAY_B2F)
                        )
                    ) continue;

                    switch ($export_option) {
                        
                        case GIPC_EXPORT_GLOBALS:
                            foreach ($GLOBALS as $name => $value)
                                if (!is_object($value) && !is_resource($value))
                                    $exports["GLOBALS"][$name] = $value;
                        break;

                        case GIPC_EXPORT_REQUEST:
                            $exports["_REQUEST"] = $_REQUEST;
                        break;

                        case GIPC_EXPORT_POST:
                            $exports["_POST"] = $_POST;
                        break;

                        case GIPC_EXPORT_GET:
                            $exports["_GET"] = $_GET;
                        break;

                        case GIPC_EXPORT_SERVER:
                            $exports["_SERVER"] = $_SERVER;
                        break;

                        case GIPC_EXPORT_COOKIE:
                            $exports["_COOKIE"] = $_COOKIE;
                        break;

                        case GIPC_EXPORT_SESSION:
                            $exports["_SESSION"] = $_SESSION;
                        break;
                    
                        case GIPC_EXPORT_CONSTANTS:
                            $exports["_CONSTANTS"] = get_defined_constants();
                        break;

                        case GIPC_EXPORT_HEADERS:
                            $exports["_HEADERS"] = GIPC_IS_BACKEND && function_exists("headers_list") ? headers_list() : array();
                        break;
                    
                        case GIPC_EXPORT_OUTPUT:
                            $export_output = true;
                        break;
                        
                        case GIPC_EXPORT_ENV:
                            $exports["_ENV"] = $_ENV;
                        break;

                        case GIPC_EXPORT_FILES:
                            $exports["_FILES"] = $_FILES;
                        break;

                        case GIPC_EXPORT_DEBUG:
                            $exports["_DEBUG"] = GIPC_IS_BACKEND ? debug_backtrace() : array();
                        break;
                    
                    }
                }
                
                if (GIPC_IS_BACKEND && (GIPC_FORCE_NO_OUTPUT || $export_output))
                    $exports["_OUTPUT"] = ob_get_clean();
                
                $this->driver->set($exports);

                $this->_log("end export");
            }
            else {
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Cannot export. Driver resource is not valid anymore."), E_USER_ERROR);
            }
            
        }

        function import () {

            if ($this->driver->valid()) {

                $data = $this->driver->get();

                $this->_log("start import");

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
                                if (substr($constant, 0, 3) != "PHP" && !defined($constant))
                                    define($constant, $constant_value);

                        if ("_CALLS" == $name)
                            $this->calls = $value;

                        if ("_ERRORS" == $name)
                            $this->errors = $value;

                        if ("_OUTPUT" == $name && !GIPC_IS_BACKEND)
                            $this->output = $value;

                        if ("_DEBUG" == $name)
                            $this->debug_backtrace = $value;

                        global $$name;
                        $$name = $value;
                    }

                $this->_log("end import");

                return $data;
            }
            else {
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Cannot import. Driver resource is not valid anymore."), E_USER_ERROR);
            }
        }

        function _log ($str) {

            if (GIPC_LOG) {

                static $logfile;

                if (!$logfile)
                    if (!$logfile = @fopen(GIPC_LOGFILE, "a+"))
                        trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                            "Cannot log. Error opening log file '" . GIPC_LOGFILE . "' for writing."));

                fwrite($logfile,
                       sprintf("%s %s %s%s%s\n",
                               time(),
                               $this->id(),
                               PHP_VERSION,
                               (GIPC_IS_BACKEND ? "\t\t" : "\t"),
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
                $this->_log(sprintf("%s: %s %s:%s", $errlevel, $errstr, $errfile, $errline));

        }

        function id () {

            static $id;

            if (empty($id))
                $id = GIPC_IS_BACKEND
                    ? get_cfg_var("php-ghetto-ipc-id")
                    : uniqid(GIPC_ID_PREFIX . getmypid(), true);

            return $id;
            
        }

    }

?>
