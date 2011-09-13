<?php

    /* Backend binary */
    define("PHPGR_BACKEND_BIN", "/usr/bin/php");

    /* Define whether backend's STDOUT will be sent to the frontend */
    define("PHPGR_NO_BACKEND_OUTPUT", false);

    /* FilePersistence config */
    define("PHPGR_EXT", ".php5b");
    define("PHPGR_TMP", dirname(__FILE__) . "/temp/");

    /* Logging config */
    define("PHPGR_LOGFILE", PHPGR_TMP . "/log.txt");
    define("PHPGR_LOG", true);

    /* Memcache config */
    define("PHPGR_USE_MEMCACHE", false);
    define("PHPGR_MEMCACHED", "127.0.0.1");
    define("PHPGR_MEMCACHEDP", 11211);
    
?>