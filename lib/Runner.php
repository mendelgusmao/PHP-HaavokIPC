<?php

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * Runner is the class responsible for executing the backend bin with
     * parameters passed by HaavokIPC class. For now it will use shell_exec()
     * to do it, but is planned to use proc_open(), due to the need of using
     * bidirectional pipes for StdIODriver.
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

    class Runner {

        var $executable;
        var $parameters;

        function initialize ($executable, $parameters = null) {

            $this->executable = $executable;
            $this->parameters = $parameters;

            if (!file_exists($this->executable))
                trigger_error(hipc_error_message(__CLASS__, __FUNCTION__, 
                    "Executable {$this->executable} not found;"), E_USER_ERROR);

        }

        function run () {

            $command_line = $this->executable . " " . $this->_commandify($this->parameters);
            
            return shell_exec($command_line);
            
        }
        
        function _commandify ($parameters) {

            $string = array();

            foreach ($parameters as $parameter_name => $parameter_value)
                $string[] = is_numeric($parameter_name)
                          ? $parameter_value
                          : $parameter_name . "=" . $parameter_value;

            return implode(" ", $string);

        }


    }
?>