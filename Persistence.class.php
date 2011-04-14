<?php

    class Persistence {

        var $id;
        var $source;
        var $data;
        var $valid;

        function initialize ($id, $fallback_source = false);

        function set ($data);

        function get ();

        function delete ();

        function valid ();

        function fallback ($source);
        
    }

?>
