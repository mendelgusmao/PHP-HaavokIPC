<?php

    require '../../Bridge.class.php';

    $backend = dirname(__FILE__) . '/backend.php';
    
    $calls = new CallsQueue();
    $calls->enqueue(new Call("php_uname", null, null, "echo"));
    
    $persistence = new FilePersistence();
    $bridge = new Bridge($persistence, $backend, $calls);
    
    $bridge->execute(true, true);
    $bridge->callback();
    
    echo "#################################################################\n";
    echo $bridge->content;
    echo "#################################################################\n";