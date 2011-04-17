<?php
    /**
     * Class responsible for the input and output of the data used by PHP-Ghetto-RPC
     * using a serialized file as a Persistence
     *
     *
     * @author Mendel Gusmao
     *
     */
    class FilePersistence {

        var $name = "File";
        
        var $id;
		var $descriptor;
        var $source_file;
        var $data;
        var $valid;

        /**
         * Constructor extender
         */
        function initialize ($id) {

            $this->id = $id;
            $this->valid = false;
            $this->source_file = PHPGR_TMP . $this->id . PHPGR_EXT;

            if ($this->descriptor = fopen($this->source_file, "w"))
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
            fwrite($this->descriptor, $data);
			
        }

        /**
         * Gets the data from the source file
         *
         * @return mixed $data The data
         */
        function get () {

            $data = "";

            while ($temp = fread($this->descriptor, 1024))
                $data .= $temp;

            fclose($this->descriptor);

            return unserialize($data);
        }

        /**
         * Delete the source file
         *
         * @return boolean
         */
        function delete () {

            return @fclose($this->descriptor) && @unlink($this->source_file);
            
        }

        /**
         * Verify if the source is valid
         * @return bool
         */
        function valid() {

            return $this->valid;
            
        }

    }

?>