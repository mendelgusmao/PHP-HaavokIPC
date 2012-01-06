<?php

    class Driver {
        
        var $serializer;
        
        function __construct ($serializer = null) {
            
            $this->Driver($serializer);

        }
        
        function Driver ($serializer = null) {
            
            if (is_null($serializer))
                $serializer = new DefaultSerializer;
            
            $this->serializer = $serializer;
            
        }
        
    }

?>
