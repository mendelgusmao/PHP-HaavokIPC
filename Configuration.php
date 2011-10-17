<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called front end) to 5 (called back end).
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

    /* Suffix for unique execution id */
    define("GIPC_ID_PREFIX", "");
    
    /* Logging config */
    define("GIPC_LOGFILE", "/var/tmp/gipc.log");
    define("GIPC_LOG", true);

    /* Define whether GhettoIPC.class.php will be prepend in back end with "-d auto_prepend_file" argument */
    define("GIPC_PREPEND_IPC_CLASS", true);

    /* Force GhettoIPC to use output buffering in back end */
    define("GIPC_FORCE_NO_OUTPUT", false);

?>