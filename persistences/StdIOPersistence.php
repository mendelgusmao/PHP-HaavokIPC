<?php

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * StdIOPersistence is the class responsible for writing to STDOUT and reading
     * from STDIO the data generated by HaavokIPC. Not implemented for now.
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */
    class StdIOPersistence extends Persistence {
        
        var $name = "Persistence";
        
        var $id;
        var $handle;
        var $runner;
        var $data;
        var $valid;
        
        function initialize (&$ipc) {

            $this->id = $ipc->id();
            $this->valid = false;

            if ($this->stdin_descriptor = fopen($this->stdin_file, "r") &&
                $this->stdout_descriptor = fopen($this->stdout_file, "w"))
                $this->valid = true;	
                
            return $this->valid;
        }

        function set ($data) {

            $data = $this->serializer->serialize($data);
            fwrite($this->stdout_descriptor, $data);
			
        }

        function get () {

            $data = "";

            while ($temp = fread($this->stdin_descriptor, 1024))
                $data .= $temp;

            return $this->serializer->unserialize($data);
        }

        function delete () {

            return true;
            
        }

        function valid () {

            return $this->valid & is_array(@fstat($this->descriptor));
            
        }

    }

?>