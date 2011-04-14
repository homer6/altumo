Description
-----------

XmlElement is designed to wrap the SimpleXml php extension with a simpler, 
more consistent interface. It throws exceptions instead of relying on errors.


Sample Usage
------------

    $xml_file = new \Altumo\Xml\XmlElement();
    $xml_file->setContentsByString(
        '<?xml version="1.0" encoding="UTF-8" ?>' . 
        '<hello>World</hello>'
    );

    $this->assertTrue( $xml_file->xpath('.') === 'World' );