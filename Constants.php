<?php

    define("PHPGR_MEDIUM_FILE", 0);
    define("PHPGR_MEDIUM_MEMCACHE", 1);
    define("PHPGR_MEDIUM_STD", 2);

    define("PHPGR_EXPORT_GLOBALS", 1);
    define("PHPGR_EXPORT_REQUEST", 2);
    define("PHPGR_EXPORT_POST", 4);
    define("PHPGR_EXPORT_GET", 8);
    define("PHPGR_EXPORT_SERVER", 16);
    define("PHPGR_EXPORT_COOKIE", 32);
    define("PHPGR_EXPORT_SESSION", 64);
    define("PHPGR_EXPORT_CONSTANTS", 128);
    define("PHPGR_EXPORT_CALLS", 256);
    define("PHPGR_EXPORT_HEADERS", 512);

?>
