<?php

    $__DIR__ = dirname(__FILE__) . "/";

    $includes = <<<INC
        Utils
        Constants
        Configuration
        Runner.class
        Instances.class
        CallsQueue.class
        calls/Call.class
        calls/StaticCall.class
        calls/ObjectCall.class
        Wrappers.class
        drivers/FileDriver.class
        drivers/MemcacheDriver.class
        drivers/ShmDriver.class
        serializers/DefaultSerializer.class
        serializers/MsgpackSerializer.class
INC;

    foreach (explode("\n", $includes) as $include)
        include $__DIR__ . trim($include) . ".php";

?>