<?php

    class DefaultSerializer {
        
        var $name = "Serializer";
        
        function to ($data) {
            return serialize($data);
        }
        
        function from ($data) {
            return unserialize($data);
        }
        
    }

?>
