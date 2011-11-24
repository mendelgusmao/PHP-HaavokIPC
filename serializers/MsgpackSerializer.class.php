<?php

    class MsgpackSerializer {
        
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
