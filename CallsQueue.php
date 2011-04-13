<?php

    /**
     * Part of PHP-Ghetto-RPC, a library to execute PHP 5 code under a PHP 4 instance
     *
     * @author Mendel Gusmao <mendelsongusmao@gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.1
     *
     */

    class CallsQueue {

        var $queue;
        var $index;

        /**
         * Enqueue a Call
         *
         * @param Call $call The call to be enqueued
         * @return int Index of the queue
         */
        function enqueue ($call)    {
        
            $this->queue[(int) $index] = $call;

            return $index++;
        }

        /**
         * Remove a Call from the queue
         *
         * @param int $index The index of the queue to be removed
         * @return bool true if the index exists in the queue, false if not
         */
        function dequeue ($index) {
        
            if (isset($this->queue[$index])) {
            
                unset($this->queue[$index]);
                return true;
                
            }

            return false;
        }

        /**
         * Process the queue of calls
         */
        function process () {

            foreach($this->queue as $index => $call) {
                // NOT IMPLEMENTED YET
                $call->call();
            }

            return $this->queue;
        }
    }
    
?>