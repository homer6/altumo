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
* Unit tests for the \Altumo\Xml\XmlElement class.
* 
*/
class XmlElementTest extends \Altumo\Test\UnitTest{

    protected $empty_xml_element = null;
    protected $minimal_xml_element = null;

    
    /**
    * Set up for these tests
    * 
    */    
    public function setup(){
        
        $this->empty_xml_element = new \Altumo\Xml\XmlElement();
        $this->minimal_xml_element = new \Altumo\Xml\XmlElement( $this->getTestSuiteFileContents('minimal.xml') );
        
    }    

    
    /**
    * Tests basic parsing.
    * 
    */
    public function testXmlFileParsing(){
        
        $xml_file = new \Altumo\Xml\XmlElement();
        $xml_file->setContentsByString(
            '<?xml version="1.0" encoding="UTF-8" ?>' . 
            '<hello>World</hello>'
        );
        
        $this->assertTrue( $xml_file->xpath('.') === 'World' );
        
    }
    
    
    /**
    * Test that the object is not loaded when no parameters are passed to the constructor.
    * 
    */
    public function testIsNotLoadedOnEmptyContructor(  ){
        
        $xml_element = $this->empty_xml_element;
        
        $this->assertTrue( $xml_element->isLoaded() === false );
        
    }
    
    
    /**
    * Test that the object throws and exception when assertLoaded is called.
    * 
    */
    public function testAssertLoadedThrowsExceptionOnEmptyContructor(){
        
        $xml_element = $this->empty_xml_element;
        try{
            $xml_element->assertLoaded();
            $this->assertTrue( false );
        }catch( \Exception $e ){
            $this->assertTrue( true );
        }
        
    }
    
    
    /**
    * Test the getName method.
    * 
    */
    public function testMethodGetName(){
        
        $xml_element = $this->minimal_xml_element;
        
        $this->assertTrue( 'nutrition' === $xml_element->getName() );
        
    }
    
    
    /**
    * Test the queryWithXpath method.
    * 
    */
    public function testMethodQueryWithXpath(){
        
        $xml_element = $this->minimal_xml_element;
        $expected = array(
            "210",
            "510"
        );
        $this->assertTrue( $expected === $xml_element->queryWithXpath('food/sodium') );
        
    }
    
    
    /**
    * Tests the documention's sample code.
    * 
    */
    public function testDocumentationSampleCode(){
        
        $xml_element = $this->minimal_xml_element;
        $expected = array(
            "210",
            "510"
        );
        $this->assertTrue( $expected === $xml_element->queryWithXpath('food/sodium', \Altumo\Xml\XmlElement::RETURN_TYPE_STRING) );
        
    }

    
    /**
    * Gets the contents of a test suite file for this object as a string.
    * 
    * @param string $filename
    * @return string
    */
    protected function getTestSuiteFileContents( $filename ){
                
        return file_get_contents( __DIR__ . '/test_data/' . $filename );

    }
    
    
}



