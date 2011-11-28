<?php

    class Profiles {

        var $profiles = array();

        function retrieve ($file) {

            foreach ($profiles as $matcher => $profile) {
                if (preg_match($matcher, $file)) {
                    return $profile;
                }
            }
            
        }
        
        function add ($matcher, $profile) {
            
            $profiles[$matcher] = $profile;
            return $this;
            
        }
        
        function remove ($matcher) {
            
            unset($profiles[$matcher]);
            
        }

    }
    
?>
