<?php 

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP 5 code under a PHP 4 instance
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.1
     *
     * @todo Define if Call->callback will be a simple array or a Call
     * @todo Define if the library will work with 'autonomous' classes
     *         using something like 'Class::' to execute only its constructor
     *         (Problem: constructor returns its class and PHP-Ghetto-IPC don't exchange objects
     *         through Medium, so it will not return anything)
     * @todo Standardize error triggering
     */
    require dirname(__FILE__) . '/Constants.php';
    require dirname(__FILE__) . '/Configuration.php';
    require dirname(__FILE__) . '/Runner.class.php';
    require dirname(__FILE__) . '/Instances.class.php';
    require dirname(__FILE__) . '/drivers/FileDriver.class.php';
    require dirname(__FILE__) . '/drivers/MemcacheDriver.class.php';
    require dirname(__FILE__) . '/Call.class.php';
    require dirname(__FILE__) . '/CallsQueue.class.php';
    
    class GhettoIPC {
        
        /*
         * The class can't use public/private/protected
         * because visibility and encapsulation are
         * features from PHP 5
         */

        var $application;
        var $calls;
        var $output;
        var $output2;
        var $errors;
        var $driver;
        var $runner;
        var $export_options;

        /**
         * Class constructor
         *
         * @param $driver Driver
         * @param $application Script filename to be called
         * @param $calls CallsQueue Methods to be executed
         */
         
        function __construct ($driver, $application = null, $calls = null) {
            
            $this->GhettoIPC($driver, $application, $calls);
            
        }
         
        function GhettoIPC ($driver, $application, $calls = null) {

            $this->driver = $driver;
            $this->application = $application;
            $this->calls = $calls;
            $this->export_options = array();

            $this->initialize();
            
        }

        /*
         * Constructor extender
         * Here the class defines its behavior (running as local/remote),
         * its unique id, the driver (file, memcache or stdin/out),
         * an error handler to store the errors in the back end
         * and the simulated PHP 4 destructor
         *
         */

        function initialize () {

            define("PHPGI_IS_BACKEND", get_cfg_var("php-ghetto-ipc-backend") == 1);

            if (PHPGI_IS_BACKEND) {

                set_error_handler(array(&$this, "error"));
                define("PHPGI_FORCE_NO_OUTPUT", get_cfg_var("php-ghetto-ipc-no-output") == 1);
                
                if (PHPGI_FORCE_NO_OUTPUT)
                    ob_start();

            } 
            else {

                #if (!file_exists(PHPGI_BACKEND_BIN))
                #    trigger_error("PHP-Ghetto-IPC: Cannot initialize. Back end executable '" . PHPGI_BACKEND_BIN . "' not found.", E_USER_ERROR);

                if (PHPGI_LOG && !is_writable(PHPGI_TMP))
                    trigger_error("PHP-Ghetto-IPC::GhettoIPC::initialize: Cannot initialize. Directory '" . PHPGI_TMP . "' not found or not writable.", E_USER_ERROR);
					
            }

            $this->driver->initialize($this->id());

            register_shutdown_function(
                array(&$this, PHPGI_IS_BACKEND ? "export" : "__destruct")
            );

            $this->_log("php " . PHP_VERSION . " start (pid:" . getmypid() . ")");
        }

        /**
         * Back end (PHP 5) destructor
         * Simulated in front end using register_shutdown_function()
         * */
        function __destruct () {
            
            static $destructed;
            
            if (!$destructed) {
            
                if (!PHPGI_IS_BACKEND)
                    $this->driver->delete();

                $this->_log(
                    sprintf("php %s end%s",
                            PHP_VERSION,
                            (!PHPGI_IS_BACKEND ? "\n" . str_repeat("-", 70) : ""))
                );
                
                $destructed = true;
            }
            
        }

        /**
         * Export the variables to the driver, execute PHP 5 binary, reimport the variables
         * and store the result
         *
         * @param boolean $export If false, it will skip the export process
         * @param boolean $import If false, it will skip the import process
         * @return boolean
         * */
        function execute ($callback = true) {

            if (PHPGI_IS_BACKEND) {

                $this->import();
                
                $this->_log("start calls");
                $this->calls->process();
                $this->_log("end calls");    
                
            }
            else {
            
                if (!$this->application = realpath($this->application)) {

                    $this->application = escapeshellcmd($this->application);
                    
                    $this->_log("cannot execute: script not found");
                    trigger_error("PHP-Ghetto-IPC::GhettoIPC::execute: Cannot execute. File '{$this->application}' not found!", E_USER_ERROR);

                }
                else {
                
                    $this->_log("start execute");

                    $this->export();

                    $runner_params = array();

                    if (PHPGI_PREPEND_IPC_CLASS)
                        $runner_params["-d auto_prepend_file"] = __FILE__;

                    $runner_params = array_merge(
                        $runner_params,
                        array(
                            "-d php-ghetto-ipc-backend" => 1,
                            "-d php-ghetto-ipc-id" => $this->id(),
                            "-d php-ghetto-ipc-force-no-output" => isset($this->export_options[PHPGI_EXPORT_FORCE_NO_OUTPUT]) ? 1 : 0,
                            "-f \"{$this->application}\""
                        )
                    );

                    $this->runner = new Runner(
                        $this,
                        PHPGI_BACKEND_BIN,
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

        function set_export_options () {

            $export_options = func_get_args();

            foreach ($export_options as $option)
                if ($option > 0) {
                    $this->export_options[$option] = true;
                }
                else {
                    trigger_error("PHP-Ghetto-IPC::GhettoIPC::set_export_options: Invalid option '{$export_option}'", E_USER_ERROR);
                }

        }

        /**
         * Export the variables to the driver
         * @return boolean
         */
        function export () {

            $this->_log("start export");
            $export_output = false;

            if ($this->driver->valid()) {

                foreach($this->export_options as $export_option => $export_option_enabled) {

                    switch ($export_option) {
                        
                        case PHPGI_EXPORT_GLOBALS:
                            foreach ($GLOBALS as $name => $value)
                                if (!is_object($value))
                                    $exports["GLOBALS"][$name] = $value;
                        break;

                        case PHPGI_EXPORT_REQUEST:
                            $exports["_REQUEST"] = $_REQUEST;
                        break;

                        case PHPGI_EXPORT_POST:
                            $exports["_POST"] = $_POST;
                        break;

                        case PHPGI_EXPORT_GET:
                            $exports["_GET"] = $_GET;
                        break;

                        case PHPGI_EXPORT_SERVER:
                            $exports["_SERVER"] = $_SERVER;
                        break;

                        case PHPGI_EXPORT_COOKIE:
                            $exports["_COOKIE"] = $_COOKIE;
                        break;

                        case PHPGI_EXPORT_SESSION:
                            $exports["_SESSION"] = $_SESSION;
                        break;
                    
                        case PHPGI_EXPORT_CONSTANTS:
                            $exports["_CONSTANTS"] = get_defined_constants();
                        break;

                        case PHPGI_EXPORT_HEADERS:
                            $exports["_HEADERS"] = PHPGI_IS_BACKEND && function_exists("headers_list") ? headers_list() : array();
                        break;
                    
                        case PHPGI_EXPORT_OUTPUT:
                            if (PHPGI_IS_BACKEND)
                                $export_output = true;
                            
                        break;
                    
                    }
                }
                
                $exports["_CALLS"] = $this->calls;
                $exports["_ERRORS"] = $this->errors;

                if (PHPGI_FORCE_NO_OUTPUT || $export_output)
                    $exports["_OUTPUT"] = ob_get_clean();
                
                $this->driver->set($exports);

                $this->_log("end export");
            }
            else {
                trigger_error("PHP-Ghetto-IPC::GhettoIPC::export: Cannot export. Driver resource is not valid anymore.", E_USER_ERROR);
            }
            
        }

        /**
         * Import the variables from the driver
         * @return mixed
         * */
        function import () {

            if ($this->driver->valid()) {

                $data = $this->driver->get();

                $this->_log("start import");

                if (is_array($data))
                    foreach ($data as $name => $value) {

                        if ("_SESSION" == $name && !$value)
                            continue;

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

                        if ("_OUTPUT" == $name)
                            if (!PHPGI_IS_BACKEND)
                                $this->output = $value;

                        global $$name;
                        $$name = $value;
                    }

                $this->_log("end import");

                return $data;
            }
            else {
                trigger_error("PHP-Ghetto-IPC::GhettoIPC::import: Cannot import. Driver resource is not valid anymore.", E_USER_ERROR);
            }
        }

        /**
         * @param $str Event description
         */
        function _log ($str) {

            if (PHPGI_LOG) {

                static $logfile;

                if (!$logfile)
                    if (!$logfile = @fopen(PHPGI_LOGFILE, "a+"))
                        trigger_error("PHP-Ghetto-IPC::GhettoIPC::_log Cannot log. Error opening log file '" . PHPGI_LOGFILE . "' for writing.", E_USER_ERROR);

                fwrite($logfile,
                       sprintf("%s %s %s%s%s\n",
                               time(),
                               $this->id(),
                               reset(explode("-", PHP_VERSION)),
                               (PHPGI_IS_BACKEND ? "\t\t" : "\t"),
                               $str)
                );
            }
        }

        /**
         *
         * Error handler for the back end
         * @param $errno The first parameter, errno, contains the level of the error raised, as an integer.
         * @param $errstr The second parameter, errstr, contains the error message, as a string.
         * @param $errfile The third parameter is optional, errfile, which contains the filename that the error was raised in, as a string.
         * @param $errline The fourth parameter is optional, errline, which contains the line number the error was raised at, as an integer.
         */
        function error ($errno, $errstr, $errfile, $errline) {

            static $errors;

            if ($errfile != __FILE__ && PHPGI_IS_BACKEND) {

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

        }

        function id () {

            static $id;

            if (empty($id))
                $id = PHPGI_IS_BACKEND
                    ? get_cfg_var("php-ghetto-ipc-id")
                    : uniqid(getmypid(), true);

            return $id;
            
        }

    }

?>
