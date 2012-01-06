<?php

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called front end) to 5 (called back end).
     *
     * This is the main configuration file, defining basic directives for HaavokIPC
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */
    class Configuration {

        function profiles () {
            
            return array(
                array(
                    "matcher" => '/\\.php$/i',
                    "executable" => "/usr/bin/php",
                    "id_prefix" => "",
                    "temp_directory" => HIPC_DIR . "temp/",
                    "logfile" => "/var/tmp/gipc.log",
                    "logging" => true,
                    "prepend_ipc_class" => true,
                    "prepend_argument" => "-d auto_prepend_file",
                    "prepend_string" => HIPC_DIR . "HaavokIPC.php",
                    "force_no_output" => false,
                ),
                array(
                    "matcher" => '/\\.rb$/i',
                    "executable" => "/usr/local/bin/ruby",
                    "id_prefix" => "",
                    "logfile" => "/var/tmp/gipc.log",
                    "logging" => true,
                    "prepend_ipc_class" => true,
                    "prepend_argument" => "-r",
                    "prepend_string" => "/path/to/Ruby/HaavokIPC.rb",
                    "force_no_output" => false,
                )
            );
            
        }
        
        function retrieve ($file = null) {

            static $profiles;
            
            if (!$profiles)
                $profiles = Configuration::profiles();

            foreach ($profiles as $profile)
                if (preg_match($profile["matcher"], $file))
                    return $profile;
            
            trigger_error(hipc_error_message(__CLASS__, __METHOD__, 
                "No profile found for file '{$file}'"));
            
        }

    }

?>