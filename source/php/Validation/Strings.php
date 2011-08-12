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




namespace Altumo\Validation;


/**
* This class contains functions for string validation.
* These functions will return the sanitized data too.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class Strings{
    
    
    /**
    * Throws an exception if this is not a non-empty string.
    * If this is not a string, recasts it as one, if possible.
    * 
    * @param mixed $string
    * @throws Exception //if argument passed is not a non-empty string or can't 
    *                     be cast as an integer.
    * @return string
    */
    static public function assertNonEmptyString( $string ){
        
        if( is_string($string) && !empty($string) ){
            return $string;
        }else{
            throw new \Exception('Value passed is not a non-empty string.');
        }
                
    }
    
    
    /**
    * Throws an exception if this is not a string (or castable as one).
    * Returns the string if it was cast as one.
    * 
    * @param mixed $string
    * @throws Exception //if argument passed is not a string
    * @return string
    */
    static public function assertString( $string ){
        
        if( !is_string($string) ){
            throw new \Exception('Value passed is not a string.');
        }else{
            return $string;
        }
                
    }

    
}
