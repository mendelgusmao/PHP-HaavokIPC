<?php

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * CallsQueue is a container of Calls and is responsible on processing
     * the queue, invoking the calls and the callback of a call
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

    class CallsQueue {

        var $name = "Calls";
        
        var $queue = array();
        var $index = 0;

        function create ($callee, $parameters = void, $constructor_parameters = void, $callback = void, $additional_callback_parameters = void) {
            return $this->enqueue(
                new Call($callee, $parameters, $constructor_parameters, $callback, $additional_callback_parameters)
            );
        }
        
        function enqueue () {

            $calls = func_get_args();

            if (0 == count($calls))
                trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                    "No calls defined."), E_USER_ERROR);

            foreach ($calls as $call)
                if (is_a($call, "Call")) {
                    $call->index = ++$this->index;
                    $this->queue[$this->index] = $call;
                }
                else {
                    trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                        "Trying to enqueue something that is not a Call."), E_USER_ERROR);
                }

            return $this;
        }

        function dequeue ($item) {

            $index = (int) (is_a($item, "Call")
                   ? $item->index
                   : $index = $item);

            if (isset($this->queue[$index]))
                unset($this->queue[$index]);

            return $this;
        }

        function process () {

            if (HIPC_IS_BACKEND) {

                $instances = new Instances();
                $wrappers = new Wrappers();                
                
                foreach ($this->queue as $index => $call) {
                    $call->invoke($instances, $wrappers);
                    $this->queue[$index] = $call;
                }

                return $this->queue;
                
            }
            else {
                trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                    "Unable to process Calls queue in the frontend."), E_USER_ERROR);
            }
        }

        function process_callbacks() {

            if (!HIPC_IS_BACKEND) {
                
                foreach ($this->queue as $call)
                    $call->callback();
                
            }
            else {
                trigger_error(hipc_error_message(__CLASS__, __FUNCTION__,
                    "Cannot execute callbacks in backend."), E_USER_ERROR);
            }

        }

        function clear () {

            $this->queue = array();

        }

        function __toString() {

            $calls = array();

            foreach ($this->queue as $call)
                $calls[] = (string) $call;

            return "{" . implode(", ", $calls) . "}";

        }

    }
    
?>