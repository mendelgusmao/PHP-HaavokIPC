<?php
    /**
     * Class responsible for the input and output of the data used by PHP-Ghetto-RPC
     * using a serialized file as a Persistence
     *
     *
     * @author Mendel Gusmao
     *
     */
    class FilePersistence extends Persistence {

        var $name = "File";
        
        var $id;
		var $source;
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

            if ($this->source = @fopen($this->source_file, "w"))
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
            fwrite($source, $data);
            fclose($source);
			
        }

        /**
         * Gets the data from the source file
         *
         * @return mixed $data The data
         */
        function get () {

            $data = "";

            while ($temp = fread($this->source, 1024))
                $data .= $temp;

            fclose($this->source);

            return unserialize($data);
        }

        /**
         * Delete the source file
         *
         * @return boolean
         */
        function delete () {

            return @fclose($this->source) && @unlink($this->source_file);
            
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