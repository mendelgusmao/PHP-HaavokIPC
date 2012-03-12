<?php

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * RedisPersistence is the class responsible for writing and reading data generated
     * by HaavokIPC to a redis server. After data is read by the frontend
     * (meaning the end of the process), the item is deleted.
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */
    class RedisPersistence extends Persistence {
        
        var $name = "Persistence";

        var $id;
        var $handle;
        var $valid;
        var $server;
        var $port;
        
        function initialize (&$ipc) {
            
            $this->server = $ipc->configuration["redis_server"];
            $this->port = $ipc->configuration["redis_port"];

            if (!$this->server)
                $this->server = "127.0.0.1";
            
            if (!$this->port)
                $this->port = 6379;
            
            $this->id = $ipc->id();
            $this->valid = false;

            if (class_exists("Redis")) {

                $this->handle = new Redis;

                if ($this->handle->connect($this->server, $this->port)) {
                    $this->valid = true;
                }
                else {
                    trigger_error(hipc_error_message(__CLASS__, __FUNCTION__, 
                        "Couldn't connect to redis at " . $this->server . ":" . $this->port), E_USER_ERROR);
                }

            }
            else {
                trigger_error(hipc_error_message(__CLASS__, __FUNCTION__, 
                    "Redis is not enabled."), E_USER_ERROR);
            }

            return $this->valid;
        }
        
        function set ($data) {

            $data = $this->serializer->to($data);

            return $this->handle->set($this->id, $data, 3600);

        }

        function get () {

            $data = $this->handle->get($this->id);
            $data = $this->serializer->from($data);

            return $data;

        }

        function delete () {

            return $this->handle->delete($this->id);

        }

        function valid () {

            return $this->valid = ($this->handle->ping() == "+PONG");

        }

    }

?>