<?php

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * MemcacheDriver is the class responsible for writing and reading data generated
     * by HaavokIPC to a memcache daemon. After data is read by the frontend
     * (meaning the end of the process), the item is deleted.
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */
    class MemcacheDriver extends Driver {
        
        var $name = "Driver";

        var $id;
        var $handle;
        var $valid;
        var $server;
        var $port;
        
        function initialize (&$ipc) {
            
            $this->server = $ipc->configuration["memcached_server"];
            $this->port = $ipc->configuration["memcached_port"];

            if (!$this->server)
                $this->server = "127.0.0.1";
            
            if (!$this->port)
                $this->port = 11211;
            
            $this->id = $ipc->id();
            $this->valid = false;

            if (class_exists("Memcache")) {

                $this->handle = new Memcache;

                if ($this->handle->addServer($this->server, $this->port)) {
                    $this->valid = true;
                }
                else {
                    trigger_error(hipc_error_message(__CLASS__, __FUNCTION__, 
                        "Couldn't connect to memcached at " . $this->server . ":" . HIPC_MEMCACHEDP), E_USER_ERROR);
                }

            }
            else {
                trigger_error(hipc_error_message(__CLASS__, __FUNCTION__, 
                    "Memcache is not enabled."), E_USER_ERROR);
            }

            return $this->valid;
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