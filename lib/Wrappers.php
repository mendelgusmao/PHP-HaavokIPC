<?php

    class Wrappers {

        function __call ($method, $parameters) {
            return call_user_func_array(array($this, "_" . $method), $parameters);
        }

        function has ($method) {
            return method_exists($this, "_" . $method);
        }

        function _eval ($parameters) {
            return eval("return {$parameters[0]};");
        }

    }

?>
