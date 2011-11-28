<?php

    include dirname(__FILE__) . '/../../GhettoIPC.class.php';

    $backend = dirname(__FILE__) . '/backend.php';
    
    $calls = new CallsQueue;
    
    $calls->enqueue(
        new Call("fopen", array(__FILE__, "r"), "var_dump"),
        new Call("compare_php_version", PHP_VERSION, "var_dump"),
        new ObjectCall(array("Backend", "backend_md5"), "The World", "var_dump"),
        new ObjectCall(array("Backend2", "backend_sha1", "The World"), void, "var_dump"),
        new StaticCall(array("Backend3", "backend_version"), void, "var_dump"),
        new ObjectCall(array("&Backend2", "backend_raw"), void, "callback", 1),
        new Call("eval", "print_r(123,1)", "var_dump")
            
    );

    function callback () {
        $args = func_get_args();
        echo "Callback called in frontend\n";
        print_r($args);
    }

    $ipc = new GhettoIPC("backend.php");
    $ipc->inject(
        new Profiles,
        new FileDriver(new MsgpackSerializer),
        new Runner,
        $calls
    )->execute(true);

    echo "--------------------- EXPORTED OUTPUT ---------------------------\n";
    echo $ipc->output;
    echo "------------------------- STDOUT --------------------------------\n";
    echo $ipc->stdout;
    echo "--------------------- EXPORTED ERRORS ---------------------------\n";
    print_r($ipc->errors);
    echo "--------------------------- END ---------------------------------\n";
