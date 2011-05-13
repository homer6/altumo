<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/


namespace Altumo\Test;

/**
* Unit tests for the \Altumo\String\Url class.
* 
*/
class UrlTest extends \Altumo\Test\UnitTest{


    /**
    * Set up for these tests
    * 
    */    
    public function setup(){
        
        $this->empty_url = new \Altumo\String\Url();
        
    }    

    /**
    * Tests basic parsing.
    * 
    */
    public function testBasicUrlNoPath(){
               
        $url_string = 'http://www.google.com';
        $url = new \Altumo\String\Url($url_string);
        $this->assertTrue( $url->isValid(), $url_string );
                
        $this->assertTrue( $url->getScheme() === 'http' );
                
        $this->assertTrue( $url->getHost() === 'www.google.com' );
        
        $this->assertTrue( $url->hasPath() === false );
        $this->assertTrue( $url->hasLogin() === false );
        $this->assertTrue( $url->hasPort() === false );
        $this->assertTrue( $url->hasAnchor() === false );
        $this->assertTrue( $url->hasScheme() === true );
        $this->assertTrue( $url->hasHost() === true );
               
    }

    /**
    * Tests basic constructor exceptions.
    * 
    */
    public function testConstructorExceptions(){
                       
        try{
            //bad url: should throw exception
            $url = new \Altumo\String\Url('');
            $this->assertTrue( false );
        }catch( \Exception $e ){
            $this->assertTrue( true );
        }
                       
        try{
            //shouldn't throw
            $url = new \Altumo\String\Url();
            $this->assertTrue( true );
        }catch( \Exception $e ){
            $this->assertTrue( false );
        }
               
    }
    
    
    /**
    * Read each line in the test suite files: 
    *   test_data/url_valid.txt 
    *   test_data/url_invalid.txt 
    * 
    * Each line in the valid urls should not throw an exception.
    * Each line in the invalid urls should throw an exception.
    * 
    */
    public function testTestValidAndInvalidTestSuites(){
       
        $valid_urls = file_get_contents( __DIR__ . '/test_data/url_valid.txt' );
        $valid_urls = explode("\n", $valid_urls);
        
        foreach( $valid_urls as $valid_url ){
            try{
                //shouldn't throw
                $url = new \Altumo\String\Url($valid_url);
                $this->assertTrue( true );
            }catch( \Exception $e ){
                $this->assertTrue( false, $valid_url );
            }
        }


        $invalid_urls = file_get_contents( __DIR__ . '/test_data/url_invalid.txt' );
        $invalid_urls = explode("\n", $invalid_urls);
        
        foreach( $invalid_urls as $invalid_url ){
            try{
                //bad url: should throw exception
                $url = new \Altumo\String\Url($invalid_url);
                $this->assertTrue( false, $invalid_url );
            }catch( \Exception $e ){
                $this->assertTrue( true );
            }
        }
        
    }
    

    
}



