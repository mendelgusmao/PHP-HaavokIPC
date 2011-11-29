<?php

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * Simple functions that must remain in global scope
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

    function hipc_error_message ($class, $function, $message) {

        $end = HIPC_IS_BACKEND ? "Backend" : "Frontend";
        $version = PHP_VERSION;
        $error_message = "HaavokIPC (%s [%s])::%s::%s: %s";

        return sprintf($error_message, $end, $version, $class, $function, $message);

    }

    function hipc_var_dump () {
        ob_start();
        $variable = func_get_args();
        call_user_func_array('var_dump', $variable);
        $return = ob_get_clean();

        return substr($return, 0, strpos($return, " "));
    }
    
    function hipc_to_array ($var) {
        
        return is_array($var) ? $var : ($var == void ? array() : array($var));
            
    }

?>
