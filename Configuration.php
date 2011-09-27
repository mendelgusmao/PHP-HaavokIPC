<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * This is the main configuration file, defining basic directives for
     * the functioning of GhettoIPC
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.3
     *
     */

    /* Backend binary */
    define("PHPGI_BACKEND_BIN", "/usr/bin/php");

    /* FileDriver config */
    define("PHPGI_ID_PREFIX", "");
    define("PHPGI_EXT", ".persistence");
    define("PHPGI_TMP", dirname(__FILE__) . "/temp/");

    /* Logging config */
    define("PHPGI_LOGFILE", PHPGI_TMP . "/log.txt");
    define("PHPGI_LOG", true);

    /* MemcacheDriver config */
    define("PHPGI_MEMCACHED", "127.0.0.1");
    define("PHPGI_MEMCACHEDP", 11211);

    /* Define whether GhettoIPC.class.php will be prepend in backend with "-d auto_prepend_file" argument */
    define("PHPGI_PREPEND_IPC_CLASS", true);

    define("PHPGI_FORCE_NO_OUTPUT", false);

    /* ShmDriver config */
    define("PHPGI_SHM_SIZE", 10000);
    define("PHPGI_SHM_PERMS", 0666);
    
?>