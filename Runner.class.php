<?php

    class Runner {

        var $bridge;
        var $executable;
        var $parameters;
        var $stdin;
        var $stdout;
        var $stderr;

        function Runner ($bridge, $executable, $parameters = null) {

            $this->bridge = $bridge;
            $this->executable = $executable;
            $this->parameters = $parameters;
            $this->stdin = $stdin;

            $this->initialize();

        }

        function initialize () {

            if (!file_exists($this->executable))
                trigger_error("PHP-Ghetto-IPC::Runner::initialize: Executable {$this->executable} not found;", E_USER_ERROR);

        }

        function run () {

            $return_value = false;

            $descriptors = array(
                0 => array("pipe", "r"),
                1 => array("pipe", "w"),
                2 => array("pipe", "r")
            );

            $command_line = sprintf("%s %s",
                $this->executable,
                $this->_commandify($this->parameters)
            );

            return shell_exec($command_line);

            $process = proc_open($command_line, $descriptors, $pipes);

            if (is_resource($process)) {
                
                // ...

                fclose($pipes[0]);
                fclose($pipes[1]);
                fclose($pipes[2]);

                $return_value = proc_close($process);
                
            }

            return $return_value;
        }

        function write_stdin ($data) {

            

        }

        function read_stdout () {

            return $this->stdout;
            
        }

        function read_stderr () {

            return $this->stderr;

        }

        function _commandify ($parameters) {

            if (!is_array($parameters))
                if (is_scalar($parameters)) {
                    return $parameters;
                }
                else {
                    return "";
                }

            $string = array();

            foreach ($parameters as $parameter_name => $parameter_value)
                $string[] = is_numeric($parameter_name)
                          ? $parameter_value
                          : $parameter_name . "=" . $parameter_value;

            return implode(" ", $string);

        }


    }
?>
