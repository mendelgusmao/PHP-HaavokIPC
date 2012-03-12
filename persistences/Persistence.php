<?php

    class Persistence {
        
        var $serializer;
        
        function __construct ($serializer = null) {
            
            $this->Persistence($serializer);

        }
        
        function Persistence ($serializer = null) {
            
            if (is_null($serializer))
                $serializer = new DefaultSerializer;
            
            $this->serializer = $serializer;
            
        }
        
    }

?>
