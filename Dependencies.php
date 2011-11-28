<?php

    class Dependencies {
        
        function inject () {
            
            $objects = func_get_args();
            
            foreach ($objects as $object) { 
            
                if (isset($object->name)) {
                    $class = $object->name;
                }
                else {
                    $class = get_class($object);
                }

                $class = strtolower($class);
                $this->$class = $object;

            }

            return $this;

        }
        
    }

?>
