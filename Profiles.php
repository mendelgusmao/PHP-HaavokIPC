<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called front end) to 5 (called back end).
     *
     * This is the main configuration file, defining basic directives for
     * the functioning of GhettoIPC
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.3
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
            
            trigger_error(gipc_error_message(__CLASS__, __METHOD__, 
                "No profile found for file '{$file}'"));
            
        }
        
        function add ($matcher, $profile) {
            
            $this->profiles[$matcher] = $profile;
            
            return $this;
            
        }

    }
    
?>
