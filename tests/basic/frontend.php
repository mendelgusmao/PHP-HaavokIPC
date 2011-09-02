<?php

    function foobar ($var) {
        echo "--- Foobar callback --- ";
        var_dump($var);
    }

    require dirname(__FILE__) . '/../../Bridge.class.php';
    require dirname(__FILE__) . '/../../phpgr.conf.php';

    $backend = dirname(__FILE__) . '/backend.php';
    
    $calls = new CallsQueue();
    $instances = new Instances();
    
    $calls->enqueue(
        new Call($instances, "php_uname", null, null, "foobar")
    );

    $bridge = new Bridge(new FilePersistence(), $backend, $calls);
    
    $bridge->execute(true, true, true); // import, export, execute callbacks
    
    echo "#################################################################\n";
    echo $bridge->output;
    echo "#################################################################\n";