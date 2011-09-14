<?php

    class Instances {
    
        var $instances;

        function Instances() {
            $this->instances = array();
        }
        
        function add ($instance) {
        
            $class_name = $this->_get_class($instance);

            if (!isset($this->instances[$class_name]))
                $this->instances[$class_name] = array();

            return array_push($this->instances[$class_name], $instance) - 1;
        }
        
        function get ($class_name, $index = -1) {

            $instance = null;
            
            if ($this->has_instances_of($class_name))
                if (-1 == $index)
                    $instance = end($this->instances[$class_name]);
                else
                    if ($this->_instance_exists($class_name, $index))
                        $instance = $this->instances[$class_name][$index];

            if (is_null($instance) || !is_a($instance, $class_name))
                trigger_error("PHP-Ghetto-RPC::Instances::get: " .
                    (-1 == $index
                        ? "No instances of class '{$class_name}' found in instances container."
                        : "Instance #{$index} of class '{$class_name}' not found in instances container."),
                    E_USER_ERROR
                );
                
            return $instance;
        }
        
        function get_or_add ($class_name, $constructor_parameters) {

            if ($this->has_instances_of($class_name))
                return $this->get($class_name);

            $object = $this->_new($class_name, $constructor_parameters);

            return $this->get($class_name, $this->add($object));
                    
        }

        function remove ($instance, $index = -1) {
        
            $class_name = $this->_get_class($instance);
        
            if ($exists = $this->_instance_exists($instance, $index))
                unset($this->instances[$class_name][$index]);

            return $exists;
        }        
 
        function clear ($instance) {
        
            $class_name = $this->_get_class($instance);
        
            if ($exists = $this->has_instances_of($class_name))
                unset($this->instances[$class_name]);

            return $exists;
        }      

        function has_instances_of ($class_name) {
        
            $class_name = $this->_get_class($class_name);
        
            return isset($this->instances[$class_name]) 
                   && count($this->instances[$class_name]);
        
        }

        function _instance_exists ($class_name, $index) {
        
            $class_name = $this->_get_class($class_name);
        
            return $this->has_instances_of($class_name) 
                   && isset($this->instances[$class_name][$index]);
        
        }
        
        function _get_class ($object) {
        
            return is_object($object) ? get_class($object) : $object;
        
        }

        function _new ($class_name, $constructor_parameters) {

            $parameters = array();

            $values = array_values($constructor_parameters);

            foreach (array_keys($values) as $index)
                $parameters[] = "\$values[{$index}]";

            $parameters = implode(", ", $parameters);

            eval("\$object = new $class_name($parameters);");

            return $object;

        }
        
    }

?>