<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * FileDriver is the class responsible for writing and reading data generated
     * by GhettoIPC to a file. After data is read by the frontend (meaning the end
     * of the process), the file is deleted.
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.3
     *
     */
    class FileDriver {
        
        var $id;
        var $handle;
        var $file;
        var $data;
        var $valid;

        function initialize ($id) {

            $this->id = $id;
            $this->valid = false;
            $this->file = GIPC_TMP . $this->id . GIPC_EXT;

            if ($this->handle = fopen($this->file, GIPC_IS_BACKEND ? "r+" : "w+"))
                $this->valid = true;
            
            return $this->valid;
            
        }

        function set ($data) {
            
            $data = serialize($data);

            rewind($this->handle);
            fwrite($this->handle, $data);
            
        }

        function get () {

            rewind($this->handle);

            $data = "";

            while ($temp = fread($this->handle, 1024))
                $data .= $temp;

            $data = unserialize($data);    
                
            if (empty($data))
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__, "Empty or corrupted file."), E_USER_ERROR);

            return $data;
            
        }

        function delete () {

            return @fclose($this->handle) & unlink($this->file);
            
        }

        function valid () {

            return $this->valid & is_array(@fstat($this->handle));
            
        }

    }

?>