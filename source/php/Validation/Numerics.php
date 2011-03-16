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
    * @throws Exception if argument passed is not an integer or can't be cast as an integer.
    * @return integer
    */
    static public function assertUnsignedInteger( $integer ){
        
        $integer = self::assertInteger( $integer );
        if( $integer < 0 ){
            throw new \Exception( 'Integer cannot be negative.' );
        }
        return $integer;
        
    }
    
    
    
    
    /**
    * Throws an exception if this is not a positive integer.
    * If this is a string, recasts as integer and returns it.
    * 
    * @param mixed $integer
    * @throws Exception if argument passed is not an integer or can't be cast as an integer.
    * @return integer
    */
    static public function assertPositiveInteger( $integer ){
        
        $integer = self::assertInteger( $integer );
        if( $integer <= 0 ){
            throw new \Exception( 'Integer cannot be negative or zero.' );
        }
        return $integer;
        
    }
    
    
    
    /**
    * Throws an exception if this is not an integer.
    * If this is a string, recasts as integer and returns it.
    * 
    * @param mixed $integer
    * @throws Exception if argument passed is not an integer or can't be cast as an integer.
    * @return integer
    */
    static public function assertInteger( $integer ){
        
        if( is_integer($integer) ){
            return $integer;
        }
        if( !is_numeric($integer) || floor($integer) != $integer ){
            throw new \Exception('Value passed is not an integer.');
        }
        return (integer)$integer;
        
    }
    
    /**
    * Determines if this is an integer (or castable as one).
    * 
    * @param mixed $integer
    * @return boolean
    */
    static public function isInteger( $integer ){
        
        try{
            self::assertInteger($integer);
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
    * @throws Exception if argument passed is not a double or can't be cast as a double.
    * @return double
    */
    static public function assertUnsignedDouble( $double ){
        
        $double = self::assertDouble( $double );
        if( $double < 0.0 ){
            throw new \Exception( 'Double cannot be negative.' );
        }
        return $double;
        
    }
    
    
    
    
    /**
    * Throws an exception if this is not a positive double.
    * If this is a string, recasts as double and returns it.
    * 
    * @param mixed $double
    * @throws Exception if argument passed is not a double or can't be cast as a double.
    * @return double
    */
    static public function assertPositiveDouble( $double ){
        
        $double = self::assertDouble( $double );
        if( $double <= 0.0 ){
            throw new \Exception( 'Double cannot be negative or zero.' );
        }
        return $double;
        
    }
    
    
    
    /**
    * Throws an exception if this is not an integer.
    * If this is a string, recasts as double and returns it.
    * 
    * @param mixed $double
    * @throws Exception if argument passed is not a double or can't be cast as a double.
    * @return double
    */
    static public function assertDouble( $double ){
        
        if( is_double($double) ){
            return $double;
        }
        if( !is_numeric($double) ){
            throw new \Exception('Value passed is not a double.');
        }
        return (double)$double;
        
    }
    
    
    
}
