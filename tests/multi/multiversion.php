<?php

    include 'simpletest/autorun.php';
    include dirname(__FILE__) . '/../../GhettoIPC.class.php';
    
    class GhettoIPCMultiVersionTest {
    
#BEGIN
        function testGhettoIPCWith4_4_9And5_2_17() {
        
            $content = shell_exec("C:\\PHP\\4.4.9\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.2.17\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 4.4.9 to 5.2.17"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.2.17"
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
        function testGhettoIPCWith4_4_9And5_3_6() {
        
            $content = shell_exec("C:\\PHP\\4.4.9\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.6\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 4.4.9 to 5.3.6"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.6"
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
        function testGhettoIPCWith4_4_9And5_3_8() {
        
            $content = shell_exec("C:\\PHP\\4.4.9\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.8\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 4.4.9 to 5.3.8"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.8"
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
        function testGhettoIPCWith4_4_9And5_4a1() {
        
            $content = shell_exec("C:\\PHP\\4.4.9\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 4.4.9 to 5.4a1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a1"
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
        function testGhettoIPCWith4_4_9And5_4a3() {
        
            $content = shell_exec("C:\\PHP\\4.4.9\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a3\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 4.4.9 to 5.4a3"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a3"
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
        function testGhettoIPCWith4_4_9And5_4b1() {
        
            $content = shell_exec("C:\\PHP\\4.4.9\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4b1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 4.4.9 to 5.4b1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4b1"
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
        function testGhettoIPCWith5_2_17And4_4_9() {
        
            $content = shell_exec("C:\\PHP\\5.2.17\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\4.4.9\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.2.17 to 4.4.9"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 4.4.9"
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
        function testGhettoIPCWith5_2_17And5_3_6() {
        
            $content = shell_exec("C:\\PHP\\5.2.17\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.6\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.2.17 to 5.3.6"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.6"
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
        function testGhettoIPCWith5_2_17And5_3_8() {
        
            $content = shell_exec("C:\\PHP\\5.2.17\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.8\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.2.17 to 5.3.8"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.8"
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
        function testGhettoIPCWith5_2_17And5_4a1() {
        
            $content = shell_exec("C:\\PHP\\5.2.17\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.2.17 to 5.4a1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a1"
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
        function testGhettoIPCWith5_2_17And5_4a3() {
        
            $content = shell_exec("C:\\PHP\\5.2.17\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a3\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.2.17 to 5.4a3"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a3"
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
        function testGhettoIPCWith5_2_17And5_4b1() {
        
            $content = shell_exec("C:\\PHP\\5.2.17\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4b1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.2.17 to 5.4b1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4b1"
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
        function testGhettoIPCWith5_3_6And4_4_9() {
        
            $content = shell_exec("C:\\PHP\\5.3.6\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\4.4.9\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.6 to 4.4.9"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 4.4.9"
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
        function testGhettoIPCWith5_3_6And5_2_17() {
        
            $content = shell_exec("C:\\PHP\\5.3.6\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.2.17\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.6 to 5.2.17"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.2.17"
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
        function testGhettoIPCWith5_3_6And5_3_8() {
        
            $content = shell_exec("C:\\PHP\\5.3.6\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.8\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.6 to 5.3.8"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.8"
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
        function testGhettoIPCWith5_3_6And5_4a1() {
        
            $content = shell_exec("C:\\PHP\\5.3.6\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.6 to 5.4a1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a1"
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
        function testGhettoIPCWith5_3_6And5_4a3() {
        
            $content = shell_exec("C:\\PHP\\5.3.6\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a3\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.6 to 5.4a3"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a3"
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
        function testGhettoIPCWith5_3_6And5_4b1() {
        
            $content = shell_exec("C:\\PHP\\5.3.6\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4b1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.6 to 5.4b1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4b1"
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
        function testGhettoIPCWith5_3_8And4_4_9() {
        
            $content = shell_exec("C:\\PHP\\5.3.8\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\4.4.9\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.8 to 4.4.9"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 4.4.9"
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
        function testGhettoIPCWith5_3_8And5_2_17() {
        
            $content = shell_exec("C:\\PHP\\5.3.8\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.2.17\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.8 to 5.2.17"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.2.17"
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
        function testGhettoIPCWith5_3_8And5_3_6() {
        
            $content = shell_exec("C:\\PHP\\5.3.8\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.6\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.8 to 5.3.6"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.6"
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
        function testGhettoIPCWith5_3_8And5_4a1() {
        
            $content = shell_exec("C:\\PHP\\5.3.8\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.8 to 5.4a1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a1"
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
        function testGhettoIPCWith5_3_8And5_4a3() {
        
            $content = shell_exec("C:\\PHP\\5.3.8\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a3\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.8 to 5.4a3"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a3"
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
        function testGhettoIPCWith5_3_8And5_4b1() {
        
            $content = shell_exec("C:\\PHP\\5.3.8\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4b1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.3.8 to 5.4b1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4b1"
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
        function testGhettoIPCWith5_4a1And4_4_9() {
        
            $content = shell_exec("C:\\PHP\\5.4a1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\4.4.9\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a1 to 4.4.9"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 4.4.9"
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
        function testGhettoIPCWith5_4a1And5_2_17() {
        
            $content = shell_exec("C:\\PHP\\5.4a1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.2.17\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a1 to 5.2.17"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.2.17"
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
        function testGhettoIPCWith5_4a1And5_3_6() {
        
            $content = shell_exec("C:\\PHP\\5.4a1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.6\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a1 to 5.3.6"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.6"
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
        function testGhettoIPCWith5_4a1And5_3_8() {
        
            $content = shell_exec("C:\\PHP\\5.4a1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.8\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a1 to 5.3.8"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.8"
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
        function testGhettoIPCWith5_4a1And5_4a3() {
        
            $content = shell_exec("C:\\PHP\\5.4a1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a3\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a1 to 5.4a3"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a3"
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
        function testGhettoIPCWith5_4a1And5_4b1() {
        
            $content = shell_exec("C:\\PHP\\5.4a1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4b1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a1 to 5.4b1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4b1"
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
        function testGhettoIPCWith5_4a3And4_4_9() {
        
            $content = shell_exec("C:\\PHP\\5.4a3\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\4.4.9\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a3 to 4.4.9"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 4.4.9"
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
        function testGhettoIPCWith5_4a3And5_2_17() {
        
            $content = shell_exec("C:\\PHP\\5.4a3\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.2.17\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a3 to 5.2.17"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.2.17"
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
        function testGhettoIPCWith5_4a3And5_3_6() {
        
            $content = shell_exec("C:\\PHP\\5.4a3\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.6\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a3 to 5.3.6"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.6"
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
        function testGhettoIPCWith5_4a3And5_3_8() {
        
            $content = shell_exec("C:\\PHP\\5.4a3\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.8\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a3 to 5.3.8"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.8"
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
        function testGhettoIPCWith5_4a3And5_4a1() {
        
            $content = shell_exec("C:\\PHP\\5.4a3\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a3 to 5.4a1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a1"
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
        function testGhettoIPCWith5_4a3And5_4b1() {
        
            $content = shell_exec("C:\\PHP\\5.4a3\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4b1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4a3 to 5.4b1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4b1"
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
        function testGhettoIPCWith5_4b1And4_4_9() {
        
            $content = shell_exec("C:\\PHP\\5.4b1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\4.4.9\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4b1 to 4.4.9"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 4.4.9"
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
        function testGhettoIPCWith5_4b1And5_2_17() {
        
            $content = shell_exec("C:\\PHP\\5.4b1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.2.17\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4b1 to 5.2.17"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.2.17"
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
        function testGhettoIPCWith5_4b1And5_3_6() {
        
            $content = shell_exec("C:\\PHP\\5.4b1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.6\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4b1 to 5.3.6"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.6"
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
        function testGhettoIPCWith5_4b1And5_3_8() {
        
            $content = shell_exec("C:\\PHP\\5.4b1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.3.8\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4b1 to 5.3.8"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.3.8"
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
        function testGhettoIPCWith5_4b1And5_4a1() {
        
            $content = shell_exec("C:\\PHP\\5.4b1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a1\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4b1 to 5.4a1"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a1"
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
        function testGhettoIPCWith5_4b1And5_4a3() {
        
            $content = shell_exec("C:\\PHP\\5.4b1\\php.exe -q \"e:\\devs\\PHP-Ghetto-IPC\\tools\\..\\tests\\basic\\frontend.php\" C:\\PHP\\5.4a3\\php.exe");

            $expectation = <<<XPCT
string(43) "compare_php_version() = From 5.4b1 to 5.4a3"
string(66) "Backend->backend_md5(The World) = cb4adbc5d0fca498acaa58729f40fff9"
string(76) "Backend2->backend_sha1(The World) = e0a97fc105f02a28430eb29fda4b37ade9bd43f4"
string(46) "Backend3::backend_version() = Backend is 5.4a3"
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