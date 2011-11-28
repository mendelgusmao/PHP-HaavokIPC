<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * Call is an object responsible for storing the information of a call,
     * the function that will be called in backend, the objects
     * it will use and the invoking of itself and its callback (if any)
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
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

        function __construct ($callee, $parameters = void, $callback = void, $additional_callback_parameters = void) {

            $this->Call($callee, $parameters, $callback, $additional_callback_parameters);

        }

        function Call ($callee, $parameters = void, $callback = void, $additional_callback_parameters = void) {

            $this->parameters = $parameters;
            $this->callback = $callback;
            $this->additional_callback_parameters = $additional_callback_parameters;

            $this->method = $callee;

        }

        function invoke (&$instances, &$wrappers) {

            $parameters = gipc_to_array($this->parameters);

            if (function_exists($this->method)) {
                $this->return = call_user_func_array($this->method, $parameters);
            }
            else {

                if ($wrappers->has($this->method)) {
                    $method = $this->method;
                    $this->return = $wrappers->$method($parameters);
                }
                else {
                    $this->return = void;
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                        "Function '{$this->method}' not found."), E_USER_ERROR);
                }

            }

            $this->_filter_resource_return();

        }

        function callback () {
            
            if (empty($this->callback) || $this->callback == void)
                return void;
            
            $additional_callback_parameters = gipc_to_array($this->additional_callback_parameters);            
            $callback = gipc_to_array($this->callback);            
            
            array_unshift($additional_callback_parameters, $this->return);

            if (2 == count($callback)) {

                /*
                 * NOT IMPLEMENTED
                 * @TODO: PHP 4 + call_user_func + Static method calls = WAT?
                 * Possible solution: use call to functions that consume
                 * the objects you need in the frontend. They'll be responsible
                 * for instantiating these objects
                 */
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Cannot execute static method calls in PHP 4."), E_USER_ERROR);

                $class = $callback[0];
                $method = $callback[1];

                if (method_exists($class, $method)) {
                    call_user_func_array(
                        array($class, $method),
                        $additional_callback_parameters
                    );
                }
                else {
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                        "Error calling method {$method}() of {$class}. Method not defined."), E_USER_ERROR);
                }
                
            }
            else if (1 == count($callback)) {

                if (function_exists($function = $callback[0])) {
                    call_user_func_array(
                        $function,
                        $additional_callback_parameters
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
            return $this->describe();
        }
        
        function describe ($with_callback = true) {

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

                $method = ($this->is_static ? "::" : "->") . $method;
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

            if ($callback != void && $with_callback)
                $string = sprintf("%s(%s)", $callback, $string);

            return $string;
        }

        function _filter_resource_return () {
            if (is_resource($this->return)) {
                
                $describe = $this->describe(false);
                
                $this->return = void;                
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                "Value returned by {$describe} is a resource."), E_USER_ERROR);
                
            }
        }
        
    }

?>