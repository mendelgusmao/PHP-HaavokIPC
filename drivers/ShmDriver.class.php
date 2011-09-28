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
        
        var $id;
        var $handle;
        var $sem_id;
        var $data;
        var $valid;

        function initialize ($id) {

            $this->id = end(explode(".", $id)) . (int) $id;
            
            $this->valid = false;

            if (preg_match("/win/i", PHP_OS))
                trigger_error(phpgi_error_message(__CLASS__, __FUNCTION__, "Cannot use Shared Memory Driver in Windows."), E_USER_ERROR);

            $this->handle = shm_attach($this->id, PHPGI_SHM_SIZE, PHPGI_SHM_PERMS);
            $this->sem_id = sem_get($this->id, 2);
            
            return $this->valid;
            
        }

        function set ($data) {

            sem_acquire($this->sem_id);
            shm_put_var($this->handle, $this->id, $data);
            sem_release($this->sem_id);
            
        }

        function get () {

            sem_acquire($this->sem_id);
            $data = shm_get_var($this->handle, $this->id);
            sem_release($this->sem_id);
            return $data;
            
        }

        function delete () {

            return shm_put_var($this->handle, $this->id, null)
                   & sem_remove($this->sem_id)
                   & shm_remove($this->handle)
                   & shm_detach($this->handle);
            
        }

        function valid () {

            // TODO: Find a way to know if a handle is valid
            return true;
            
        }

    }

?>