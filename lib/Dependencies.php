<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * Dependencies is a class to inject dependencies in objects that inherit from it
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

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
