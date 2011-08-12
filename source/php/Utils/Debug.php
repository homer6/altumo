<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
* (c) Juan Jaramillo <juan.jaramillo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/




namespace Altumo\Utils;


/**
* This class is a collection of static debugging functions.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class Debug {
    

    /**
    * Outputs a stack trace and exits, if $exit == true
    * 
    * @param boolean $exit
    */
    static public function trace( $exit = true ){
        
        echo "<pre>";
        $exception = new Exception();
        
        echo $exception->getTraceAsString();
        
        if( $exit ){
            exit();    
        }
        
    }
    
    
    /**
    * Dumps out a preformatted version of these variables and exits.
    * This is a polyvariadic function.
    * 
    * @param mixed $variables...
    */
    static public function dump(){
        
        $arguments = func_get_args();
       
        echo '<pre>';
        foreach( $arguments as $argument ){
            var_dump( $argument );
        }
        
        exit();
        
    }
    
    
    /**
    * Dumps out a preformatted version of these variables and a trace dump and exits.
    * This is a polyvariadic function.
    * 
    * @param mixed $variables...
    */
    static public function dumpTrace(){
        
        $arguments = func_get_args();
       
        echo '<pre>';
        foreach( $arguments as $argument ){
            var_dump( $arg );
        }
        self::trace( false );
        exit();
        
    }
    
    
    /**
    * Prints the types of the variables passed and exists.
    * Also prints the methods for that class.
    * 
    * @param mixed $variables...
    */
    static public function getType(){
        
        $arguments = func_get_args();
       
        echo '<pre>';
        foreach( $arguments as $argument ){
            
            $type = gettype($argument);
                        
            if( $type === 'object' ){
                
                $class_name = get_class( $argument );
                echo 'Type:' . $class_name . "\n";
                $methods = get_class_methods( $argument );
                sort($methods);
                foreach( $methods as $method ){
                    echo '   Method: ' . $class_name . '::' . $method . "()\n";
                }
                                
            }else{
                echo 'Type:' . $type . "\n";
            }
            
            echo "\n\n";

        }
        exit();
        
    }
    
    
}
