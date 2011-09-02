<?php

    require '../../Bridge.class.php';
    
    $persistence = new FilePersistence();
    $bridge = new Bridge($persistence);
    
    $bridge->import();
    $bridge->execute();
    $bridge->export();

    file_put_contents(PHPGR_TMP."/php5_executado.txt", implode(" ", $argv));