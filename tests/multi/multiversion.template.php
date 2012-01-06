<?php

    include 'simpletest/autorun.php';
    include dirname(__FILE__) . '/../../HaavokIPC.class.php';
    
    class HaavokIPCMultiVersionTest {
    
#BEGIN
        function testHaavokIPCWith__PHP1__And__PHP2__() {
        
            $content = shell_exec("C:\\PHP\\__PHP1V__\\php.exe -q \"__FRONTEND__\" C:\\PHP\\__PHP2V__\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From __PHP1V__ to __PHP2V__"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is __PHP2V__"
string(52) "Backend2(reused)->backend_raw(The World) = The World"
#################################################################
#################################################################
Backend constructed
Backend2 constructed with parameters The World
#################################################################           
XPCT;
            
            $this->assertTrue(
                strpos($content, $expectation) !== false
            );
        
        }
#END    
    }