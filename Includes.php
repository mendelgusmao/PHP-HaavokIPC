<?php

    $__DIR__ = dirname(__FILE__) . "/";

    $includes = array(
        "Constants.php",
        "Configuration.php",
        "Runner.class.php",
        "Instances.class.php",
        "drivers/FileDriver.class.php",
        "drivers/MemcacheDriver.class.php",
        "Call.class.php",
        "CallsQueue.class.php",
    );

    foreach ($includes as $include)
        include $__DIR__ . $include;

?>
