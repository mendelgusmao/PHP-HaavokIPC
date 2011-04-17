<?php

    class Instances {
    
        var $instances;
        
        function add ($class) {

            $class_name = get_class($class);
        
            return array_push($instances[$class_name], $class) - 1;
            
        }
        
        function get ($class, $index = -1) {

            $class_name = get_class($class);
            $instance = null;
            
            if ($this->_has_instances($class_name))
                if ($index == -1)
                    $instance = end($this>instances[$instances]);
                else
                    if (_instance_exists($class_name, $index))
                        $instance = $this>instances[$instances][$index];

            return $instance;
        
        }

        function remove ($class, $index = -1) {
        
            $class_name = get_class($class);
        
            if ($exists = _instance_exists($class, $index))
                unset($this->instances[$class_name][$index]);

            return $exists;
        }        
 
        function clear ($class) {
        
            $class_name = get_class($class);
        
            if (_has_instances($class_name))
                unset($this->instances[$class_name]);

            return $exists;
        }      

        function _has_instances ($class_name) {
        
            return isset($this->instances[$class_name]) 
                   && count($this->instances[$class_name]);
        
        }

        function _instance_exists ($class_name, $index) {
        
            return _has_instances($class_name) 
                   && isset($this->instances[$class_name][$index]);
        
        }
        
    }

?>