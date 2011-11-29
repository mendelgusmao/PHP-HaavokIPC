<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * ShmDriver is the class responsible for writing and reading data generated
     * by GhettoIPC from shared memory. After data is read by the frontend
     * (meaning the end of the process), the shm segment is deleted.
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */
    class ShmDriver extends Driver {
        
        var $name = "Driver";

        var $id;
        var $handle;
        var $data;
        var $valid;
        var $size;
        var $permissions;

        function initialize (&$ipc) {
            
            $this->size = $ipc->configuration["shm_size"];
            $this->permissions = $ipc->configuration["shm_permissions"];            
            
            if (!$this->size)
                $this->size = 32768;
            
            if (!$this->permissions)
                $this->permissions = 0666;

            $this->id = end(explode(".", $ipc->id())) . (int) $ipc->id();

            if (GIPC_ON_WINDOWS)
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Cannot use Shared Memory Driver in Windows."), E_USER_ERROR);

            $this->handle = shmop_open($this->id, "c", $this->size, $this->permissions);

            return true;

        }
        
        function set ($data) {

            $data = $this->serializer->serialize($data);
            $expected = strlen($data);
            $written = shmop_write($this->handle, $data, 0);

            if ($written != $expected)
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Error writing to shared memory cache: expected {$expected} bytes, written {$written} bytes. "
                    . "Try to increase size in configuration."), E_USER_ERROR);

        }

        function get () {

            $data = shmop_read($this->handle, 0, shmop_size($this->handle));
            $data = $this->serializer->unserialize($data);

            if (empty($data))
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Empty or corrupted data from shared memory segment."), E_USER_ERROR);

            return $data;

        }

        function delete () {

            return shmop_delete($this->handle);

        }

        function valid () {

            // TODO: Find a way to know if a shared memory handle is valid
            return true;

        }

    }

?>