<?php

    $__DIR__ = dirname(__FILE__) . "/";

    $includes = <<<INC
        Constants
        Configuration
        Runner.class
        Instances.class
        Call.class
        CallsQueue.class
        drivers/FileDriver.class
        drivers/MemcacheDriver.class
        drivers/ShmDriver.class
INC;

    foreach (explode("\n", $includes) as $include)
        include $__DIR__ . trim($include) . ".php";

?>