<?php

    class DefaultSerializer {
        
        function to ($data) {
            return serialize($data);
        }
        
        function from ($data) {
            return unserialize($data);
        }
        
    }

?>
