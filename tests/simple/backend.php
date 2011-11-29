<?php

    $ipc = new HaavokIPC(new FileDriver);
    $ipc->set_export_options(HIPC_EXPORT_OUTPUT);
    $ipc->execute();

    function function_with_no_parameters () {
        return "foobar";
    }

    function function_with_parameters ($php_version) {
        return $php_version;
    }

    class ClassWithStaticMethods {

        static function method_with_no_parameters () {
            return "static foobar";
        }

        static function method_with_parameters ($php_version) {
            return $php_version;
        }

    }

    class ClassWithInstanceMethods {

        function method_with_no_parameters () {
            return "instance foobar";
        }

        function method_with_parameters ($php_version) {
            return $php_version;
        }

    }

    class ClassWithAttributes {

        private $attribute;

        function __construct ($attr) {
            $this->attribute = $attr;
        }

        function get_attribute () {
            return $this->attribute;
        }
    }
?>
