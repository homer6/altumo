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
* Unit tests for the \Altumo\String\String class.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class StringTest extends \Altumo\Test\UnitTest{


    /**
    * Tests String::generateUrlSlug()
    * 
    */
    public function testGenerateUrlSlug(){
               
        $input = 'asdfsdaf-asdfsdf';
        $output = \Altumo\String\String::generateUrlSlug( $input );
        $this->assertTrue( $input === $output );
        
        $input = 'Happy to Meet you !';
        $output = \Altumo\String\String::generateUrlSlug( $input );
        $this->assertTrue( $output === 'happy-to-meet-you' );
        
        $input = '_How Are You_';
        $output = \Altumo\String\String::generateUrlSlug( $input );
        $this->assertTrue( $output === 'how-are-you' );
        
        $input = '_How Are       $(#@) $ $&#*($&# $(#@$&(*#@)))   You_';
        $output = \Altumo\String\String::generateUrlSlug( $input );
        $this->assertTrue( $output === 'how-are-you' );
               
    }


    /**
    * Tests String::getTruncatedText()
    * 
    */
    public function testGetTruncatedText(){
               
        $input = 'know thyself';
        $output = \Altumo\String\String::getTruncatedText( $input, 10 );
        $this->assertTrue( $output === 'know...' );
               
    }
    
   
    
}



