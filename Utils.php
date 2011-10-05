<?php

    function phpgi_error_message ($class, $function, $message) {

        $end = GIPC_IS_BACKEND ? "Backend" : "Frontend";
        $version = PHP_VERSION;
        $error_message = "PHP-Ghetto-IPC (%s [%s])::%s::%s: %s";

        return sprintf($error_message, $end, $version, $class, $function, $message);

    }

?>
