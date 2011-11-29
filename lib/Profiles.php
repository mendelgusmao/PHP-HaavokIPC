<?php

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called front end) to 5 (called back end).
     *
     * This is the Profiles container, used to store and retrieve configuration 
     * profiles based on regular expressions
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

    class Profiles {

        var $profiles = array();

        function retrieve ($file) {

            foreach ($this->profiles as $matcher => $profile) {
                if (preg_match($matcher, $file)) {
                    return $profile;
                }
            }
            
            trigger_error(hipc_error_message(__CLASS__, __METHOD__, 
                "No profile found for file '{$file}'"));
            
        }
        
        function add ($matcher, $profile) {
            
            $this->profiles[$matcher] = $profile;
            
            return $this;
            
        }

    }
    
?>
