<?php

    require '../../Bridge.class.php';
    
    $persistence = new FilePersistence();
    $bridge = new Bridge($persistence);
    
    $bridge->import();
    $bridge->execute();
    $bridge->export();
    
    file_put_contents("backend.txt", "fui executado");