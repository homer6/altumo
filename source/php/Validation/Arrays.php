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
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception //if $input is not null, a string or an array
    * @return array
    */
    static public function sanitizeCsvArray( $input, $exception_message = null ){
        
        if( is_null( $exception_message ) ){
            $exception_message = '$input must be an array, string or null.';
        }
        
        if( is_null($input) ){
            return array();
        }
        
        if( is_string($input) ){
            $array = explode( ',', $input );
        }else{
            $array = $input;
        }
        
        if( !is_array($array) ){
            throw new \Exception( $exception_message );
        }

        return self::removeEmptyElements( $array );
        
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
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception //if $input is not null, a string or an array
    * @throws Exception //if $input contains elements that are not integers (or castable as integers)
    * @return array
    */
    static public function sanitizeCsvArrayPostitiveInteger( $input, $exception_message = null ){
                    
        //accept a single integer
            try{
                $number = \Altumo\Validation\Numerics::assertPositiveInteger( $input, $exception_message ); 
                return array( $number );
            }catch( \Exception $e ){}

        //or a collection of them
            $array = self::sanitizeCsvArray($input);
                foreach( $array as $key => $value ){
                $array[$key] = \Altumo\Validation\Numerics::assertPositiveInteger( $value, $exception_message );            
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
    
    
    /**
     * Returns an array or null input if input is an array or null,
     * throws an exception if otherwise
     *  
     * @param mixed $input
     * @param string $message Optional message for the exception thrown
     *  if assertion fails
     *  
     * @throws \Exception if $input is not an array or null
     *  
     * @return array|null
     */
    public static function assertArrayOrNull( $input, $message=null ) {
    	
    	if ( is_null($input) ) return $input;
    	
    	return self::assertArray( $input, $message );
    }
    

    /**
     * Returns an array if input is an array, throws an
     * exception if otherwise.
     *
     * @param mixed $input
     * @param string $message Optional message for the exception thrown
     *  if assertion fails
     *
     * @throws \Exception if $input is not an array or null
     *
     * @return array
     */
    public static function assertArray( $input, $message=null ) {
    	
    	if ( ! is_array( $input ) ) {
    		
    		$message = ! is_null($message) ? $message : "Value passed is not an array";
    		
    		throw new \Exception( $message );
    	}

	return $input;
    	
    }
    
}

