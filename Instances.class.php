<?php

    class Instances {
    
        var $instances;
        
        function add ($class) {

            $class_name = get_class($class);
        
            return array_push($instances[$class_name], $class) - 1;
            
        }
        
        function get ($class, $index = -1) {

            $class_name = get_class($class);
        
            if ($exists = isset($this->instances[$class_name][$index]))
                return $this->instances[$class_name][$index];

            return $exists;            
        
        }

        function remove ($class, $index = -1) {
        
            $class_name = get_class($class);
        
            if ($exists = isset($this->instances[$class_name][$index]))
                unset($this->instances[$class_name][$index]);

            return $exists;
        }        
 
        function destroy ($class) {
        
            $class_name = get_class($class);
        
            if ($exists = isset($this->instances[$class_name]))
                unset($this->instances[$class_name]);

            return $exists;
        }       
 
    }

?>