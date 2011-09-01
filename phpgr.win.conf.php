<?php

    /* PHP 5 binary */
    define("PHPGR_BIN", "php4");
    define("PHPGR_PARAMS", "");

    /* Medium file config */
    define("PHPGR_EXT", ".php5b");
    define("PHPGR_TMP", dirname(__FILE__) . "\\temp\\");

    /* Logging config */
    define("PHPGR_LOGFILE", PHPGR_TMP . "\\log.txt");
    define("PHPGR_LOG", true);

    /* Memcache config */
    define("PHPGR_USE_MEMCACHE", false);
    define("PHPGR_MEMCACHED", "127.0.0.1");
    define("PHPGR_MEMCACHEDP", 11211);

    define("PHPGR_MEDIUM", PHPGR_MEDIUM_FILE);
    
?>