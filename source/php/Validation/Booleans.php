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
* This class contains functions for boolean validation.
* These functions will return the sanitized data too.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class Booleans{
        
    
    /**
    * Determines if this is a boolean value (or can be interpreted as one)
    * 
    * @param mixed $value
    * 
    * @return boolean                       //the value that it interpreted
    * @throw \Exception                     //if could not be interpreted as 
    *                                         boolean
    */
    static public function assertLooseBoolean( $value ){
        
        if( is_bool($value) ){
            return $value;
        }
                
        if( is_integer($value) ){
            
            if( $value >= 1 ){
                return true;
            }        
            if( $value <= 0 ){
                return false;
            }
            
        }
        
        if( is_string($value) ){
            
            $value = strtolower($value);            
            
            switch( $value ){
                
                case 'true':
                case 'on':
                case 'yes':
                case '1':
                case 'enable':
                case 'enabled':
                case 'active':

                    return true;                    
                
                case 'false':
                case 'off':
                case 'no':
                case '0':
                case 'disabled':
                case 'disable':
                case 'deactive':
                
                    return false;
                
            }
            
        }
        
        throw new \Exception( 'Could not interpret as boolean.' );

    }
    
    
}
