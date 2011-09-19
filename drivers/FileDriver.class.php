<?php
    /**
     * Class responsible for the input and output of the data used by PHP-Ghetto-IPC
     * using a serialized file
     *
     * @author Mendel Gusmao
     *
     */
    class FileDriver {
        
        var $id;
        var $descriptor;
        var $file;
        var $data;
        var $valid;

        /**
         * Initialize FileDriver by setting the id and opening the file
         */
        function initialize ($id) {

            $this->id = $id;
            $this->valid = false;
            $this->file = PHPGI_TMP . $this->id . PHPGI_EXT;

            if ($this->descriptor = fopen($this->file, PHPGI_IS_BACKEND ? "r+" : "w+"))
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

            rewind($this->descriptor);
            fwrite($this->descriptor, $data);
            
        }

        /**
         * Gets the data from the source file
         *
         * @return mixed $data The data
         */
        function get () {

            rewind($this->descriptor);

            $data = "";

            while ($temp = fread($this->descriptor, 1024))
                $data .= $temp;

            $data = unserialize($data);    
                
            if (empty($data))
                trigger_error("PHP-Ghetto-IPC::FileDriver::get: Empty or corrupted file.", E_USER_ERROR);

            return $data;
            
        }

        /**
         * Delete the source file
         *
         * @return boolean
         */
        function delete () {

            return @fclose($this->descriptor) & unlink($this->file);
            
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