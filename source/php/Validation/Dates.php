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
* This class contains functions for Date validation.
* These functions will return the sanitized data too.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class Dates{
        
    
    /**
    * Determines if this is a DateTime value (or can be interpreted as one)
    * 
    * @param mixed $value
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @return \Altumo\Utils\Date            //the value that it interpreted
    * @throw \Exception                     //if could not be interpreted as 
    *                                         a DateTime
    */
    static public function assertDateTime( $value, $exception_message = null ){
        
        if( is_null( $exception_message ) ){
            $exception_message = 'Could not interpret as valid DateTime.';
        }
        
        if( $value instanceof \Altumo\Utils\Date ){
            return $value;
        }
        
        //use the Date's constructor validation to see if it adheres to a 
        //known date format
        try{
            $date = new \Altumo\Utils\Date( $value );
            return $date;            
        }catch( \Exception $e ){
            throw new \Exception( $exception_message );
        }
                
    }
    
    
}