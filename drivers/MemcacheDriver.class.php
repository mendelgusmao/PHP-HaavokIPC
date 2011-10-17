<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * MemcacheDriver is the class responsible for writing and reading data generated
     * by GhettoIPC to a memcache daemon. After data is read by the frontend
     * (meaning the end of the process), the item is deleted.
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.3
     *
     */
    class MemcacheDriver {

        var $id;
        var $handle;
        var $data;
        var $valid;

        function initialize ($id, $fallback_source = null) {

            $this->configure();
            
            $this->valid = false;

            if (class_exists("Memcache")) {

                $this->handle = new Memcache;

                if ($this->handle->addServer(GIPC_MEMCACHED, GIPC_MEMCACHEDP)) {
                    $this->valid = true;
                }
                else {
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__, "Couldn't connect to memcached at " . GIPC_MEMCACHED . ":" . GIPC_MEMCACHEDP), E_USER_ERROR);
                }

            }
            else {
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__, "Memcache is not enabled."), E_USER_ERROR);
            }

            return $this->valid;
        }

        function configure () {
            
            if (!defined("GIPC_MEMCACHED")) 
                define("GIPC_MEMCACHED", "127.0.0.1");
            
            if (!defined("GIPC_MEMCACHEDP"))
                define("GIPC_MEMCACHEDP", 11211);
        
        }
        
        function set ($data) {

            return $this->handle->set($this->id, $data, 1);

        }

        function get () {

            return $this->handle->get($this->id);

        }

        function delete () {

            return $this->handle->delete($this->id);

        }

        function valid () {

            return $this->valid;

        }

    }

?>