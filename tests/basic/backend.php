<?php

    require '../../Bridge.class.php';
    require dirname(__FILE__) . '/../../phpgr.conf.php';
    
    $bridge = new Bridge(new FilePersistence);
    $bridge->import();
    $bridge->execute_backend();

    function backend_foobar($version) { return "From $version to " . PHP_VERSION; }