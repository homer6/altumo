<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Altumo\Validation;


/**
* This class contains functions for object validation.
* 
* @author Juan Jaramillo <juan.jaramillo@altumo.com>
*/
class Objects {

    /**
    * Throws an exception if $object's class does not exactly match $class_name.
    * This function does not account for inheritance. 
    * 
    * @param mixed $object
    * 
    * @param string $class_name     
    *   //name of the class to match. e.g. "User"
    * 
    * @param \Exception $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * 
    * @throws \Exception                //if $class_name does not match the class 
    *                                     name of $object, if $object is not an
    *                                     Object, or if $class_name is not a string.
    * 
    * @return void
    */
    static public function assertObjectClass( &$object, $class_name, $exception_message = null ){

        \Altumo\Validation\Strings::assertNonEmptyString( $class_name );

        //remove global namespace if included in $class_name
            if( $class_name[0] == '\\' ){
                $class_name = substr( $class_name, 1 );
            }
        
        
        if( is_null( $exception_message ) ){
            $exception_message = 'object of type "' . $class_name . '" expected, but "' . get_class( $object ) . '" given.';
        }

        if( !is_object($object) || ( get_class($object) != $class_name ) ){
            throw new \Exception( $exception_message );
        }
                
    }    
    
    
    /**
    * Throws an exception if $object is not an instance of $class_name. Inheritance
    * will be accounted for, 
    * 
    * @param mixed $object
    * 
    * @param string $class_name     
    *   //name of the class to match. e.g. "User"
    * 
    * @param \Exception $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * 
    * @throws \Exception                // - if $object is not an instance of $class_name and is not null.
    *                                   // - if $class_name is not a string
    * 
    * @return void
    */
    static public function assertObjectInstanceOfClass( &$object, $class_name, $exception_message = null ){

        \Altumo\Validation\Strings::assertNonEmptyString( $class_name );

        //remove global namespace if included in $class_name
            if( $class_name[0] == '\\' ){
                $class_name = substr( $class_name, 1 );
            }
        
        if( is_null( $exception_message ) ){
            $exception_message = 'object of type "' . $class_name . '" expected, but "' . get_class( $object ) . '" given.';
        }

        if( !is_object($object) || !is_a($object, $class_name) ){
            throw new \Exception( $exception_message );
        }
                
    }    
    
    /**
    * Throws an exception if $object is not null and not an instance of $class_name. 
    * @see assertObjectInstanceOfClass
    * 
    * @param mixed $object
    * 
    * @param string $class_name     
    *   //name of the class to match. e.g. "User"
    * 
    * @param \Exception $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * 
    * @throws \Exception                //if $class_name does not match the class 
    *                                     name of $object, if $object is not an
    *                                     Object, or if $class_name is not a string.
    * 
    * @return void
    */
    static public function assertObjectInstanceOfClassOrNull( &$object, $class_name, $exception_message = null ){
        
        if( is_null($object) ){
            
            return;
            
        } else {
            
            return self::assertObjectInstanceOfClass( $object, $class_name, $exception_message );
            
        }
       
    }

}