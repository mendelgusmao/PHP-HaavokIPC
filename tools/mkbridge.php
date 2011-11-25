<?php

    define("BASE", dirname(__FILE__) . "/");
    define("OUTDIR", getcwd());
    
    include BASE . '../GhettoIPC.class.php';
    include "extract.php";

    $lang = $argv[1];

    $definitions = parse_ini_file(BASE . "/defs.ini", true);
    $definitions = $definitions[$lang];    

    @rmdir("./$lang");
    mkdir("./$lang");

    foreach(array("drivers", "temp", "tests", "serializers", "calls") as $dir) {
        rmdir(OUTDIR . "/$lang/" . $dir);
        mkdir(OUTDIR . "/$lang/" . $dir);
    }
    
    $includes .= "\nGhettoIPC.class";
    
    foreach (explode("\n", $includes) as $include) {
        $include = trim($include);
        $file = OUTDIR . "/$lang/" . str_replace(".class", "", $include) . "." . $definitions["ext"];
        file_put_contents($file, extract_class($lang, BASE . "/../" . $include . ".php", $definitions));
    }
        
        
?>
