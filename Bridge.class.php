<?php 

    /**
     * Part of PHP-Ghetto-RPC, a library to execute PHP 5 code under a PHP 4 instance
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.1
     *
     * @todo Define if Call->callback will be a simple array or a Call
     * @todo Define if the library will work with 'autonomous' classes
     *         using something like 'Class::' to execute only its constructor
     *         (Problem: constructor returns its class and PHP-Ghetto-RPC don't exchange objects
     *         through Medium, so it will not return anything)
     * @todo Standardize error triggering
     * @todo PROBLEM with the object instances collection
     *       in Call::make() -- previously Bridge::call()
     */
    require dirname(__FILE__) . '/Constants.php';
    require dirname(__FILE__) . '/Runner.class.php';
    require dirname(__FILE__) . '/Instances.class.php';
    require dirname(__FILE__) . '/FilePersistence.class.php';
    require dirname(__FILE__) . '/MemcachePersistence.class.php';
    require dirname(__FILE__) . '/Call.class.php';
    require dirname(__FILE__) . '/CallsQueue.class.php';
    
    class Bridge {
        
        /*
         * The class can't use public/private/protected
         * because visibility and encapsulation are
         * features from PHP 5
         */

        var $application;
        var $calls;
        var $id;
        var $output;
        var $errors;
        var $persistence;
        var $runner;
        var $export_options;

        /**
         * Class constructor
         *
         * @param $persistence Persistence Persistence object
         * @param $application Script filename to be called
         * @param $calls CallsQueue Methods to be executed
         */
         
        function __construct ($persistence, $application = null, $calls = null) {
            
            $this->Bridge($persistence, $application, $calls);
            
        }
         
        function Bridge ($persistence, $application, $calls = null) {

            $this->persistence = $persistence;
            $this->application = $application;
            $this->calls = $calls;
            $this->export_options = array();

            $this->initialize();
            
        }

        /*
         * Constructor extender
         * Here the class defines its behavior (running as local/remote),
         * its unique id, the persistence (file, memcache or stdin/out [todo]),
         * an error handler to store the errors in the back end
         * and the simulated PHP 4 destructor
         *
         */

        function initialize () {

            define(PHPGR_IS_BACKEND, get_cfg_var("php-ghetto-rpc-backend") == 1);

            if (PHPGR_IS_BACKEND) {

                $this->id = get_cfg_var("php-ghetto-rpc-id");
                set_error_handler(array(&$this, "error"));
                //ob_start();
				
            } 
            else {

                #if (!file_exists(PHPGR_BIN))
                #    trigger_error("PHP-Ghetto-RPC: Cannot initialize. Back end executable '" . PHPGR_BIN . "' not found.", E_USER_ERROR);
                
                $this->id = $this->_id();

                if (PHPGR_LOG && !is_writable(PHPGR_TMP))
                    trigger_error("PHP-Ghetto-RPC::Bridge::initialize: Cannot initialize. Directory '" . PHPGR_TMP . "' not found or not writable.", E_USER_ERROR);
					
            }

            // TODO: Add fallback to persistence
            // if $persistence is scalar, consider it the
            // selected persistence.
            // if $persistence is an array, iterate it
            // instantiate the persistence and verify if it
            // is valid. if not, proceed to the next item
            // and redo the verification. if no persistence
            // is valid, trigger an error
			
            $this->persistence->initialize($this->id);

            register_shutdown_function(
                array(&$this, PHPGR_IS_BACKEND ? "export" : "__destruct")
            );

            $this->_log("php " . PHP_VERSION . " start (pid:" . getmypid() . ")");
        }

        /**
         * Back end (PHP 5) destructor
         * Simulated in front end using register_shutdown_function()
         * */
        function __destruct () {

            if (!PHPGR_IS_BACKEND)
                $this->persistence->delete();

            $this->_log(
                sprintf("php %s end%s",
                        PHP_VERSION,
                        (!PHPGR_IS_BACKEND ? "\n" . str_repeat("-", 70) : ""))
            );
        }

        /**
         * Export the variables to the persistence, execute PHP 5 binary, reimport the variables
         * and store the result
         *
         * @param boolean $export If false, it will skip the export process
         * @param boolean $import If false, it will skip the import process
         * @return boolean
         * */
        function execute ($import = true, $export = true, $callback = true) {

            if (!$this->application = realpath($this->application)) {

                $this->_log("cannot execute: script not found");
                trigger_error("PHP-Ghetto-RPC::Bridge::execute: Cannot execute. File '{$this->application}' not found!", E_USER_ERROR);

            }
            else {
            
                $this->_log("start execute");

                if ($export)
                    $this->export();

                $this->runner = new Runner(
                    $this,
                    PHPGR_BACKEND_BIN,
                    array(
                        "-d php-ghetto-rpc-backend" => 1,
                        "-d php-ghetto-rpc-id" => $this->id,
                        $this->application,
                   )
                );

                $this->output = $this->runner->run();
                $this->_log("end execute");

                if ($import)
                    $this->import();

                $this->calls->process_callbacks();
                
            }

        }

        function set_export_options ($export_option) {

            if (!is_array($export_option))
                $export_option = array($export_option);

            foreach ($export_option as $option)
                if ($option > 0) {
                    $this->export_options[$option] = true;
                }
                else {
                    trigger_error("PHP-Ghetto-RPC::Bridge::set_export_options: ", E_USER_ERROR);
                }

        }

        /**
         * Export the variables to the persistence
         * @return boolean
         */
        function export () {

            $this->_log("start export");

            if ($this->persistence->valid()) {

                foreach($this->export_options as $export_option)
                    switch ($export_option) {

                        case PHPGR_EXPORT_GLOBALS:
                            foreach ($GLOBALS as $name => $value)
                                if (!is_object($value))
                                    $exports["GLOBALS"][$name] = $value;
                        break;

                        case PHPGR_EXPORT_REQUEST:
                            $exports["_REQUEST"] = $_REQUEST;
                        break;

                        case PHPGR_EXPORT_POST:
                            $exports["_POST"] = $_POST;
                        break;

                        case PHPGR_EXPORT_GET:
                            $exports["_GET"] = $_GET;
                        break;

                        case PHPGR_EXPORT_SERVER:
                            $exports["_SERVER"] = $_SERVER;
                        break;

                        case PHPGR_EXPORT_COOKIE:
                            $exports["_COOKIE"] = $_COOKIE;
                        break;

                        case PHPGR_EXPORT_SESSION:
                            $exports["_SESSION"] = $_SESSION;
                        break;
                    
                        case PHPGR_EXPORT_CONSTANTS:
                            $exports["_CONSTANTS"] = get_defined_constants();
                        break;

                        case PHPGR_EXPORT_HEADERS:
                            $exports["_HEADERS"] = PHPGR_IS_BACKEND && function_exists("headers_list") ? headers_list() : array();
                        break;
                    
                        case PHPGR_EXPORT_OUTPUT:
                            if (PHPGR_IS_BACKEND)
                                $exports["_OUTPUT"] = ob_get_clean();
                        break;
                    
                    }
                
                $exports["_CALLS"] = $this->calls;
                $exports["_ERRORS"] = $this->errors;
                
                $this->persistence->set($exports);

                $this->_log("end export");
            }
            else {
                trigger_error("PHP-Ghetto-RPC::Bridge:export: Cannot export. Persistence is not valid anymore.", E_USER_ERROR);
            }
            
        }

        /**
         * Import the variables from the persistence
         * @return mixed
         * */
        function import () {

            if ($this->persistence->valid()) {

                $data = $this->persistence->get();

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
                            $this->output = $value;

                        global $$name;
                        $$name = $value;
                    }

                $this->_log("end import");

                return $data;
            }
            else {
                trigger_error("PHP-Ghetto-RPC::Bridge::import: Cannot import. Persistence is not valid anymore.", E_USER_ERROR);
            }
        }

        /**
         * @param $str Event description
         */
        function _log ($str) {

            if (PHPGR_LOG) {

                static $logfile;

                if (!$logfile)
                    if (!$logfile = @fopen(PHPGR_LOGFILE, "a+"))
                        trigger_error("PHP-Ghetto-RPC::Bridge::_log Cannot log. Error opening log file '" . PHPGR_LOGFILE . "' for writing.", E_USER_ERROR);

                fwrite($logfile,
                        sprintf("%s %s %s %s %s\n",
                                $this->id,
                                PHP_VERSION,
                                PHPGR_USE_MEMCACHE ? "(MEM)" : "",
                                (PHPGR_IS_BACKEND ? "    " : ""),
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

            if ($errfile != __FILE__ && PHPGR_IS_BACKEND) {

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

        function _id () {

            return $this->id = uniqid(getmypid() . time(), true);
            
        }

    }

?>
