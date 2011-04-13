<?php

    /**
     * Class responsible for the input and output of the data used by PHP-Ghetto-RPC
     * using a serialized file as a Medium
     *
     *
     * @author Mendel Gusmao
     *
     */
    class FileMedium extends Medium {

        var $name = "File";
        
        var $id;
        var $medium_file;
        var $data;
        var $valid;

        /**
         * Constructor extender
         */
        function initialize ($id) {

            $this->id = $id;
            $this->valid = false;
            $this->medium_file = PHPGR_TMP . $this->id . PHPGR_EXT;

            if ($this->medium = @fopen($this->medium_file, "w"))
                $this->valid = true;

            return $this->valid;
        }

        /**
         * Sets the data to the medium
         *
         * @param mixed $data The data to be set
         */
        function set ($data) {

            $data = serialize($data);
            fwrite($medium, $data);
            fclose($medium);
            return true;
        }

        /**
         * Gets the data from the medium file
         *
         * @return mixed $data The data
         */
        function get () {

            $data = "";

            while ($temp = fread($this->medium, 1024))
                $data .= $temp;

            fclose($this->medium);

            return unserialize($data);
        }

        /**
         * Delete the medium file
         *
         * @return boolean
         */
        function delete () {

            return @fclose($this->medium) && @unlink($this->medium_file);
            
        }

        /**
         * Verify if the medium is valid
         * @return bool
         */
        function valid() {

            return $this->valid;
            
        }

    }

?>