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
* This class contains functions for array validation.
* These functions will return the sanitized data too.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class Arrays{
    
    
    /**
    * Ensures that the input is an array or a CSV string representing an array.
    * If it's a CSV string, it converts it into an array with the elements split 
    * at the comma delimeter.  This method removes empty values.  
    * 
    * Eg.
    *     sanitizeCsvArray( '1,2,,,,3' );   //returns array( 1, 2, 3 );
    *     sanitizeCsvArray( array( 1, 2, 3 ) );   //returns array( 1, 2, 3 );
    *     sanitizeCsvArray( null );   //returns array();
    *     sanitizeCsvArray( "" );   //returns array();
    * 
    * @param mixed $input
    * @throws Exception //if $input is not null, a string or an array
    * @return array
    */
    static public function sanitizeCsvArray( $input ){
        
        if( is_null($input) ){
            return array();
        }
        
        if( is_string($input) ){
            $array = explode( ',', $input );
        }else{
            $array = $input;
        }
        
        if( !is_array($array) ){
            throw new \Exception('$input must be an array, string or null.');
        }

        return self::removeEmptyElements($array);
        
    }
    
    
    /**
    * Ensures that the input is an array or a CSV string representing an array.
    * If it's a CSV string, it converts it into an array with the elements split 
    * at the comma delimeter.  This method removes empty values. 
    * 
    * Each value must be a postitive integer.  Throws and exception if they aren't
    * (doesn't throw on empty value, just removes it).  This method will santize
    * the values; so, if they're a string "2", they'll be converted to int 2.
    * 
    * 
    * Eg.
    *     sanitizeCsvArrayPostitiveInteger( '1,2,,,,3' );   //returns array( 1, 2, 3 );
    *     sanitizeCsvArrayPostitiveInteger( array( 1, 2, 3 ) );   //returns array( 1, 2, 3 );
    *     sanitizeCsvArrayPostitiveInteger( array( 1, "hello", 3 ) );   //throws Exception
    *     sanitizeCsvArrayPostitiveInteger( '1,2,,"hello",,3' );   //throws Exception
    * 
    * @param mixed $input
    * @throws Exception //if $input is not null, a string or an array
    * @throws Exception //if $input contains elements that are not integers (or castable as integers)
    * @return array
    */
    static public function sanitizeCsvArrayPostitiveInteger( $input ){
        
        $array = self::sanitizeCsvArray($input);
        foreach( $array as $key => $value ){
            $array[$key] = \Altumo\Validation\Numerics::assertPositiveInteger($value);            
        }
        
        return $array;
        
    }

    
    /**
    * Removes all of the elements from the provided array that are empty strings or null.
    * Does not remove zeros.  Returns empty array if $array is not an array.
    * 
    * @param array $array
    * 
    * @return array
    */
    static public function removeEmptyElements( $array ){
        
        if( !is_array($array) ){
            return array();
        }
        foreach( $array as $key => $value ){
            if( is_null($value) || $value === "" ){
                unset($array[$key]);
            }            
        }
        return $array;
        
    }
    
    
}
