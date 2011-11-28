<?php

    $__DIR__ = dirname(__FILE__) . "/";

    $includes = <<<INC
        Utils
        Constants
        Profiles        
        Configuration
        Runner.class
        Instances.class
        CallsQueue.class
        Wrappers.class
        Dependencies
        calls/Call.class
        calls/StaticCall.class
        calls/ObjectCall.class
        drivers/FileDriver.class
        drivers/MemcacheDriver.class
        drivers/ShmDriver.class
        serializers/DefaultSerializer.class
        serializers/MsgpackSerializer.class
INC;

    foreach (explode("\n", $includes) as $include)
        include $__DIR__ . trim($include) . ".php";

?>