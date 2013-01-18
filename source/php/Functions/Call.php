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




namespace Altumo\Functions;


/**
* This class contains functions for getting more information and validating
* function calls.
* 
* @author Juan Jaramillo <juan.jaramillo@altumo.com>
*/
class Call{
    
    /**
    * Artificially makes a function either Protected or Private.
    * 
    * Ensures a function call came from its parent class or a child class (depending
    * on $assert_as_private).
    * 
    * 
    * @param array $backtrace           // the debug_bracktrace() array
    *                                   // expects a slice of the array containing
    *                                   // the callee first, then the caller.
    *       
    * @param bool $assert_as_private    // weather to assert as private or protected.
    * 
    * 
    * @throws Exception                 // if method is being called from a class that is not
    *                                   // function's parent or one of its children.
    * @return void
    */
    static protected function assertProtectedOrPrivate( $backtrace, $assert_as_private = false ){
        
        // ignore if not called from a function method.
            if( !is_array($backtrace) || count($backtrace) != 2 ){
                return;
            }
            
        // get the classes & functions involved
            $callee_class = &$backtrace[0]['class'];
            $caller_class = &$backtrace[1]['class'];        
            
            $callee_function = &$backtrace[0]['function'];
            $caller_function = &$backtrace[1]['function'];

        // if both are the same class, no problem
            if( $callee_class === $caller_class ){
                return;
            }
            
            
        // if protected and called from a child class, no problem
            if( !$assert_as_private && in_array( $callee_class, class_parents($caller_class) ) ){
               return;
            }
            
        $term = $assert_as_private ? 'private' : 'protected';
        
        throw new \Exception( "{$callee_class}::{$callee_function} cannot be called from {$caller_class}::{$caller_function}. It is \"{$term}\"." );
     
    }
    
    
    /**
    * Artificially makes a function Protected.
    * 
    * Ensures a function call came from its parent class or a child class.
    * 
    * This is particularly useful when extending from a class that you don't have
    * control over to make certain methods private. 
    * 
    * @param bool $assert_as_private  // weather to assert as private or protected.
    * 
    * @throws Exception // if method is being called from a class that is not
    *                   // function's parent or one of its children.
    * @return void
    */
    static public function assertProtected(){

        return self::assertProtectedOrPrivate( array_slice( debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 1, 2 ), false );
        
    }    
    
    
    /**
    * Artificially makes a function Private.
    * 
    * Ensures a function call came from its parent class
    * 
    * This is particularly useful when extending from a class that you don't have
    * control over to make certain methods private. 
    * 
    * @param bool $assert_as_private  // weather to assert as private or protected.
    * 
    * @throws Exception // if method is being called from a class that is not
    *                   // function's parent or one of its children.
    * @return void
    */
    static public function assertPrivate(){
        
        return self::assertProtectedOrPrivate( array_slice( debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 1, 2 ), true );
        
    }
    
}
