<?php

    require dirname(__FILE__) . '/../../Bridge.class.php';

    $backend = dirname(__FILE__) . '/backend.php';
    
    $calls = new CallsQueue;
    
    $calls->enqueue(
        new Call("compare_php_version", PHP_VERSION, null, "var_dump"),
        new Call(array("Backend", "backend_md5"), "The World", null, "var_dump"),
        new Call(array("Backend2", "backend_sha1"), null, array("str" => "The World"), "var_dump"),
        new Call(array("Backend3", "::backend_version"), null, null, "var_dump"),
        new Call(array("&Backend2", "backend_raw"), null, null, "var_dump")
    );

    $bridge = new Bridge(new FilePersistence, $backend, $calls);
    
    $bridge->execute(true); // execute callbacks
    
    echo "#################################################################\n";
    echo $bridge->output;
    echo "#################################################################\n";