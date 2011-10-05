<?php

    include dirname(__FILE__) . '/../../GhettoIPC.class.php';

    $backend = dirname(__FILE__) . '/backend.php';
    
    $calls = new CallsQueue;
    
    $calls->enqueue(
        new Call("fopen", array(__FILE__, "r"), null, "var_dump"),
        new Call("compare_php_version", PHP_VERSION, null, "var_dump"),
        new Call(array("Backend", "backend_md5"), "The World", null, "var_dump"),
        new Call(array("Backend2", "backend_sha1"), null, array("str" => "The World"), "var_dump"),
        new Call(array("Backend3", "::backend_version"), null, null, "var_dump"),
        new Call(array("&Backend2", "backend_raw"), null, null, "callback", 1)
    );

    function callback () {
        $args = func_get_args();
        echo "Callback called in frontend\n";
        print_r($args);
    }

    $ipc = new GhettoIPC(new FileDriver, $backend, $calls);
    $ipc->set_export_options(PHPGI_EXPORT_SERVER);
    $ipc->execute(true); // execute callbacks
    
    echo "#################################################################\n";
    echo $ipc->output;
    echo "#################################################################\n";
    echo $ipc->output2;
    echo "#################################################################\n";
    print_r($ipc->errors);
    echo "-----------------------------------------------------------------\n";