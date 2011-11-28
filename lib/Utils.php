<?php

    function gipc_error_message ($class, $function, $message) {

        $end = GIPC_IS_BACKEND ? "Backend" : "Frontend";
        $version = PHP_VERSION;
        $error_message = "PHP-Ghetto-IPC (%s [%s])::%s::%s: %s";

        return sprintf($error_message, $end, $version, $class, $function, $message);

    }

    function gipc_var_dump () {
        ob_start();
        $variable = func_get_args();
        call_user_func_array('var_dump', $variable);
        $return = ob_get_clean();

        return substr($return, 0, strpos($return, " "));
    }
    
    function gipc_to_array ($var) {
        
        return is_array($var) ? $var : ($var == void ? array() : array($var));
            
    }

?>
