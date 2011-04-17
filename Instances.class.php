<?php

    class Instances {
    
        var $instances;
        
        function add ($instance) {

            $class_name = $this->_get_class($instance);
        
            return array_push($instances[$class_name], $instance) - 1;
            
        }
        
        function get ($class_name, $index = -1) {

            $instance = null;
            
            if ($this->has_instances($class_name))
                if ($index == -1)
                    $instance = end($this->instances[$class_name]);
                else
                    if (_instance_exists($class_name, $index))
                        $instance = $this->instances[$instances][$index];

            return $instance;
        
        }
        
        function get_or_add ($instance) {
        
            if (!$this->has_instances($instance))
                return $this->get($this->_get_class($instance), $this->add($instance));
        
        }

        function remove ($instance, $index = -1) {
        
            $class_name = $this->_get_class($instance);
        
            if ($exists = _instance_exists($instance, $index))
                unset($this->instances[$class_name][$index]);

            return $exists;
        }        
 
        function clear ($instance) {
        
            $class_name = $this->_get_class($instance);
        
            if ($exists = has_instances($class_name))
                unset($this->instances[$class_name]);

            return $exists;
        }      

        function has_instances ($class_name) {
        
            $class_name = $this->_get_class($instance);
        
            return isset($this->instances[$class_name]) 
                   && count($this->instances[$class_name]);
        
        }

        function _instance_exists ($class_name, $index) {
        
            $class_name = $this->_get_class($instance);
        
            return has_instances($class_name) 
                   && isset($this->instances[$class_name][$index]);
        
        }
        
        function _get_class($instance) {
        
            return is_object($instance) ? get_class($instance) : $instance;
        
        }
        
        function _instanceof($class, $instance) {
            
            return is_a($class, $instance);
            
        }
        
    }

?>