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
    

Create a new XML element (or modify an existing one):

    $xml_template = new \Altumo\Xml\XmlElement( '<DatabaseConfiguration/>' );
        $database = $xml_template->addChild('Database');
            $database->addChild( 'DatabaseName', 'changeme' );
            $database->addChild( 'Hostname', 'changeme' );
            $database->addChild( 'Username', 'changeme' );
            $database->addChild( 'Password', 'changeme' );
        $last_updated = $xml_template->addChild( 'LastUpdated', \Altumo\Utils\Date::getInstance()->format(DATE_RFC822) );

    var_dump( $xml_template->getXmlAsString( true, false ) );

    string(301) "<DatabaseConfiguration>
        <Database>
            <DatabaseName>changeme</DatabaseName>
            <Hostname>changeme</Hostname>
            <Username>changeme</Username>
            <Password>changeme</Password>

        </Database>
        <LastUpdated>Thu, 29 Sep 11 10:32:08 -0700</LastUpdated>
    </DatabaseConfiguration>
    "