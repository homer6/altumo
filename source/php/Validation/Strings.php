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
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception //if argument passed is not a non-empty string or can't 
    *                     be cast as a string
    * @return string
    */
    static public function assertNonEmptyString( $string, $exception_message = null ){
        
        if( is_null($exception_message) ){
            $exception_message = 'Value passed is not a non-empty string.';
        }
        
        if( is_string($string) && !empty($string) ){
            return $string;
        }else{
            throw new \Exception( $exception_message );
        }
                
    }
    
    
    /**
    * Throws an exception if this is not a string (or castable as one).
    * Returns the string if it was cast as one.
    * 
    * @param mixed $string
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception //if argument passed is not a string
    * @return string
    */
    static public function assertString( $string, $exception_message = null ){
        
        if( is_null($exception_message) ){
            $exception_message = 'Value passed is not a string.';
        }
        
        if( !is_string($string) ){
            throw new \Exception( $exception_message );
        }else{
            return $string;
        }
                
    }
    
    
    /**
    * Throws an exception if this is not a string (or castable as one) or is not
    * with the string length range provided.
    * Returns the string if it was cast as one.
    * 
    * @param mixed $string
    * @param integer $min_length
    * @param integer $max_length
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception //if argument passed is not a string
    * @throws Exception //if the string length was not within the provided range
    * @return string
    */
    static public function assertStringAndLength( $string, $min_length = null, $max_length = null, $exception_message = null ){
        
        if( !is_string($string) ){
            if( is_null($exception_message) ){
                $exception_message = 'Value passed is not a string.';
            }
            throw new \Exception( $exception_message );
        }
        
        $length = strlen( $string );
        
        if( !is_null($min_length) ){
            $min_length = \Altumo\Validation\Numerics::assertUnsignedInteger( $min_length );
            if( $length < $min_length ){
                if( is_null($exception_message) ){
                    $exception_message = 'String length was less than the minimum allowed length ( ' . $min_length . ' ).';
                }
                throw new \Exception( $exception_message );
            }
        }
        
        if( !is_null($max_length) ){
            $max_length = \Altumo\Validation\Numerics::assertUnsignedInteger( $max_length );
            if( $length > $max_length ){
                if( is_null($exception_message) ){
                    $exception_message = 'String length was greater than the maximum allowed length ( ' . $max_length . ' ).';
                }
                throw new \Exception( $exception_message );
            }
        }
        
        return $string;
                
    }

    
    /**
    * Throws an exception if this is not a string (or castable as one).
    * Also throws an exception if this string contains characters other than
    * alpha-numeric, hyphens or underscores.
    * 
    * @param mixed $string
    * @param string $exception_message  
    *   //custom Exception message to throw. If null, default message will be 
    *     used.
    * 
    * @throws Exception 
    *   //if argument passed is not a string
    * 
    * @throws Exception 
    *   //if argument passed contains characters other than alpha-numeric, 
    *     hyphens or underscores.
    * 
    * @return string
    */
    static public function assertStringAlphaNumericHyphenUnderscore( $string, $exception_message = null ){
        
        self::assertString( $string, $exception_message );
       
        if( preg_match('/[-_a-zA-Z0-9]/', $string) ){
            return $string;
        }else{
            if( is_null($exception_message) ){
                $exception_message = 'String contained characters other than alpha-numeric, hyphens or underscores.';
            }
            throw new \Exception( $exception_message );
        }
        
    }
    
    
}
