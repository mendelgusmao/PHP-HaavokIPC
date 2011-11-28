<?php

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
        drivers/FileDriver
        drivers/MemcacheDriver
        drivers/ShmDriver
        serializers/DefaultSerializer
        serializers/MsgpackSerializer
INC;

    foreach (explode("\n", $includes) as $include)
        include $__DIR__ . trim($include) . ".php";

?>