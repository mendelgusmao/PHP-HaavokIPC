<?php

    class Medium {

        var $id;
        var $medium_file;
        var $medium;
        var $data;
        var $valid;

        function initialize ($id, $fallback_medium = false);

        function set ($data);

        function get ();

        function delete ();

        function valid ();

        function fallback ($medium);
        
    }

?>
