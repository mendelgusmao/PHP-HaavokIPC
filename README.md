# PHP-Ghetto-IPC

Library to integrate legacy applications built under PHP 4 with PHP 5 applications and libraries.

## Really, really basic usage

This is the front end, i.e., a PHP 4 application:

```php
# frontend.php
<?php

    include "PHP-Ghetto-IPC/GhettoIPC.class.php";

    // CallsQueue is a container of calls
    $calls = new CallsQueue;

    // Create a call and enqueue it
    // It will call the function "hello" with the parameter "world", no constructor parameters in back end
    // and when the back end process is finished, execute the function "callback" in front end
    $calls->enqueue(
        new Call("hello", "world", null, "callback")
    );

    // Instantiate GhettoIPC, the core class, with a driver that uses
    // a file to store the serialized information to be used by back end
    // "backend.php"
    $ipc = new GhettoIPC(new FileDriver, "backend.php", $calls);

    // Method responsible for serializing data, exporting, executing back end,
    // re-importing data and executing callbacks.
    $ipc->execute(true);

    function callback ($string) {
        echo "Back end said: ", $string;
    }
```

This is the back end, i.e., a PHP 5 application

```php
# backend.php
<?php

    // Instantiate GhettoIPC in back end.
    // Don't worry about configuring anything in this side
    // Everything it needs has already been passed via command line
    $ipc = new GhettoIPC();
    $ipc->execute();

    function hello ($who) {
        return "Hello {$who}!";
    }
```

## Some considerations
    
* PHP-Ghetto-IPC is meant to be portable between all versions of PHP, so...
    * There are no visibility keywords in classes definition, as the implementation of visibility in PHP 4
    * There are no interfaces - I miss them, they would be very useful defining the drivers
    * A lot of good sense is needed to avoid interference in PHP-Ghetto-IPC behavior. Respect the API and you'll be fine
    
* The execution is syncronous, following this order:
    * At front end: Build the calls
    * At front end: Serialize and export the serialized data to driver
    * At front end: Execute the back end
    * At back end: Import serialized data and unserialize it
    * At back end: Process the calls queue, invoking the calls
    * At back end: Serialize and export the serialized data to driver
    * At front end: Import serialized data, unserialize it
    * At front end: Grab the results from every call and execute the respective callbacks, if any
    
* Don't expect performance. PHP-Ghetto-IPC is not meant to be fast
    * In average, between the first data serializing and the end of the execution of callbacks,
    it took 0.01 second, running in a Core i7 (Ubuntu 11.04) and in a Phenom II X4 965 (Windows XP)
    - Also, shell_exec() performed much faster than proc_open()
    
* PHP-Ghetto-IPC is meant to be simple and to make the integration of PHP 4 applications with PHP 5 code simpler.
    Personally, I think it's easier than build a local webservice or RPC, set up a new HTTP daemon, and so on
    