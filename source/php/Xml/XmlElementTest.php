<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/


require_once( getenv('ALTUMO_TEST_ROOT') . '/loader.php' );

/**
* Unit tests for the \Altumo\Xml\XmlElement class.
* 
*/
class XmlElementTest extends PHPUnit_Framework_TestCase{
    

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
    * Provides an empty object (no arguments to contructor).
    * 
    * @return array
    */
    public function dataProviderEmptyObject(){
        
        return array( 
                    array( new \Altumo\Xml\XmlElement() )                   
        );
        
    }
    
    /**
    * Test that the object is not loaded when no parameters are passed to the constructor.
    * 
    * @dataProvider dataProviderEmptyObject
    * @param \Altumo\Xml\XmlElement $xml_element
    */
    public function testIsNotLoadedOnEmptyContructor( $xml_element ){
       
        $this->assertFalse( $xml_element->isLoaded() );
        
    }
    
    /**
    * Test that the object throws and exception when assertLoaded is called.
    * 
    * @dataProvider dataProviderEmptyObject
    * @expectedException \Exception
    * @param \Altumo\Xml\XmlElement $xml_element
    */
    public function testAssertLoadedThrowsExceptionOnEmptyContructor( $xml_element ){
        
        $xml_element->assertLoaded();
        
    }
    
    
    /**
    * Provides a minimally-size xml file for testing query elements.
    * 
    * @array
    */
    public function dataProviderMinimalElements(){
        
        
        return array( 
                    array( new \Altumo\Xml\XmlElement( $this->getTestSuiteFileContents('minimal.xml') ) )                   
        );
        
    }
    
    
    
    /**
    * Test the getName method.
    * 
    * @dataProvider dataProviderMinimalElements
    * @param \Altumo\Xml\XmlElement $xml_element
    */
    public function testMethodGetName( $xml_element ){
        
        $this->assertEquals( 'nutrition', $xml_element->getName() );
        
    }
    
    
    /**
    * Test the queryWithXpath method.
    * 
    * @dataProvider dataProviderMinimalElements
    * @param \Altumo\Xml\XmlElement $xml_element
    */
    public function testMethodQueryWithXpath( $xml_element ){
        
        $expected = array(
            "210",
            "510"
        );        
        $this->assertEquals( $expected, $xml_element->queryWithXpath('food/sodium') );
        
    }
    
    
    /**
    * Tests the documention's sample code.
    * 
    */
    public function testDocumentationSampleCode(){
        
        $file_contents = file_get_contents( __DIR__ . '/test_data/minimal.xml' );
        $xml_element = new \Altumo\Xml\XmlElement( $file_contents );
        $expected = array(
            "210",
            "510"
        );
        $this->assertEquals( $expected, $xml_element->queryWithXpath('food/sodium', \Altumo\Xml\XmlElement::RETURN_TYPE_STRING) );
        
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



