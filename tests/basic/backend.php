<?php

    require '../../Bridge.class.php';
    require dirname(__FILE__) . '/../../phpgr.conf.php';
    
    $bridge = new Bridge(new FilePersistence);
    $bridge->import();
    $bridge->execute_backend();

    function compare_php_version ($version) { return "From $version to " . PHP_VERSION; }

    class Backend {
        function __construct () { echo __CLASS__, " constructed\n"; }
        function backend_md5($input) { return md5($input); }
    }

    class Backend2 {
        private $str;
        function __construct($params) { echo __CLASS__, " constructed\n"; $this->str = $params["str"]; }
        function backend_sha1() { return sha1($this->str); }
        function backend_raw() { return $this->str; }
    }

    class Backend3 {
        function __construct () { echo __CLASS__, " constructed\n"; }
        static function backend_version() { return "Backend is " . PHP_VERSION; }
    }