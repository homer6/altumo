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
* This class contains functions for number validation.
* These functions will return the sanitized data too.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class Numerics{
    

    /**
    * Throws an exception if this is not an unsigned integer.
    * If this is a string, recasts as integer and returns it.
    * 
    * @param mixed $integer
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception if argument passed is not an integer or can't be cast as an integer.
    * @return integer
    */
    static public function assertUnsignedInteger( $integer, $exception_message = null ){
        
        if( is_null( $exception_message ) ){
            $exception_message = 'Integer cannot be negative.';
        }
        
        $integer = self::assertInteger( $integer, $exception_message );
        if( $integer < 0 ){
            throw new \Exception( $exception_message );
        }
        return $integer;
        
    }
    
    
    /**
    * Throws an exception if this is not a positive integer.
    * If this is a string, recasts as integer and returns it.
    * 
    * @param mixed $integer
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception if argument passed is not an integer or can't be cast as an integer.
    * @return integer
    */
    static public function assertPositiveInteger( $integer, $exception_message = null ){
        
        if( is_null( $exception_message ) ){
            $exception_message = 'Integer cannot be negative or zero.';
        }
        
        $integer = self::assertInteger( $integer, $exception_message );
        if( $integer <= 0 ){
            throw new \Exception( $exception_message );
        }
        return $integer;
        
    }

    
    /**
    * Throws an exception if this is not an integer.
    * If this is a string, recasts as integer and returns it.
    * 
    * @param mixed $integer
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception if argument passed is not an integer or can't be cast as an integer.
    * @return integer
    */
    static public function assertInteger( $integer, $exception_message = null ){
        
        if( is_null( $exception_message ) ){
            $exception_message = 'Value passed is not an integer.';
        }
        
        if( is_integer($integer) ){
            return $integer;
        }
        if( !is_numeric($integer) || floor($integer) != $integer ){
            throw new \Exception( $exception_message );
        }
        return (integer)$integer;
        
    }
    
    
    /**
    * Determines if this is an integer (or castable as one).
    * 
    * @param mixed $integer
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @return boolean
    */
    static public function isInteger( $integer, $exception_message = null ){
        
        try{
            self::assertInteger( $integer, $exception_message );
            return true;
        }catch( \Exception $e ){
            return false;
        }        
        
    }

    
    /**
    * Throws an exception if this is not an unsigned double.
    * If this is a string, recasts as double and returns it.
    * 
    * @param mixed $double
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception if argument passed is not a double or can't be cast as a double.
    * @return double
    */
    static public function assertUnsignedDouble( $double, $exception_message = null ){
        
        if( is_null( $exception_message ) ){
            $exception_message = 'Double cannot be negative.';
        }
        
        $double = self::assertDouble( $double, $exception_message );
        if( $double < 0.0 ){
            throw new \Exception( $exception_message );
        }
        return $double;
        
    }
    
    
    /**
    * Throws an exception if this is not a positive double.
    * If this is a string, recasts as double and returns it.
    * 
    * @param mixed $double
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception if argument passed is not a double or can't be cast as a double.
    * @return double
    */
    static public function assertPositiveDouble( $double, $exception_message = null ){
        
        if( is_null( $exception_message ) ){
            $exception_message = 'Double cannot be negative or zero.';
        }
        
        $double = self::assertDouble( $double, $exception_message );
        if( $double <= 0.0 ){
            throw new \Exception( $exception_message );
        }
        return $double;
        
    }
    
    
    /**
    * Throws an exception if this is not an integer.
    * If this is a string, recasts as double and returns it.
    * 
    * @param mixed $double
    * @param string $exception_message  
    *   //custom Exception message to throw. 
    *     If null, default message will be used.
    * 
    * @throws Exception if argument passed is not a double or can't be cast as a double.
    * @return double
    */
    static public function assertDouble( $double, $exception_message = null ){
        
        if( is_null( $exception_message ) ){
            $exception_message = 'Value passed is not a double.';
        }
        
        if( is_double($double) ){
            return $double;
        }
        if( !is_numeric($double) ){
            throw new \Exception( $exception_message );
        }
        return (double)$double;
        
    }


}