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
* Unit tests for the \Altumo\Validation\Emails class.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class EmailsTest extends \Altumo\Test\UnitTest{

    
    
    /**
    * Read each line in the test suite files: 
    *   test_data/email_valid.txt 
    *   test_data/email_invalid.txt 
    * 
    * Each line in the valid urls should not throw an exception.
    * Each line in the invalid urls should throw an exception.
    * 
    * @thanks to http://blogs.msdn.com/b/testing123/archive/2009/02/05/email-address-test-cases.aspx
    * 
    */
    public function testTestValidAndInvalidTestSuites(){
       
        $valid_emails = file_get_contents( __DIR__ . '/test_data/email_valid.txt' );
        $valid_emails = explode("\n", $valid_emails);
        
        foreach( $valid_emails as $valid_email ){
            try{
                //good email: shouldn't throw
                $url = \Altumo\Validation\Emails::assertEmailAddress( $valid_email );
                $this->assertTrue( true );
            }catch( \Exception $e ){
                $this->assertTrue( false, $valid_email );
            }
        }


        $invalid_emails = file_get_contents( __DIR__ . '/test_data/email_invalid.txt' );
        $invalid_emails = explode("\n", $invalid_emails);
        
        foreach( $invalid_emails as $invalid_email ){
            try{
                //bad email: should throw exception
                $url = \Altumo\Validation\Emails::assertEmailAddress( $invalid_email );
                $this->assertTrue( false, $invalid_email );
            }catch( \Exception $e ){
                $this->assertTrue( true );
            }
        }
        
    }
    
    
}
