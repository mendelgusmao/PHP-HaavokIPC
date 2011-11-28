<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * Instances is a container of objects instantiated in backend. It's responsible
     * for instantiating, storing and retrieving an object.
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

    class Instances {
    
        var $instances = array();
        
        function add ($object) {
        
            $class = get_class($object);

            if (!isset($this->instances[$class]))
                $this->instances[$class] = array();

            return array_push($this->instances[$class], $object) - 1;
        }
        
        function get ($class, $index = -1) {

            $instance = null;
            
            if ($this->has_instances_of($class))
                if (-1 == $index) {
                    $instance = end($this->instances[$class]);
                }
                else {
                    if ($this->_instance_exists($class, $index))
                        $instance = $this->instances[$class][$index];
                }
                
            if (is_null($instance) || !is_a($instance, $class))
                trigger_error(
                    gipc_error_message(__CLASS__, __FUNCTION__,
                    -1 == $index
                        ? "No instances of class '{$class}' found in instances container."
                        : "Instance #{$index} of class '{$class}' not found in instances container.")
                );
                
            return $instance;
        }
        
        function get_or_add ($class, $constructor_parameters) {

            if ($this->has_instances_of($class))
                return $this->get($class);

            $object = $this->_new($class, $constructor_parameters);

            return $this->get($class, $this->add($object));
                    
        }

        function remove ($class, $index = -1) {
        
            if ($exists = $this->_instance_exists($class, $index))
                unset($this->instances[$class][$index]);

            return $exists;
        }        
 
        function clear ($class) {
        
            if ($exists = $this->has_instances_of($class))
                unset($this->instances[$class]);

            return $exists;
        }      

        function has_instances_of ($class) {
        
            return isset($this->instances[$class]) 
                   && count($this->instances[$class]);
        
        }

        function _instance_exists ($class, $index) {
        
            return $this->has_instances_of($class) 
                   && isset($this->instances[$class][$index]);
        
        }

        function _new ($class, $constructor_parameters) {

            $parameters = array();

            $values = array_values($constructor_parameters);

            foreach (array_keys($values) as $index)
                $parameters[] = "\$values[{$index}]";

            $parameters = implode(", ", $parameters);

            return eval("return new $class($parameters);");

        }
        
    }

?>