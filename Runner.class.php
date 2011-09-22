<?php

    class Runner {

        var $ipc;
        var $executable;
        var $parameters;

        function __construct ($ipc, $executable, $parameters = null) {

            $this->Runner($ipc, $executable, $parameters);

        }

        function Runner ($ipc, $executable, $parameters = null) {

            $this->ipc = $ipc;
            $this->executable = $executable;
            $this->parameters = $parameters;

            $this->initialize();

        }

        function initialize () {

            if (!file_exists($this->executable))
                trigger_error("PHP-Ghetto-IPC::Runner::initialize: Executable {$this->executable} not found;", E_USER_ERROR);

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