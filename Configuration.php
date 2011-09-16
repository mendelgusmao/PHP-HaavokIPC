<?php

    /* Backend binary */
    define("PHPGI_BACKEND_BIN", "/usr/bin/php");

    /* FilePersistence config */
    define("PHPGI_EXT", ".persistence");
    define("PHPGI_TMP", dirname(__FILE__) . "/temp/");

    /* Logging config */
    define("PHPGI_LOGFILE", PHPGI_TMP . "/log.txt");
    define("PHPGI_LOG", true);

    /* Memcache config */
    define("PHPGI_MEMCACHED", "127.0.0.1");
    define("PHPGI_MEMCACHEDP", 11211);

    define("PHPGI_PREPEND_BRIDGE", true);
    define("PHPGI_FORCE_NO_OUTPUT", false);
    
?>