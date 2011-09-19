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
* This class contains functions for validating email addresses
* Some functions return the sanitized value as well.
* 
* @author Juan Jaramillo <juan.jaramillo@altumo.com>
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class Emails{
    
    /**
    * Throws an exception if this is not a valid email address.
    * These functions will return the sanitized data too.
    * 
    * 
    * @param mixed $email_address
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception //if argument passed is not a non-empty string or can't 
    *                     be cast as a string
    * @return string
    */
    static public function assertEmailAddress( $email_address, $exception_message = null ){
        
        if( is_null($exception_message) ){
            $exception_message = 'Value passed is not a valid email address.';
        }
        
        if( preg_match('/([A-Z0-9._%+-]+@[A-Z0-9.-]+\\.[A-Z]{2,4})/i', $email_address, $matches) ){
            //sanitize by using the first capturing group
                $email_address = $matches[1];
        } else {
            throw new \Exception( $exception_message );
        }
        
        return $email_address;
                
    }

}

