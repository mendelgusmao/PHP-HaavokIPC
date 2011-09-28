<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * CallsQueue is a container of Calls and is responsible on processing
     * the queue, invoking the calls and the callback of a call
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.3
     *
     */

    class CallsQueue {

        var $queue = array();
        var $index = 0;

        function create ($class_method, $parameters = null, $constructor_parameters = null, $callback = null) {
            
            $call = new Call($class_method, $parameters, $constructor_parameters, $callback);
            return $this->enqueue($call);
        }
        
        function enqueue () {

            $calls = func_get_args();

            if (0 == count($calls))
                phpgi_trigger_error(__CLASS__, __FUNCTION__, "No calls defined.");

            foreach ($calls as $call)
                if (is_a($call, "Call")) {
                    $call->index = ++$this->index;
                    $this->queue[$this->index] = $call;
                }
                else {
                    phpgi_trigger_error(__CLASS__, __FUNCTION__, "Trying to enqueue something that is not a Call.");
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

            $instances = new Instances();

            if (PHPGI_IS_BACKEND) {

                foreach ($this->queue as $index => $call) {
                    $call->invoke($instances);
                    $this->queue[$index] = $call;
                }

                return $this->queue;
                
            }
            else {
                phpgi_trigger_error(__CLASS__, __FUNCTION__, "Unable to process Calls queue in the frontend.");
            }
        }

        function process_callbacks() {

            if (PHPGI_IS_BACKEND)
                phpgi_trigger_error(__CLASS__, __FUNCTION__, "Cannot execute callbacks in backend.");

            if (is_array($this->queue))
                foreach ($this->queue as $call)
                    $call->callback();

            return $this;

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