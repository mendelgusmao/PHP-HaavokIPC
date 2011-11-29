<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * FileDriver is the class responsible for writing and reading data generated
     * by GhettoIPC to a file. After data is read by the frontend (meaning the end
     * of the process), the file is deleted.
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */
    class FileDriver extends Driver {
        
        var $name = "Driver";
        
        var $id;
        var $handle;
        var $file;
        var $directory;
        var $extension;
        var $data;
        var $valid;

        function initialize (&$ipc) {
            
            $this->directory = $ipc->configuration["temp_directory"];
            $this->extension = $ipc->configuration["file_extension"];
            
            if (!$this->directory)
                $this->directory = "/tmp/";

            if (!$this->extension)
                $this->extension = ".persistence";
            
            $this->id = $ipc->id();
            $this->valid = false;
            $this->file = $this->directory . $this->id . $this->extension;

            if (!is_writable($this->directory))
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Cannot initialize. Directory '{$this->directory}' is not writable."), E_USER_ERROR);            
            
            if ($this->handle = fopen($this->file, GIPC_IS_BACKEND ? "r+" : "w+"))
                $this->valid = true;
            
            return $this->valid;
            
        }
        
        function set ($data) {
            
            $data = $this->serializer->to($data);

            rewind($this->handle);
            fwrite($this->handle, $data);
            
        }

        function get () {

            rewind($this->handle);

            $data = "";

            while ($temp = fread($this->handle, 1024))
                $data .= $temp;

            $data = $this->serializer->from($data);    
                
            if (empty($data))
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__, 
                    "Empty or corrupted file."), E_USER_ERROR);

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