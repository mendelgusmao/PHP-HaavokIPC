<?php

    /**
     * Part of PHP-Ghetto-RPC, a library to execute PHP 5 code under a PHP 4 instance
     *
     * @author Mendel Gusmao <mendelsongusmao@gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.1
     *
     * @todo Define if Call->callback will be a simple array or a Call
     * @todo Define if the library will work with 'autonomous' classes
     *         using something like 'Class::' to execute only its constructor
     *         (Problem: constructor returns its class and PHP-Ghetto-RPC don't exchange objects
     *         through Medium, so it will not return anything)
     * @todo Improve configuration method, eliminating the excess of constants
     * @todo Standardize error triggering
     * @todo PROBLEM with the object instances collection
     *       in Call::make() -- previously Bridge::call()
     */
    require 'Medium.class.php';
    require 'FileMedium.class.php';
    require 'MemcacheMedium.class.php';
    require 'Call.class.php';
    require 'CallsQueue.class.php';
    require 'phpgr.conf.php';

    class Bridge {
        /*
         * The class can't use public/private/protected
         * because visibility is a feature from PHP 5
         */

        var $script;
        var $calls;
        var $variables;
        var $id;
        var $content;
        var $mem;
        var $errors;
        var $medium;

        /**
         * Class constructor
         *
         * @param $script Path of the script to be executed
         * @param $variables Optional variables
         * @param $methods Methods to be executed by call()
         */
        function Bridge ($medium, $script = null, $variables = null, $calls = null) {

            $this->medium = $medium;
            $this->script = $script;
            $this->variables = $variables;
            $this->calls = $calls->queue;

            $this->initialize();
        }

        /*
         * Constructor extender
         * Here the class defines its behavior (running as local/remote),
         * its unique id, the medium (file, memcache or stdin/out [todo]),
         * an error handler to store the errors in the remote instance
         * and the simulated PHP 4 destructor
         *
         */

        function initialize () {

            if ($_SERVER["argv"][1] == "--php-ghetto-rpc") {

                $this->id = $_SERVER["argv"][2];

                define(PHPGR_IS_BACKEND, true);

                set_error_handler(array(&$this, "error"));
            } else {

                $this->id = $this->_id();

                define(PHPGR_IS_BACKEND, false);

                if (PHPGR_MEDIUM == PHPGR_MEDIUM_FILE && PHPGR_LOG && !is_writable(PHPGR_TMP))
                    trigger_error("PHP-Ghetto-RPC: cannot initialize. directory '" . PHPGR_TMP . "' not writable", E_USER_ERROR);
            }

            $this->medium = new $medium();
            $this->medium->initialize($this->id);

            if (!session_id() == "" && !headers_sent())
                session_start();

            register_shutdown_function(
                    array(&$this, PHPGR_IS_BACKEND ? "export" : "__destruct")
            );

            $this->_log("php " . PHP_VERSION . " start (pid:" . getmypid() . ")");
        }

        /**
         * Remote instance (PHP 5) destructor
         * Simulated in PHP 4 instance using register_shutdown_function()
         * */
        function __destruct () {

            if (!PHPGR_IS_BACKEND)
                $this->medium - remove($this->id);

            $this->_log("php " . PHP_VERSION . " end"
                    . (!PHPGR_IS_BACKEND ? "\n" . str_repeat("-", 70) : ""));
        }

        /**
         * Export the variables to the medium, execute PHP 5 binary, reimport the variables
         * and store the result
         *
         * @param boolean $export If false, it will skip the export process
         * @param boolean $import If false, it will skip the import process
         * @return boolean
         * */
        function execute ($import = true, $export = true) {

            if (!$this->script = realpath($this->script)) {

                $this->_log("cannot execute: script not found");

                trigger_error("PHP-Ghetto-RPC: cannot execute. script '{$this->script}' not found", E_USER_ERROR);

                return false;
            }

            $this->_log("start execute");

            if ($export)
                $this->export();

            $cmdline = sprintf("%s \"%s\" --php-ghetto-rpc %s", PHPGR_BIN, $this->script, $this->id);

            $this->content = shell_exec($cmdline);

            $this->_log("end execute");

            if ($import)
                $this->import();

            $this->callback();

            return true;
        }

        /**
         * Export the variables to the medium
         * @return boolean
         */
        function export ($to_export = array()) {

            $this->_log("start export");

            if (!$this->medium->valid())
                return false;

            $temp_globals = array();

            foreach ($GLOBALS as $name => $value)
                if (!is_object($value))
                    $temp_globals[$name] = $value;

            $exports = array(
                "GLOBALS" => $temp_globals,
                "_REQUEST" => $_REQUEST,
                "_POST" => $_POST,
                "_GET" => $_GET,
                "_SERVER" => $_SERVER,
                "_COOKIE" => $_COOKIE,
                "_SESSION" => $_SESSION,
                "_CONSTANTS" => get_defined_constants(),
                "_CALLS" => $this->calls,
                "_HEADERS" => PHPGR_IS_BACKEND ? headers_list() : array(),
                "_ERRORS" => $this->errors
            );

            foreach (array_keys($exports) as $export_name)
                if (!in_array($export_name, $to_export))
                    unset($exports[$export_name]);

            if (is_array($this->variables))
                $data = array_merge($data, $this->variables);

            $this->medium->set($data);

            $this->_log("end export");

            return true;
        }

        /**
         * Import the variables from the medium
         * @return mixed
         * */
        function import () {

            $data = $medium->get();

            $this->_log("start import");

            if ($data && is_array($data))
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

                    global $$name;
                    $$name = $value;
                }

            $this->_log("end import");

            return $data;
        }

        /**
         * Execute functions/methods in the local instance using the data returned
         * by call() in the remote instance
         * @return boolean
         */
        function callback () {

            if (!$this->calls || PHPGR_IS_BACKEND)
                return false;

            foreach ($this->calls as $call)
                if ($callback_method = $call->callback)
                    if (preg_match("/(.*)::(.*)/", $callback_method, $return)) {

                        continue;

                        // NOT IMPLEMENTED
                        // TODO: PHP 4 + call_user_func + Static method calls = WAT?

                        $class = $return[1];
                        $method = $return[2];

                        if (method_exists($class, $method))
                            call_user_func(array($class, $method), $call->return);
                    }
                    else {

                        if (function_exists($callback_method))
                            call_user_func($callback_method, $call->return);
                    }

            return true;
        }

        /**
         * @param $str Event description
         */
        function _log ($str) {

            if (!PHPGR_LOG)
                return false;

            static $logfile;

            if (!$logfile)
                if (!$logfile = @fopen(PHPGR_LOGFILE, "a+")) {
                    define("PHPGR_LOG", false);

                    trigger_error("PHP-Ghetto-RPC: cannot log. error opening log file '" . PHPGR_LOGFILE . "' for writing", E_USER_ERROR);
                }

            fwrite($logfile,
                   sprintf("%s %s %s %s %s\n",
                           $this->id,
                           PHP_VERSION,
                           PHPGR_USE_MEMCACHE ? "(MEM)" : "",
                           (PHPGR_IS_BACKEND ? "    " : ""),
                           $str)
                   );

            return true;
        }

        /**
         *
         * Error handler for the remote instance
         * @param $errno The first parameter, errno, contains the level of the error raised, as an integer.
         * @param $errstr The second parameter, errstr, contains the error message, as a string.
         * @param $errfile The third parameter is optional, errfile, which contains the filename that the error was raised in, as a string.
         * @param $errline The fourth parameter is optional, errline, which contains the line number the error was raised at, as an integer.
         */
        function error ($errno, $errstr, $errfile, $errline) {

            if ($errfile == __FILE__ || !PHPGR_IS_BACKEND)
                return true;

            if (!$errors)
                static $errors = array(
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

            return true;
        }

        function _id () {

            return $this->id = getmypid() . "_" . time() . "_" . uniqid("");
            
        }

    }

?>
