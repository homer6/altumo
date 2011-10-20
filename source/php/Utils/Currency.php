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




namespace Altumo\Utils;


/**
* Useds for formatting currency data
* 
* 
* @see http://www.symfony-project.org/plugins/sfDateTime2Plugin
* 
* @author Juan Jaramillo <juan.jaramillo@altumo.com>
*/
class Currency{


    /**
    * Converts a Dollar amount ( e.g. 12.95 ) to cents (e.g. 1295)
    * Note: $amount will be rounded to 2 decimal points before conversion.
    * 
    * 
    * @param float|string $amount       // dollar amount to convert. e.g. 12.95
    * 
    * @return int                       // cent amount. e.g. 1295
    */
    static function getDollarsAsCents( $amount ){

        if( !is_numeric($amount) ){
            throw new \Exception( '$amount must be numeric.' );
        }

        $amount = round( $amount, 2 );

        return $amount * 100;

    } 
    
    
    /**
    * Converts cents to dollars.
    * 
    * 
    * @param int $amount
    * 
    * @return float
    */
    static function getCentsAsDollars( $amount ){

        $amount = \Altumo\Validation\Numerics::assertInteger( $amount );
        
        return $amount / 100;

    }


}
