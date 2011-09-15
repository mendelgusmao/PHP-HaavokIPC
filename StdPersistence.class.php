<?php
    /**
     * Class responsible for the input and output of the data used by PHP-Ghetto-IPC
     * using STDIN/STDOUT file as Persistence
     *
     *
     * @author Mendel Gusmao
     *
     */
    class StdPersistence {

        var $name = "Std";
        
        var $id;
        var $runner;
        var $data;
        var $valid;

        /**
         * Constructor extender
         */
        function initialize ($id) {

            $this->id = $id;
            $this->valid = false;

            if ($this->stdin_descriptor = fopen($this->stdin_file, "r") &&
                $this->stdout_descriptor = fopen($this->stdout_file, "w"))
                $this->valid = true;	
                
            return $this->valid;
        }

        /**
         * Sets the data to the source
         *
         * @param mixed $data The data to be set
         */
        function set ($data) {

            $data = serialize($data);
            fwrite($this->stdout_descriptor, $data);
			
        }

        /**
         * Gets the data from the source file
         *
         * @return mixed $data The data
         */
        function get () {

            $data = "";

            while ($temp = fread($this->stdin_descriptor, 1024))
                $data .= $temp;

            return unserialize($data);
        }

        /**
         * Delete the source file
         *
         * @return boolean
         */
        function delete () {

            return true;
            
        }

        /**
         * Verify if the source is valid
         * @return bool
         */
        function valid () {

            return $this->valid & is_array(@fstat($this->descriptor));
            
        }

    }

?>