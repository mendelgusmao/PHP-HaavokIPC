<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * MsgpackSerializer is a serializer that uses Msgpack's msgpack_pack
     * /msgpack_unpack functions
     * 
     * More information at http://msgpack.org/
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

    class MsgpackSerializer {
        
        var $name = "Serializer";
        
        function __construct () {
            $this->MsgpackSerializer();
        }

        function MsgpackSerializer () {
            
            if (!extension_loaded("msgpack")) {
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Msgpack extension not installed"), E_USER_ERROR);
            }
            
        }
        
        function to ($data) {
            return msgpack_pack($data);
        }
        
        function from ($data) {
            return msgpack_unpack($data);
        }
        
    }

?>
