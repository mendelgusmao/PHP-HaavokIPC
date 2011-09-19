<?php

    require dirname(__FILE__) . '/../../GhettoIPC.class.php';
    $backend = dirname(__FILE__) . '/backend.php';

    $calls = new CallsQueue;

    $base = str_repeat("*", 4096);

    $calls->enqueue(
        new Call("sha1", $base, null, "result")
    );

    $ipc = new GhettoIPC(new FilePersistence, $backend, $calls);

    $time_fe = microtime_float();
    $sha1_fe = sha1($base);
    $time_fe = microtime_float() - $time_fe;
    echo "Frontend executed sha1(string(4096) \$base) [{$sha1_fe}] in ", $time_fe, " ms\n";

    $time_be = microtime_float();
    $ipc->execute(true);

    function result ($sha1_be) {
        global $time_be, $base;
        $time_be = microtime_float() - $time_be;
        echo "Backend  executed sha1(string(4096) \$base) [{$sha1_be}] in ", $time_be, " ms\n";
    }

    function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

?>
