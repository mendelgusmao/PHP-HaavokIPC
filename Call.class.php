<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * Call is an object responsible for storing the information of a call,
     * the class method or function that will be called in backend, the objects
     * it will use and the invoking of itself and its callback (if any)
     *
     * When reading $class_method, think in "class method" OR "function"
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.3
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
        var $additional_callback_parameters;
        var $return;
        var $reuse_instance = false;
        
        function __construct ($class_method, $parameters = null, $constructor_parameters = null, $callback = null, $additional_callback_parameters = null) {

            $this->Call($class_method, $parameters, $constructor_parameters, $callback, $additional_callback_parameters);
            
        }

        function Call ($class_method, $parameters = null, $constructor_parameters = null, $callback = null, $additional_callback_parameters = null) {

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
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__, "Wrong parameter count for class method/function name."), E_USER_ERROR);
                }

            }
            else {
                $this->method = $class_method;
            }

            $this->parameters = $parameters;
            $this->constructor_parameters = $constructor_parameters;
            $this->callback = $callback;

            if (!is_array($additional_callback_parameters))
                $additional_callback_parameters = is_null($additional_callback_parameters)
                                                ? array()
                                                : array($additional_callback_parameters);

            $this->additional_callback_parameters = $additional_callback_parameters;
            
        }

        function invoke (&$instances) {

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
                        trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                            "Method '{$method}' not found in class '{$class}'."), E_USER_ERROR);
                    }
                    
                }
                else {
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                        "Class '{$class}' doesn't exist."), E_USER_ERROR);
                }

            }
            else {

                if (function_exists($method)) {
                    $this->return = call_user_func_array($method, $parameters);
                }
                else {

                    $wrappers = new Wrappers;

                    if ($wrappers->has($method)) {
                        $this->return = $wrappers->$method($parameters);
                    }
                    else {
                        trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                            "Function '{$method}' not found."), E_USER_ERROR);
                    }
                    
                }

            }

            if (is_resource($this->return)) {
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Value returned is a resource."), E_USER_ERROR);
                $this->return = null;
            }

        }

        function callback () {

            if (!isset($this->callback) || is_null($this->callback))
                return false;

            if (!is_array($this->callback))
                $this->callback = array($this->callback);
            
            array_unshift($this->additional_callback_parameters, $this->return);

            if (2 == count($this->callback)) {

                /*
                 * NOT IMPLEMENTED
                 * @TODO: PHP 4 + call_user_func + Static method calls = WAT?
                 * Possible solution: use call to functions that consume
                 * the objects you need in the frontend. They'll be responsible
                 * for instantiating these objects
                 */
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Cannot execute static method calls in PHP 4."), E_USER_ERROR);

                $class = $this->callback[0];
                $method = $this->callback[1];

                if (method_exists($class, $method)) {
                    call_user_func_array(
                        array($class, $method),
                        $this->additional_callback_parameters
                    );
                }
                else {
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                        "Error calling method {$method}() of {$class}. Method not defined."), E_USER_ERROR);
                }
                
            }
            else if (1 == count($this->callback)) {

                if (function_exists($function = $this->callback[0])) {
                    call_user_func_array(
                        $function,
                        $this->additional_callback_parameters
                    );
                }
                else {
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                        "Error calling function {$function}(). Function not defined."), E_USER_ERROR);
                }

            }
            else {
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Wrong parameter count for class method/function name."), E_USER_ERROR);
            }

        }

        function __toString () {

            $class = $this->class;
            $method = $this->method;
            $parameters = $this->parameters;
            $constructor_parameters = $this->constructor_parameters;
            $callback = $this->callback;
            $parameters_types = array();

            if ($class) {
                if (is_array($constructor_parameters)) {

                    foreach ($constructor_parameters as $parameter)
                        $parameters_types[] = gipc_var_dump($parameter);

                    $constructor_parameters = "(" . implode(", ", $parameters_types) . ")";
                }

                $method = "::" . $method;
            }
            else {
                $constructor_parameters = gipc_var_dump($constructor_parameters);
            }

            $parameters_types = array();

            if (is_array($parameters)) {
                foreach ($parameters as $parameter)
                    $parameters_types[] = gipc_var_dump($parameter);

                $parameters = implode(", ", $parameters_types);
            }
            else {
                $parameters = gipc_var_dump($parameters);
            }

            $string = sprintf("%s%s%s(%s)", $class, $constructor_parameters, $method, $parameters);

            if ($callback)
                $string = sprintf("%s(%s)", $callback, $string);

            return $string;
        }

    }

?>