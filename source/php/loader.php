<?php
  
/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
  
  
/**
* This file is designed to be the one and only required include to use Altumo
* for php sources.
* 
* If you'd like to not use it because of the hard-coded timezone, you can 
* simply copy the autoloader lines into your own application.
* 
* There's no requirement to use this specific file to autoload the class paths.
* 
* Also, you can register your own namespaces by adding a second value to the
* registerNamespaces array argument. See the comments within the 
* UniversalClassLoader for addition sample usages.
*/
  
    //set default timezone
        date_default_timezone_set( 'America/Los_Angeles' );
  
    //symfony 2 autoloader (for classes within namespaces)
        require_once __DIR__ . '/Utils/UniversalClassLoader.php';
        $loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
        $loader->registerNamespaces(array(
            'Altumo' => __DIR__
        ));
        $loader->register();