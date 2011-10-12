<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * Call is an object responsible for storing the information of a call,
     * the class method or function that will be called in backend, the objects
     * it will use and the invoking of itself and its callback (if any)
     *
     * When reading $callee, think in "class method" OR "function"
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.3
     *
     */

    class ObjectCall extends Call {

        function __construct ($callee, $parameters = void, $callback = void, $additional_callback_parameters = void) {

            $this->Call($callee, $parameters, $callback, $additional_callback_parameters);
            
        }

        function ObjectCall ($callee, $parameters = void, $callback = void, $additional_callback_parameters = void) {

            $this->parameters = $parameters;
            $this->callback = $callback;            
            $this->additional_callback_parameters = $additional_callback_parameters;
            
            $this->_define_callee($callee);            
            
        }

        function _define_callee ($callee) {
            
            if (is_array($callee)) {

                if (3 == count($callee)) {

                    $this->class = $callee[0];
                    $this->method = $callee[1];
                    $this->constructor_parameters = $callee[2];

                }
                else if (2 == count($callee)) {

                    $this->class = $callee[0];
                    $this->method = $callee[1];

                }
                else {
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__, 
                        "Wrong parameter count for class method/function name."), E_USER_ERROR);
                }

            }
            
            if ("&" == substr($this->class, 0, 1)) {
                $this->reuse_instance = true;
                $this->class = substr($this->class, 1);
            }
            
            if (!$this->class) {
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__, 
                    "No class specified for method '{$scope['method']}'"), E_USER_ERROR);                        
            }              
            
        }
        
        function invoke (&$instances, &$wrappers) {

            $parameters = gipc_to_array($this->parameters);
            $constructor_parameters = gipc_to_array($this->constructor_parameters);
            
            if (class_exists($this->class)) {

                $object = $instances->has_instances_of($this->class) && $this->reuse_instance
                        ? $instances->get($this->class)
                        : $instances->get_or_add($this->class, $constructor_parameters);

                if (method_exists($object, $this->method)) {
                    $this->return = call_user_func_array(
                        array($object, $this->method),
                        $parameters
                    );
                }
                else {
                    $this->return = void;
                    trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                        "Method '{$this->method}' not found in class '{$this->class}'."), E_USER_ERROR);
                }

            }
            else {
                $this->return = void;
                trigger_error(gipc_error_message(__CLASS__, __FUNCTION__,
                    "Class '{$this->class}' doesn't exist."), E_USER_ERROR);
            }

            $this->_filter_resource_return();

        }

    }

?>