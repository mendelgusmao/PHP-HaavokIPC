<?php

    /**
     * Class responsible for the input and output of the data used by PHP-Ghetto-RPC
     * depending on the value of PHPGR_MEDIUM
     *
     * PHPGR_MEDIUM can be:
     * - PHPGR_MEDIUM_FILE
     *   PHP-Ghetto-RPC will use a file as the medium
     *
     * - PHPGR_MEDIUM_MEMCACHE
     *   PHP-Ghetto-RPC will use Memcache as the medium
     *
     * - PHPGR_MEDIUM_STD
     *   PHP-Ghetto-RPC will use STDIN/STDOUT as the Medim // NOT IMPLEMENTED YET
     *
     * @author Mendel Gusmao
     *
     */
    class MemcacheMedium extends Medium {

        var $name = "Memcache";

        var $id;
        var $medium_file;
        var $data;
        var $valid;

        /**
         * Constructor extender
         */
        function initialize ($id, $fallback_medium = null) {

            $this->valid = false;

            if (class_exists("Memcache")) {

                $this->medium_file = new Memcache;

                if ($this->medium->addServer(PHPGR_MEMCACHED, PHPGR_MEMCACHEDP)) {

                    $this->valid = true;

                }

            }

            if (!$this->valid && $fallback_medium) {

                 // There is no Memcache support
                 // Or, if there is, there is no server
                 // What would fallback do?

            }

            return $this->valid;
        }

        /**
         * Sets the data to the medium
         *
         * @param mixed $data The data to be set
         */
        function set ($data) {

            return $this->mem->set($this->id, $data, 1);

        }

        /**
         * Gets the data from the medium
         *
         * @return mixed $data The data
         */
        function get () {

            return $this->mem->get($this->id);

        }

        function delete ($id) {

            return $this->mem->delete($this->id);

        }

        /**
         * Verify if the medium is valid
         * @return bool
         */
        function valid () {

            return $this->valid;

        }

    }

?>