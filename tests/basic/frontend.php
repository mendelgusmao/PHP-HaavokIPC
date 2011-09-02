<?php

    require dirname(__FILE__) . '/../../Bridge.class.php';
    require dirname(__FILE__) . '/../../phpgr.conf.php';

    $backend = dirname(__FILE__) . '/backend.php';
    
    $calls = new CallsQueue();
    
    $calls->enqueue(
        new Call("php_uname", null, null, "var_dump")
    );

    $bridge = new Bridge(new FilePersistence(), $backend, $calls);
    
    $bridge->execute(true, true, true); // import, export, execute callbacks
    
    echo "#################################################################\n";
    echo $bridge->output;
    echo "#################################################################\n";