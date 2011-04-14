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
        static $index = 0;

        /**
         * Enqueue a Call
         *
         * @param Call $call The call to be enqueued
         * @return int Index of the queue or -1 if $call is not a Call
         */
        function enqueue ($call)    {

            if ($call instanceof Call)
                $this->queue[$index++] = $call;
            else
                $index = -1;
            
            return $index;
        }

        /**
         * Remove a Call from the queue
         *
         * @param int $index The index of the queue to be removed
         * @return bool true if the index exists in the queue, false if not
         */
        function dequeue ($index) {
        
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
                    $call->call();
                    $this->queue[$index] = $call;
                }

                return $this->queue;
                
            }
            else {

                trigger_error("PHP-Ghetto-RPC: Unable to process Calls queue in the front end.", E_USER_ERROR);
                
            }
        }
    }
    
?>