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

## How it works
    
The execution is syncronous, following this order:
    
* At front end: Build the calls
* At front end: Serialize and export the serialized data to driver
* At front end: Execute the back end
* At back end: Import serialized data and unserialize it
* At back end: Process the calls queue, invoking the calls
* At back end: Serialize and export the serialized data to driver
* At front end: Import serialized data, unserialize it
* At front end: Grab the results from every call and execute the respective callbacks, if any    
    
## Some considerations
    
* PHP-Ghetto-IPC is meant to be portable between all versions of PHP, so...
    * There are no visibility keywords in classes definition, as there is no implementation of visibility in PHP 4
    * There are no interfaces - I miss them, they would be very useful defining the drivers
    * A lot of good sense is needed to avoid interference in PHP-Ghetto-IPC behavior. Respect the API and you'll be fine
    
* Don't expect performance. PHP-Ghetto-IPC is not meant to be fast
    * In average, between the first data serializing and the end of the execution of callbacks,
    it took 0.01 second, running in a Core i7 (Ubuntu 11.04) and in a Phenom II X4 965 (Windows XP)
    - Also, shell_exec() performed much faster than proc_open()
    
* PHP-Ghetto-IPC is meant to be simple and to make the integration of PHP 4 applications with PHP 5 code simpler
    Personally, I think it's easier than build a local webservice or RPC, set up a new HTTP daemon, and so on

## Building a call
    
```php
$call = new Call($callee, $parameters = void, $constructor_parameters = void, $callback = void, $additional_callback_parameters = void);
```
_As NULL is a value that can be exchanged between the ends, doesn't make sense to use is_null. void is a constant meant to be the library's null value_
    
Where

* $callee - The static method, object method or function to be invoked

    When calling a function:

    ```php
    $call = new Call("function");
    ```

    ```php
    $call = new Call(array("function"));
    ```

    When calling an object method
    
    ```php
    $call = new Call(array("class", "method"));
    ```

    When calling a static method
    
    ```php
    $call = new Call(array("class", "::method"));
    ```        
    
    or
    
    ```php
    $call = new Call(array("class::method");
    ```
    
    easier:
    
    ```php
    $call = new Call("class::method");
    ```

    If the call is to an object method, GhettoIPC will instantiate the class and store it in a container named Instances.
    The container has a way to do "instance reusing", so, if the code needs to do two calls to the same object, and the second call
    has its class name prepended by "&", PHP-Ghetto-IPC will not instantiate another object to the second call if there's an object 
    of the same class instantiated when invoking the first call.

    Examples:

    ```php
    $call = new Call("foobar", "baz");
    $call = new Call("foobar", "baz");
    ```
    _Will instantiate two objects of class "foobar" and invoke the method "baz"_

    ```php
    $call = new Call("foobar", "baz");
    $call = new Call("&foobar", "baz");
    ```
    _Will instantiate one object of class "foobar" in first call and invoke the method "baz" two times_

    ```php
    $call = new Call("&foobar", "baz");
    ```
    _Will instantiate one object of class "foobar", but, even if marked to reuse an instance, there is no instance in the container, so, it will instantiate_        

* $parameters - Parameters to be passed to the function or class

* $constructor_parameters - Parameters to be passed to the class constructor
    
* $callback - Function or static method (PHP 5 only) to be called when the execution returns to front end
    
* $additional_callback_parameters - Parameters to be passed to callback in addition to the value returned by a call in back end

## Note about $parameters, $constructor_parameters and $additional_callback_parameters
        
Important: The point about these parameters is that every value passed to them
that is not an array will be the first element of an array to make easier to pass values to call_user_func_array().
If you must pass an array to any of these variables, you must first create another array with the first array as an element

For instance:
    
```php
$foo = array("bar");
$call = new Call("baz", $foo);
```

The idea is to call _foo(array("bar"))_, but instead of this, _foo("bar")_ will be called. To avoid this, the right way is:

```php
$foo = array("bar");
$call = new Call("baz", array($foo));
```

(to be continued)