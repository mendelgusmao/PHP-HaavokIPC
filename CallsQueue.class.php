<?php

    /**
     * Part of PHP-Ghetto-RPC, a library to execute PHP 5 code under a PHP 4 instance
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
        var $instances;

        function __construct() {
        
            $this->instances = new Instances();
        
        }
        
        function create ($class_method, $parameters = null,
                         $constructor_parameters = null, $callback = null) {
            
            $call = new Call($this->instances,
                             $class_method, $parameters, $constructor_parameters, $callback);
            
            return $this->enqueue($call);
            
        }
        
        /**
         * Enqueue a Call
         *
         * @param Call $call The call to be enqueued
         * @return int Index of the queue or -1 if $call is not a Call
         */
        function enqueue ($call)    {

            $index++;

            if (is_a($call, "Call")) {
                $call->index = $index;
                $this->queue[$index] = $call;
            }
            else {
                $index = -1;
            }
            
            return $index;
        }

        /**
         * Remove a Call from the queue
         *
         * @param mixed int or Call The index of the call to be removed
         * @return bool true if the index exists in the queue, false if not
         */
        function dequeue ($item) {

            $index = is_a($call, "Call")
                   ? $item->item
                   : $index = $item;

            if ($exists = isset($this->queue[$index]))
                unset($this->queue[$index]);

            return $exists;
        }

        /**
         * Process the queue of calls
         */
        function process () {

            if (PHPGR_IS_BACKEND) {

                foreach ($this->queue as $index => $call) {
                    $call->invoke();
                    $this->queue[$index] = $call;
                }

                return $this->queue;
                
            }
            else {
                trigger_error("PHP-Ghetto-RPC::CallsQueue::process: Unable to process Calls queue in the front end.", E_USER_ERROR);
            }
        }

        function __toString() {

            $calls = array();

            foreach ($this->queue as $call)
                $calls[] = (string) $call;

            return "{" . implode(",", $calls) . "}";

        }
    }
    
?>