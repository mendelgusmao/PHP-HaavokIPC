<?php

    function callback () {
        $args = func_get_args();
        echo "Callback called in frontend\n";
        print_r($args);
    }
    
?>
