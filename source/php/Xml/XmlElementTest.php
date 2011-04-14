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
    
}

