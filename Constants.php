<?php

    /**
     * Part of PHP-Ghetto-IPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * Basic constants to set what data will be exported
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com> | @MendelGusmao
     * @copyright Mendel Gusmao
     * @version 1.3
     *
     */

    define("GIPC_EXPORT_GLOBALS", 1);
    define("GIPC_EXPORT_REQUEST", 2);
    define("GIPC_EXPORT_POST", 4);
    define("GIPC_EXPORT_GET", 8);
    define("GIPC_EXPORT_SERVER", 16);
    define("GIPC_EXPORT_COOKIE", 32);
    define("GIPC_EXPORT_SESSION", 64);
    define("GIPC_EXPORT_CONSTANTS", 128);
    define("GIPC_EXPORT_HEADERS", 256);
    define("GIPC_EXPORT_ENV", 512);
    define("GIPC_EXPORT_FILES", 1024);
    define("GIPC_EXPORT_DEBUG", 2048);
    define("GIPC_EXPORT_OUTPUT", 4096);
    define("GIPC_EXPORT_FORCE_NO_OUTPUT", 8192);

    define("GIPC_EXPORT_WAY_BOTH", 1);
    define("GIPC_EXPORT_WAY_F2B", 2);
    define("GIPC_EXPORT_WAY_B2F", 3);

    define("void", "§\0§\0\0§\0\0\0§");  

?>
