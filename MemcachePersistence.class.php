<?php

    /**
     * Class responsible for the input and output of the data used by PHP-Ghetto-IPC
     * depending on the value of PHPGR_MEDIUM
     *
     * PHPGR_MEDIUM can be:
     * - PHPGR_MEDIUM_FILE
     *   PHP-Ghetto-IPC will use a file as the source
     *
     * - PHPGR_MEDIUM_MEMCACHE
     *   PHP-Ghetto-IPC will use Memcache as the source
     *
     * - PHPGR_MEDIUM_STD
     *   PHP-Ghetto-IPC will use STDIN/STDOUT as the Medim // NOT IMPLEMENTED YET
     *
     * @author Mendel Gusmao
     *
     */
    class MemcachePersistence {

        var $name = "Memcache";

        var $id;
        var $source;
        var $data;
        var $valid;

        /**
         * Constructor extender
         */
        function initialize ($id, $fallback_source = null) {

            $this->valid = false;

            if (class_exists("Memcache")) {

                $this->source = new Memcache;

                if ($this->source->addServer(PHPGR_MEMCACHED, PHPGR_MEMCACHEDP)) {
                    $this->valid = true;
                }
                else {
                    trigger_error("PHP-Ghetto-IPC::MemcachePersistence::initialize: Couldn't connect to memcached at " . PHPGR_MEMCACHED . ":" . PHPGR_MEMCACHEDP, E_USER_ERROR);
                }

            }
            else {
                trigger_error("PHP-Ghetto-IPC::MemcachePersistence::initialize: Memcache is not enabled.", E_USER_ERROR);
            }

            return $this->valid;
        }

        /**
         * Sets the data to the source
         *
         * @param mixed $data The data to be set
         */
        function set ($data) {

            return $this->source->set($this->id, $data, 1);

        }

        /**
         * Gets the data from the source
         *
         * @return mixed $data The data
         */
        function get () {

            return $this->source->get($this->id);

        }

        function delete () {

            return $this->source->delete($this->id);

        }

        /**
         * Verify if the source is valid
         * @return bool
         */
        function valid () {

            return $this->valid;

        }

    }

?>