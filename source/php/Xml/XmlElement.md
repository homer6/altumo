Description
-----------

XmlElement is designed to wrap the SimpleXml php extension with a simpler, 
more consistent interface. It throws exceptions instead of relying on errors.


Sample Usage
------------

Set contents by string:

    $xml_element = new \Altumo\Xml\XmlElement();
    $xml_element->setContentsByString(
        '<?xml version="1.0" encoding="UTF-8" ?>' . 
        '<hello>World</hello>'
    );

    $this->assertTrue( $xml_element->xpath('.') === 'World' );
    

Read from a file:
    
    $xml_element = new \Altumo\Xml\XmlElement();
    $xml_element->loadFromFile( __DIR__ . '/test_data/minimal.xml' );
    var_dump( $xml_element->queryWithXpath('food/sodium', \Altumo\Xml\XmlElement::RETURN_TYPE_STRING) );
    
    array(2) {
      [0]=>
      string(3) "210"
      [1]=>
      string(3) "510"
    }
    

Read from a file and pass the contents to the constructor as a string:
    
    $file_contents = file_get_contents( __DIR__ . '/test_data/minimal.xml' );
    $xml_element = new \Altumo\Xml\XmlElement( $file_contents );
    var_dump( $xml_element->queryWithXpath('food/sodium', \Altumo\Xml\XmlElement::RETURN_TYPE_STRING) );
    
    array(2) {
      [0]=>
      string(3) "210"
      [1]=>
      string(3) "510"
    }
    
