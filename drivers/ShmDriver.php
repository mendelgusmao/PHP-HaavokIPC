<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * ShmDriver is the class responsible for writing and reading data generated
     * by GhettoIPC from shared memory. After data is read by the frontend
     * (meaning the end of the process), the shm segment is deleted.
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.3
     *
     */
    class ShmDriver {
        
        var $name = "Driver";

        var $id;
        var $handle;
        var $sem_id;
        var $data;
        var $valid;
        var $serializer;

        function __construct ($serializer = null) {
            
            $this->ShmDriver($serializer);

        }
        
        function ShmDriver ($serializer = null) {
            
            if (is_null($serializer))
                $serializer = new DefaultSerializer;
            
            $this->serializer = $serializer;            
            
        }         
        
        function initialize ($id) {
            
            $this->configure();

            $this->id = end(explode(".", $id)) . (int) $id;

            if (preg_match("/win/i", PHP_OS))
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Cannot use Shared Memory Driver in Windows."), E_USER_ERROR);

            $this->handle = shmop_open($this->id, "c", GIPC_SHM_PERMS, GIPC_SHM_SIZE);

            return true;

        }

        function configure () {
            
            if (!defined("GIPC_SHM_SIZE"))
                define("GIPC_SHM_SIZE", 32768);

            if (!defined("GIPC_SHM_PERMS"))
                define("GIPC_SHM_PERMS", 0666);
            
        }
        
        function set ($data) {

            $data = $this->serializer->serialize($data);
            $expected = strlen($data);
            $written = shmop_write($this->handle, $data, 0);

            if ($written != $expected)
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Error writing to shared memory cache: expected {$expected} bytes, written {$written} bytes. "
                    . "Try to increase GIPC_SHM_SIZE constant."), E_USER_ERROR);

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