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




namespace Altumo\Test;


/**
* Unit tests for the \Altumo\Validation\Numerics class.
* 
* @author Juan Jaramillo <juan.jaramillo@altumo.com>
*/
class NumericsTest extends \Altumo\Test\UnitTest{

    
    /**
    * Tests Numerics::assertInteger()
    * 
    */
    public function testAssertInteger(){
               
        $input = '7215';
        $output = \Altumo\Validation\Numerics::assertInteger( $input );
        $this->assertTrue( is_integer($output) );               
        
        $input = 7215.0;
        $output = \Altumo\Validation\Numerics::assertInteger( $input );
        $this->assertTrue( is_integer($output) );               
        
        $input = 7215;
        $output = \Altumo\Validation\Numerics::assertInteger( $input );
        $this->assertTrue( is_integer($output) );               
        
        try{
            $input = 72.56;
            $output = \Altumo\Validation\Numerics::assertInteger( $input );
            $this->assertTrue( false ); 
        }catch( \Exception $e ){
            $this->assertTrue( true );               
        }
        
        $input = 0;
        $output = \Altumo\Validation\Numerics::assertInteger( $input );
        $this->assertTrue( is_integer($output) );
        
    }
   

    /**
    * Tests Numerics::assertPositiveInteger()
    * 
    */
    public function testAssertPossitiveInteger(){
               
        $input = '7215';
        $output = \Altumo\Validation\Numerics::assertPositiveInteger( $input );
        $this->assertTrue( is_integer($output) );               
        
        $input = 7215.0;
        $output = \Altumo\Validation\Numerics::assertPositiveInteger( $input );
        $this->assertTrue( is_integer($output) );                
        
        $input = (double)7215;
        $output = \Altumo\Validation\Numerics::assertPositiveInteger( $input );
        $this->assertTrue( is_integer($output) );          
        
        $input = (float)7215;
        $output = \Altumo\Validation\Numerics::assertPositiveInteger( $input );
        $this->assertTrue( is_integer($output) );               
        
        $input = 7215;
        $output = \Altumo\Validation\Numerics::assertPositiveInteger( $input );
        $this->assertTrue( is_integer($output) );               
        
        try{
            $input = 72.56;
            $output = \Altumo\Validation\Numerics::assertPositiveInteger( $input );
            $this->assertTrue( false ); 
        }catch( \Exception $e ){
            $this->assertTrue( true );               
        }  
        
        try{
            $input = 0;
            $output = \Altumo\Validation\Numerics::assertPositiveInteger( $input );
            $this->assertTrue( false ); 
        }catch( \Exception $e ){
            $this->assertTrue( true );               
        }
        
    }
   
    
}
