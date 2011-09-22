<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP 5 code under a PHP 4 instance
     *
     * CallsQueue is a container of Calls and is responsible on processing
     * the queue, making the calls
     *
     * @author Mendel Gusmao <mendelsongusmao@gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.1
     *
     */

    class CallsQueue {

        var $queue;
        var $index = 0;

        function create ($class_method, $parameters = null,
                         $constructor_parameters = null, $callback = null) {
            
            $call = new Call($class_method, $parameters, $constructor_parameters, $callback);

            return $this->enqueue($call);
            
        }
        
        /**
         * Enqueue a Call
         *
         * @param Call $call The call to be enqueued
         * @return int Index of the queue or -1 if $call is not a Call
         */
        function enqueue () {

            $calls = func_get_args();

            if (0 == count($calls))
                trigger_error("PHP-Ghetto-IPC::CallsQueue::enqueue: No calls defined.", E_USER_ERROR);

            foreach ($calls as $call)
                if (is_a($call, "Call")) {
                    $call->index = ++$this->index;
                    $this->queue[$this->index] = $call;
                }
                else {
                    trigger_error("PHP-Ghetto-IPC::CallsQueue::enqueue: Trying to enqueue something that is not a Call.", E_USER_ERROR);
                }

            return $this;
        }

        /**
         * Remove a Call from the queue
         *
         * @param mixed int or Call The index of the call to be removed
         * @return bool true if the index exists in the queue, false if not
         */
        function dequeue ($item) {

            $index = (int) (is_a($item, "Call")
                   ? $item->index
                   : $index = $item);

            if (isset($this->queue[$index]))
                unset($this->queue[$index]);

            return $this;
        }

        /**
         * Process the queue of calls
         */
        function process () {

            $instances = new Instances();

            if (PHPGI_IS_BACKEND) {

                foreach ($this->queue as $index => $call) {
                    $call->index = $index;
                    $call->invoke($instances);
                    $this->queue[$index] = $call;
                }

                return $this->queue;
                
            }
            else {
                trigger_error("PHP-Ghetto-IPC::CallsQueue::process: Unable to process Calls queue in the frontend.", E_USER_ERROR);
            }
        }

        function process_callbacks() {

            if (PHPGI_IS_BACKEND)
                trigger_error("PHP-Ghetto-IPC::GhettoIPC::callback: Cannot execute callbacks in backend.");

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