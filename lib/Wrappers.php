<?php

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * Wrappers is a class to wrap language constructs that cannot be identified as
     * functions, like eval()
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

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
