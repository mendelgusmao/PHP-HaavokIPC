<?php

    /**
     * Part of PHP-Ghetto-RPC, a library to execute PHP 5 code under a PHP 4 instance
     *
     * @author Mendel Gusmao <mendelsongusmao@gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.1
     *
     */

    class Call {

        var $class;
        var $method;
        var $parameters;
        var $constructor_parameters;
        var $callback;
        var $return;
        var $instances;
        var $reuse_instance;

        /**
         * Class constructor
         *
         * @param string $class_method The 'function' or 'Class::method' to be called in remote instance
         * @param array $parameters Parameters to be passed to the function/class method
         * @param array $constructor_parameters Parameters to be passed to the class constructor
         * @param string/Call $callback Callback to be called when the processing
         *                              in the remote instance is done
         *
         */
        function Call ($instances_container, $class_method, $parameters = null,
                       $constructor_parameters = null, $callback = null) {

            if (is_array($class_method)) {

                if (count($class_method == 2)) {

                    if (substr($class_method[0], 0, 1) == "&") {
                        $this->reuse_instance = true;
                        $class_method[0] = substr($class_method[0], 1);
                    }

                    $this->class = $class_method[0];
                    $this->method = $class_method[1];
                    
                }
                else if (count($class_method == 1)) {

                    $this->method = $class_method[0];

                }
                else {

                    trigger_error("PHP-Ghetto-RPC::Call::Call: Wrong parameter count for class method/function name.");

                }

            }

            $this->instances = $instances_container;            
            $this->parameters = $parameters;
            $this->constructor_parameters = $constructor_parameters;
            $this->callback = $callback;

        }

        /**
         * Execute the functions or class methods passed by ::$methods
         * in the back end
         * @return mixed
         */
        function invoke () {

            /* Oh yeah, this is a real problem!
             * To instantiate a new class for every Call in the queue
             * or develop a way to use the same instance for every subsequent call?
             */

            $class = $this->class;
            $method = $this->method;
            $static = $this->static;
            $parameters = $this->parameters;
            $constructor_parameters = $this->constructor_parameters;
            $callback = $this->callback;

            if ($parameters && !is_array($parameters))
                $parameters = array($parameters);

            if ($constructor_parameters && !is_array($constructor_parameters))
                $constructor_parameters = array($constructor_parameters);

            if ($class && class_exists($class)) {
                
                if ($this->instances->has_instances($class) && $this->reuse_instance)
                    $object = $this->instances->get($class);
                else
                    $object = $this->instances->get_or_add(new $class($constructor_parameters));
                
                if (method_exists($object, $method)) {
                
                    $return = call_user_func_array(array($object, $method), $params);
                    
                }
                else {
                    
                    trigger_error("PHP-Ghetto-RPC::Call::invoke: Method '{$method}' not found in class '{$class}'.", E_USER_ERROR);
                    
                }

                $this->calls[$i_method]->return = $return;

            }
            else {
            
                if (function_exists($method)) {
                
                    $return = call_user_func_array($method, $params);
                    # $this->_log("call $method()");
                    
                }
                else {
                    
                    trigger_error("PHP-Ghetto-RPC::Call::invoke: Function '{$method}' not found.", E_USER_ERROR);
                    
                }
                
                $this->calls[$i_method]->return = $return;

            }

            $i_method++;

            return $this->calls;
        }

        /**
         * Returns an human readable form of the class
         * @example  'Callback(Class(p1, p2, p3)::Method(p4, p5, p6))'
         *
         * @return string
         */
        function __toString () {
        
            if ($this->constructor_parameters)
                $constructor_parameters = "(" . implode(", ", $this->constructor_parameters) . ")";

            if ($this->class)
                $class = $this->class . $constructor_parameters . "::";

            $parameters = implode(", ", $this->parameters);
            $callback = $this->callback;
            $method = $this->method;

            return sprintf("%s(%s%s(%s))", $callback, $class, $method, $parameters);
        }

    }
    
?>