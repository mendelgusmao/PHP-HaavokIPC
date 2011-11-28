<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called front end) to 5 (called back end).
     *
     * This is the main configuration file, defining basic directives for GhettoIPC
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

    $profiles = new Profiles;
    
    $profiles->add('/\\.php$/i', 
        array(
            "executable" => "/usr/bin/php",
            "id_prefix" => "",
            "logfile" => "/var/tmp/gipc.log",
            "logging" => true,
            "prepend_ipc_class" => true,
            "prepend_argument" => "-d auto_prepend_file",
            "prepend_string" => $__DIR__ . "GhettoIPC.php",
            "force_no_output" => false,
        )
    );

    $profiles->add('/\\.rb$/i', 
        array(
            "executable" => "/usr/local/bin/ruby",
            "id_prefix" => "",
            "logfile" => "/var/tmp/gipc.log",
            "logging" => true,
            "prepend_ipc_class" => true,
            "prepend_argument" => "-r",
            "prepend_string" => "/path/to/Ruby-Ghetto-IPC/Core.rb",
            "force_no_output" => false,
        )
    );    
    
?>