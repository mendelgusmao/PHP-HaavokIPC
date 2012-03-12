<?php

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

    define("HIPC_DIR", realpath(dirname(__FILE__) . "/../") . "/");
    define("HIPC_APPLICATION", realpath($_SERVER["argv"][0]));

    $includes = <<<INC
        lib/Utils
        lib/Constants
        Configuration
        lib/Runner
        lib/Instances
        lib/CallsQueue
        lib/Wrappers
        lib/Dependencies
        calls/Call
        calls/StaticCall
        calls/ObjectCall
        persistences/Persistence
        persistences/FilePersistence
        persistences/MemcachePersistence
        persistences/ShmPersistence
        persistences/RedisPersistence
        serializers/DefaultSerializer
        serializers/MsgpackSerializer
INC;

    foreach (explode("\n", $includes) as $include)
        include HIPC_DIR . trim($include) . ".php";

?>