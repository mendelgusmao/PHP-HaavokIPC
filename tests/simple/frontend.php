<?php

    /* Must include simpletest 1.0.1 (the last version with PHP 4 support */
    include 'simpletest/autorun.php';
    include dirname(__FILE__) . '/../../Bridge.class.php';

    class PHPGhettoIPC extends UnitTestCase {

        var $persistence;
        var $bridge;
        var $application;
        var $call_index = 0;
        var $calls;

        function setUp () {

            $this->application = realpath("backend.php");

            $calls = array(
                new Call("function_with_no_parameters"),
                new Call("function_with_parameters", PHP_VERSION),
                new Call(array("ClassWithStaticMethods", "::method_with_no_parameters")),
                new Call(array("ClassWithStaticMethods", "::method_with_parameters"), PHP_VERSION),
                new Call(array("ClassWithInstanceMethods", "method_with_no_parameters")),
                new Call(array("ClassWithInstanceMethods", "method_with_parameters"), PHP_VERSION),
                new Call(array("ClassWithAttributes", "get_attribute"), null, PHP_VERSION)
            );

            $this->calls = new CallsQueue;
            $this->persistence = new FilePersistence;
            
            $this->calls->enqueue(
                $calls[$this->call_index++]
            );

            $this->bridge = new Bridge($this->persistence, $this->application, $this->calls);
            $this->bridge->execute(true);

        }

        function testCallToFunctionWithNoParameters () {
            $this->assertEqual(
                "foobar",
                $this->bridge->calls->queue[0]->return
            );
        }

        function testCallToFunctionWithParameters () {
            $this->assertEqual(
                PHP_VERSION,
                $this->bridge->calls->queue[0]->return
            );
        }

        function testCallToStaticMethodWithNoParameters () {
            $this->assertEqual(
                "static foobar",
                $this->bridge->calls->queue[0]->return
            );
        }

        function testCallToStaticMethodWithParameters () {
            $this->assertEqual(
                PHP_VERSION,
                $this->bridge->calls->queue[0]->return
            );
        }

        function testCallToInstanceMethodWithNoParameters () {
            $this->assertEqual(
                "instance foobar",
                $this->bridge->calls->queue[0]->return
            );
        }

        function testCallToInstanceMethodWithParameters () {
            $this->assertEqual(
                PHP_VERSION,
                $this->bridge->calls->queue[0]->return
            );
        }


    }


?>
