<?php

    include dirname(__FILE__) . "/../Includes.php";

    function extract_class ($language, $file, $definitions) {
        if (!file_exists($file))
            return;

        $script = file_get_contents($file);

        $class_regex = "/class\\s?(?P<class>[a-z0-9_]+).*{/i";

        preg_match($class_regex, $script, $return);

        $class = $return["class"];

        $methods = get_class_methods($class);
        if (!$methods) $methods = array();
        $full_methods = array();

        $output = str_replace("<class>", $class, $definitions["class"]) . "\n\n";

        $vars = get_class_vars($class);
        if (!$vars) $vars = array();
        
        foreach ($vars as $variable => $value)
            $output .= str_replace("<variable>", $variable, $definitions[(is_array($value) ? "class" : "instance") . "var"]) . "\n";

        $output .= "\n";

        foreach ($methods as $method) {

            if ($method == "__construct" || $method == $class)
                $method = $definitions["constructor"];

            if ($method == "__toString")
                $method = $definitions["tostring"];        

            $regex_method = "/function $method.*\\((?<arguments>.*)\\).*{/i";
            preg_match($regex_method, $script, $return);

            $arguments = $return["arguments"];
            $arguments = str_replace('$', "", $arguments);

            $full_methods[$method] = $arguments;
        }

        foreach ($full_methods as $method => $arguments) {
            $output .= str_replace(array("<method>", "<arguments>"), array($method, $arguments), $definitions["method"]) . "\n\n";
            $output .= $definitions["method_end"] . "\n\n";
        }

        $output .= $definitions["class_end"] . "\n";
        
        return $output;
    }