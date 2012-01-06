<?php

    /**
     * Part of HaavokIPC, a library to execute PHP code between different
     * PHP versions, usually from PHP 4 (called frontend) to 5 (called backend).
     *
     * DefaultSerializer is a serializer that uses basic serialize/unserialize
     * PHP functions
     *
     * @author Mendel Gusmao <mendelsongusmao () gmail.com>
     * @copyright Mendel Gusmao
     * @version 1.4
     *
     */

    class DefaultSerializer {
        
        var $name = "Serializer";
        
        function to ($data) {
            return serialize($data);
        }
        
        function from ($data) {
            return unserialize($data);
        }
        
    }

?>
