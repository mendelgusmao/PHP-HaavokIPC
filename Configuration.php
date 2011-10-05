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
    define("GIPC_BACKEND_BIN", "/usr/bin/php");

    /* FileDriver config */
    define("GIPC_ID_PREFIX", "");
    define("GIPC_EXT", ".persistence");
    define("GIPC_TMP", dirname(__FILE__) . "/temp/");

    /* Logging config */
    define("GIPC_LOGFILE", GIPC_TMP . "/log.txt");
    define("GIPC_LOG", true);

    /* MemcacheDriver config */
    define("GIPC_MEMCACHED", "127.0.0.1");
    define("GIPC_MEMCACHEDP", 11211);

    /* Define whether GhettoIPC.class.php will be prepend in backend with "-d auto_prepend_file" argument */
    define("GIPC_PREPEND_IPC_CLASS", true);

    define("GIPC_FORCE_NO_OUTPUT", false);

    /* ShmDriver config */
    define("GIPC_SHM_SIZE", 32768);
    define("GIPC_SHM_PERMS", 0666);
    
?>