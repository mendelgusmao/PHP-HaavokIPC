<?php

    $bridge = new Bridge(new FilePersistence);
    $bridge->import();
    $bridge->execute();

    function compare_php_version ($version) { return __FUNCTION__ . "() = From $version to " . PHP_VERSION; }

    class Backend {
        function __construct () {
                echo __CLASS__, " constructed\n";
        }
        function backend_md5 ($input) {
            return __CLASS__ . "->" . __FUNCTION__ . "($input) = " . md5($input);
        }
    }

    class Backend2 {
        private $str;
        function __construct($str) {
            echo __CLASS__, " constructed with parameters {$str}\n";
            $this->str = $str;
        }
        function backend_sha1() { return __CLASS__ . "->" . __FUNCTION__ . "({$this->str}) = " . sha1($this->str); }
        function backend_raw() { return __CLASS__ . "(reused)->" . __FUNCTION__ . "({$this->str}) = " . $this->str; }
    }

    class Backend3 {
        function __construct () { echo __CLASS__, " constructed\n"; }
        static function backend_version() { return __CLASS__ . "::" . __FUNCTION__ . "() = " . "Backend is " . PHP_VERSION; }
    }