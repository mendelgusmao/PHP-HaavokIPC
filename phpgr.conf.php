<?php

    /* Backend binary */
    define("PHPGR_BACKEND_BIN", "/usr/bin/php");

    /* FilePersistence config */
    define("PHPGR_EXT", ".persistence");
    define("PHPGR_TMP", dirname(__FILE__) . "/temp/");

    /* Logging config */
    define("PHPGR_LOGFILE", PHPGR_TMP . "/log.txt");
    define("PHPGR_LOG", true);

    /* Memcache config */
    define("PHPGR_MEMCACHED", "127.0.0.1");
    define("PHPGR_MEMCACHEDP", 11211);
    
?>