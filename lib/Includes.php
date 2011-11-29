<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

    $__DIR__ = realpath(dirname(__FILE__) . "/../") . "/";

    $includes = <<<INC
        lib/Utils
        lib/Constants
        lib/Profiles        
        Configuration
        lib/Runner
        lib/Instances
        lib/CallsQueue
        lib/Wrappers
        lib/Dependencies
        calls/Call
        calls/StaticCall
        calls/ObjectCall
        drivers/Driver
        drivers/FileDriver
        drivers/MemcacheDriver
        drivers/ShmDriver
        serializers/DefaultSerializer
        serializers/MsgpackSerializer
INC;

    foreach (explode("\n", $includes) as $include)
        include $__DIR__ . trim($include) . ".php";

?>