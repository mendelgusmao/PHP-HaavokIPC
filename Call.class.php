<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP 5 code under a PHP 4 instance
     *
     * @author Mendel Gusmao <mendelsongusmao@gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.1
     *
     */

    class Call {

        var $index;
        var $class;
        var $method;
        var $is_static = false;
        var $parameters;
        var $constructor_parameters;
        var $callback;
        var $return;
        var $reuse_instance = false;

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
        function Call ($class_method, $parameters = null,
                       $constructor_parameters = null, $callback = null) {

            if (is_array($class_method)) {

                if (2 == count($class_method)) {

                    if ("&" == substr($class_method[0], 0, 1)) {
                        $this->reuse_instance = true;
                        $class_method[0] = substr($class_method[0], 1);
                    }

                    if ("::" == substr($class_method[1], 0, 2)) {
                        $this->is_static = true;
                        $class_method[1] = substr($class_method[1], 2);
                    }                    
                    
                    $this->class = $class_method[0];
                    $this->method = $class_method[1];
                    
                }
                else if (1 == count($class_method)) {
                    $this->method = $class_method[0];
                }
                else {
                    trigger_error("PHP-Ghetto-IPC::Call::Call: Wrong parameter count for class method/function name.");
                }

            }
            else {
                $this->method = $class_method;
            }

            $this->parameters = $parameters;
            $this->constructor_parameters = $constructor_parameters;
            $this->callback = $callback;

        }

        /**
         * Execute the functions or class methods passed by ::$methods
         * in the back end
         * @return mixed
         */
        function invoke ($instances) {

            $class = $this->class;
            $method = $this->method;
            $is_static = $this->is_static;
            $parameters = $this->parameters;
            $constructor_parameters = $this->constructor_parameters;
            $reuse_instance = $this->reuse_instance;

            if (!is_array($parameters))
                $parameters = is_null($parameters)
                            ? array()
                            : array($parameters);

            if (!is_array($constructor_parameters))
                $constructor_parameters = is_null($constructor_parameters)
                                        ? array()
                                        : array($constructor_parameters);

            if ($class) {
                if (class_exists($class)) {
                    
                    if ($is_static) {
                        $object = $class;
                    }
                    else {
                        $object = $instances->has_instances_of($class) && $reuse_instance
                                ? $instances->get($class)
                                : $instances->get_or_add($class, $constructor_parameters);
                    }

                    if (method_exists($object, $method)) {
                        $this->return = call_user_func_array(
                            array($object, $method),
                            $parameters
                        );
                    }
                    else {
                        trigger_error("PHP-Ghetto-IPC::Call::invoke: " . ($is_static ? "Static method" : "Method") . " '{$method}' not found in class '{$class}'.", E_USER_ERROR);
                    }
                    
                }
                else {
                    trigger_error("PHP-Ghetto-IPC::Call::invoke: Class '{$class}' doesn't exist.", E_USER_ERROR);
                }

            }
            else {
            
                if (function_exists($method)) {
                    $this->return = call_user_func_array($method, $parameters);
                    # $this->_log("call $method()");
                }
                else {
                    trigger_error("PHP-Ghetto-IPC::Call::invoke: Function '{$method}' not found.", E_USER_ERROR);
                }

            }

        }

        /**
         * Execute functions/methods in front end using the data returned
         * from the back end
         *
         * TODO: Allow multiple callbacks if $call->callback is an array?
         * Example: $call->callback = array("Function1", "Function2", "Function3")
         *          -> Function3(Function2(Function1($call->return)))
         *
         * @return boolean
         */
        function callback () {

            if (!isset($this->callback) || is_null($this->callback))
                return false;

            if (!is_array($this->callback))
                $this->callback = array($this->callback);

            if (count($this->callback) == 2) {

                // NOT IMPLEMENTED
                // TODO: PHP 4 + call_user_func + Static method calls = WAT?
                // Temporary solution: use call to functions that consume
                // the objects you need in the frontend. They'll be responsible
                // for instantiating these objects

                trigger_error("PHP-Ghetto-IPC::Call::callback: Cannot execute static method calls in PHP 4.", E_USER_ERROR);

                $class = $this->callback[0];
                $method = $this->callback[1];

                if (method_exists($class, $method)) {
                    call_user_func(array($class, $method), $this->return);
                }
                else {
                    trigger_error("PHP-Ghetto-IPC::Call::callback: Error calling method {$method}() of {$class}. Method not defined.", E_USER_ERROR);
                }
                
            }
            else if (count($this->callback) == 1) {

                if (function_exists($function = $this->callback[0])) {
                    call_user_func($function, $this->return);
                }
                else {
                    trigger_error("PHP-Ghetto-IPC::Call::callback: Error calling function {$function}(). Function not defined.", E_USER_ERROR);
                }

            }
            else {
                trigger_error("PHP-Ghetto-IPC::Call::callback: Wrong parameter count for class method/function name.");
            }

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

            if (is_array($parameters))
                $parameters = implode(", ", $this->parameters);
                
            $callback = $this->callback;
            $method = $this->method;

            return sprintf("%s(%s%s(%s))", $callback, $class, $method, $parameters);
        }

    }
    
?>