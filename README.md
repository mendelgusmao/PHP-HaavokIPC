# PHP-Ghetto-IPC / GhettoIPC

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
    
* GhettoIPC is meant to be portable between the majority of versions of PHP after 4, so...
    * No visibility keywords in classes definition, as there is no implementation of visibility in PHP 4
    * No interfaces - I miss them, they would be very useful defining the drivers
    * No type hinting
    * No Reflection classes
    * A lot of good sense is needed to avoid interference in GhettoIPC behavior. Respect the API and you'll be fine
    
* Don't expect performance. GhettoIPC is not meant to be fast
    * In average, between the first data serializing and the end of the execution of callbacks, it took 0.01 second, running in a Core i7 (Ubuntu 11.04) and in a Phenom II X4 965 (Windows XP)
    - Also, shell_exec() performed much faster than proc_open()
    
* GhettoIPC is meant to be simple and to make the integration of PHP 4 applications with PHP 5 code simpler. Personally, I think it's easier than build a local webservice or RPC, set up a new HTTP daemon, and so on

## Basic configuration

All configuration is done with constants defined in Configuration.php
    
The most important are:

* GIPC_BACKEND_BIN
    
The path of PHP binary that will run as back end

* GIPC_LOGFILE
    
The path of the file that will store log information

* GIPC_LOG
    
Enable or disable logging

* GIPC_PREPEND_IPC_CLASS
    
Enable or disable the prepending of GhettoIPC.class.php in back end, avoiding the need to include the file
    
_It uses the parameter "-d auto_prepend_file" or "define auto_prepend_file INI entry", so it will overwrite INI configuration_

* GIPC_FORCE_NO_OUTPUT
    
Enable or disable output buffering in back end from the instantiation of GhettoIPC
    
## Setting the driver

For now 3 drivers are avaliable:

* FileDriver
    
It's the first driver created for GhettoIPC and uses a temporary file for each execution. You can configure it in Configuration.php using these constants:

```php
define("GIPC_EXT", ".persistence"); // Filename extension
define("GIPC_TMP", "/tmp/");        // Temporary directory
```   
    
* MemcacheDriver

This driver uses memcache and doesn't serializes data before exporting. You can configure it using these constants:

```php
define("GIPC_MEMCACHED", "127.0.0.1"); // Address of the memcache server
define("GIPC_MEMCACHEDP", 11211);      // Port of the memcache server
```

* ShmDriver

This driver uses shared memory and doesn't serializes data before exporting. It's more appropriated for *nix environments and won't work in Windows.
You can configure it using these constants:

```php
define("GIPC_SHM_SIZE", 32768); // Initial shared memory segment size
define("GIPC_SHM_PERMS", 0666); // Permissions for the shared memory segment
```

* StdIODriver
This driver will use stdio to exchange data between front end and back end. Its development is frozen as it requires architecture changes in GhettoIPC core.

_There is no need to define a driver in back end. The information about what driver was configured in front end is passed via command line to back end_

## Building a call
    
```php
$call = new (Static|Object)?Call($callee, $parameters = void, $callback = void, $additional_callback_parameters = void);
```
_As NULL is a value that can be exchanged between the ends, then it makes no sense to use is_null in certain situations.
void is a constant meant to be the "library's null value"._
    
Where

* $callee - The static method, object method or function to be invoked

    When calling a function:

    ```php
    $call = new Call("function");
    // or
    $call = new Call(array("function"));
    ```

    When calling an object method
    
    ```php
    $call = new ObjectCall(array("class", "method"));
    ```

    Instantiating a class with constructor parameters before calling the desired method
    
    ```php
    $call = new ObjectCall(array("class", "method", "constructor parameter"));
    // or
    $call = new ObjectCall(array("class", "method", array("constructor parameter 1", "constructor parameter 2")));
    ```
    
    When calling a static method
    
    ```php
    $call = new StaticCall(array("class", "method"));
    // or
    $call = new StaticCall(array("class::method"));
    // easier!
    $call = new StaticCall("class::method");
    ```

    If the call is to an object method, GhettoIPC will instantiate the class and store it in a container named Instances.
    The container has a way to do "instance reusing", so, if the code needs to do two calls to the same object, and the second call
    has its class name prepended by "&", GhettoIPC will not instantiate another object to the second call if there's an object 
    of the same class instantiated when invoking the first call.

    Examples:

    ```php
    $call = new ObjectCall(array("foobar", "baz"));
    $call = new ObjectCall(array("foobar", "baz"));
    ```
    _Will instantiate two objects of class "foobar" and invoke the method "baz"_

    ```php
    $call = new ObjectCall(array("foobar", "baz"));
    $call = new ObjectCall(array("&foobar", "baz"));
    ```
    _Will instantiate one object of class "foobar" in first call and invoke the method "baz" two times_

    ```php
    $call = new ObjectCall(array("&foobar", "baz"));
    ```
    _Will instantiate one object of class "foobar", but, even if marked to reuse an instance, there is no instance in the container, so, it will instantiate_        

* $parameters - Parameters to be passed to the function or class
    
* $callback - Function or static method (PHP 5 only) to be called when the execution returns to front end
    
* $additional_callback_parameters - Parameters to be passed to callback in addition to the value returned by a call in back end

## Note about $parameters, $constructor_parameters and $additional_callback_parameters
        
Important: The point about these parameters is that every value passed to them that is not an array will be the first element of an array to make easier to pass values to call_user_func_array(). 
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

## Sending extra data to back end

Before execute() in front end:

```php
$ipc->set_export_options($option, $value);
```

Where $option can be:

* GIPC_EXPORT_GLOBALS - Export $GLOBALS
* GIPC_EXPORT_REQUEST - Export $_REQUEST
* GIPC_EXPORT_POST - Export $_POST
* GIPC_EXPORT_GET - Export $_GET
* GIPC_EXPORT_SERVER - Export $_SERVER
* GIPC_EXPORT_COOKIE - Export $_COOKIE
* GIPC_EXPORT_SESSION - Export $_SESSION
* GIPC_EXPORT_CONSTANTS - Export defined constants
* GIPC_EXPORT_HEADERS - Export generated headers 
* GIPC_EXPORT_ENV - Export $_ENV
* GIPC_EXPORT_FILES - Export $_FILES
* GIPC_EXPORT_DEBUG - Export debug_backtrace() result
* GIPC_EXPORT_OUTPUT - Export stdout
* GIPC_EXPORT_FORCE_NO_OUTPUT - Force back end to use output buffering

And $value can be:

* GIPC_EXPORT_WAY_F2B - Export from front end to back end
* GIPC_EXPORT_WAY_B2F - Export from back end to front end
* GIPC_EXPORT_WAY_BOTH - Export from front end to back end, export from back end to front end

Exceptions: For GIPC_EXPORT_HEADERS, GIPC_EXPORT_DEBUG, GIPC_EXPORT_FORCE_NO_OUTPUT, and GIPC_EXPORT_OUTPUT the value is boolean