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

    class FunctionCall extends Call {

        function __construct ($callee, $parameters = void, $callback = void, $additional_callback_parameters = void) {

            $this->Call($callee, $parameters, $callback, $additional_callback_parameters);
            
        }

        function FunctionCall ($callee, $parameters = void, $callback = void, $additional_callback_parameters = void) {

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

    }

?>