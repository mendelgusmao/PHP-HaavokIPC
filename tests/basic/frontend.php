<?php

    echo "SOU O FRONTEND: ", PHP_VERSION, "\n";

    require dirname(__FILE__) . '/../../Bridge.class.php';
    require dirname(__FILE__) . '/../../phpgr.conf.php';

    $backend = dirname(__FILE__) . '/backend.php';
    
    $calls = new CallsQueue;
    
    $calls->enqueue(
        new Call("backend_foobar", PHP_VERSION, null, "var_dump"),
        new Call("backend_foobar", "The World", null, "var_dump")
    );

    $bridge = new Bridge(new FilePersistence, $backend, $calls);
    
    $bridge->execute(true, true, true); // import, export, execute callbacks
    
    echo "#################################################################\n";
    echo $bridge->output;
    echo "#################################################################\n";