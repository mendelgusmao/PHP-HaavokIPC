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
    // Don't worry about configuring anythin in this side
    // Everything it needs has already been passed via command line
    $ipc = new GhettoIPC();
    $ipc->execute();

    function hello ($who) {
        return "Hello {$who}!";
    }
```
