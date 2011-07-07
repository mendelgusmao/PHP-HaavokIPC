<?php
    /**
     * Class responsible for the input and output of the data used by PHP-Ghetto-RPC
     * using a serialized file as a Persistence
     *
     *
     * @author Mendel Gusmao
     *
     */
    class StdPersistence {

        var $name = "Std";
        
        var $id;
        var $stdin_file;
        var $stdin_descriptor;
        var $stdout_file;
        var $stdout_descriptor;
        var $data;
        var $valid;

        /**
         * Constructor extender
         */
        function initialize ($id) {

            $this->id = $id;
            $this->valid = false;
            $this->stdin_file = "php://STDIN";
            $this->stdout_file = "php://STDOUT";

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

            fclose($this->stdin_descriptor);

            return unserialize($data);
        }

        /**
         * Delete the source file
         *
         * @return boolean
         */
        function delete () {

            return @fclose($this->stdin_descriptor) & @fclose($this->stdout_descriptor);
            
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