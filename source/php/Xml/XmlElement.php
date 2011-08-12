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




namespace Altumo\Xml;


/**
* This class represents a single XML element.
* This class is designed to hide the xml parser used and to only expose the 
* methods that are used often, therefore making the interface simpler.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class XmlElement{
    
    protected $loaded = false;
    protected $valid = false;
    protected $errors = array();
    protected $xml_element = null;
    
    const RETURN_TYPE_XML_ELEMENT = 1;
    const RETURN_TYPE_STRING = 2;
        
    
    /**
    * Creates a XmlElement
    * 
    * @param string|\SimpleXMLElement $xml_string
    * @throws \Exception if SimpleXML is not loaded 
    * @return XmlElement
    */    
    public function __construct( $xml_string = null ){
              
        //checks if SimpleXML is loaded
           if( !extension_loaded('simplexml') ){
                throw new \Exception('The PHP extension SimpleXML must be loaded before using XmlElement.');
           }
                              
        if( !is_null($xml_string) && !($xml_string instanceof \SimpleXMLElement) ){
            $this->setContentsByString( $xml_string );
        }elseif( $xml_string instanceof \SimpleXMLElement ){
            $this->setContentsBySimpleXMLElement( $xml_string );
        }
        
    }
    
    
    /**
    * Gets a single Xpath result as string
    * Returns null if no result was found.
    * 
    * @param string $xpath_query the xpath query to be used
    * @param boolean $throw_on_no_result whether this method with throw an exception if no result is found
    * @throws \Exception if there are more than 1 results
    * @return string
    */
    public function xpath( $xpath_query, $throw_on_no_result = true ){
        
        $results = $this->queryWithXpath( $xpath_query, self::RETURN_TYPE_STRING, $throw_on_no_result );
        
        if( empty($results) ){
            if( $throw_on_no_result ){
                throw new \Exception('No result for ' . $xpath_query . ' found.');
            }
            return null;
        }elseif( count($results) === 1 ){
            return reset($results);
        }else{
            throw new \Exception( $xpath_query . ' has more than one result.');
        }
    }
    
    
    /**
    * Queries this document and returns an array of strings the result of the xpath query
    * Returns an array of XmlElement objects if $return_type = XmlElement::RETURN_TYPE_XML_ELEMENT
    * 
    * @param string $xpath_query
    * @param integer $return_type
    * @param boolean $throw_on_no_result whether this method with throw an exception if no result is found
    * @throws \Exception if this object is not loaded
    * @throws \Exception if $xpath_query is not a string
    * @return array
    */
    public function queryWithXpath( $xpath_query, $return_type = self::RETURN_TYPE_STRING, $throw_on_no_result = true ){
        
        if( !is_string($xpath_query) ){
            throw new \Exception('Invalid type.  XmlElement::queryWithXpath expects a string argument.');
        }
        $this->assertLoaded();
                
        $results = $this->getXmlElement()->xpath( $xpath_query );
        
        //convert results to string
            if( $return_type === self::RETURN_TYPE_STRING ){
                                          
                if( $results instanceof \SimpleXMLElement ){
                    $results = array(
                        (string)$results
                    );
                }else{
                    if( !is_array($results) ){
                        if( $throw_on_no_result ){
                            throw new \Exception('No result for ' . $xpath_query . ' found.');
                        }else{
                            return array();
                        }
                    }
                    foreach( $results as $key => $result ){
                         $results[$key] = (string)$result;
                    }
                }                
            
            }elseif( $return_type === self::RETURN_TYPE_XML_ELEMENT ){
                
                if( $results instanceof \SimpleXMLElement ){
                    return array(
                        new XmlElement( $results )
                    );
                }else{  
                    if( !is_array($results) ){
                        if( $throw_on_no_result ){
                            throw new \Exception('No result for ' . $xpath_query . ' found.');
                        }else{
                            return array();
                        }
                    }
                    foreach( $results as $key => $result ){                        
                        $results[$key] = new XmlElement( $result );
                    }
                }
                                
            }else{
                throw new \Exception('Unknown return type.');
            }

        return $results;
        
    }
    
    
    /**
    * Loads this xml element from the supplied filename
    * eg.
    *   /var/www/html/file.xml
    * 
    * @param string $filename
    * @throws \Exception if file does not exist
    * @throws \Exception if contents are not valid XML
    */
    public function loadFromFile( $filename ){
        
        if( !file_exists($filename) ){
            throw new \Exception( $filename . ' does not exist or is not readable.' );
        }
        
        $contents = file_get_contents($filename);
        $this->setContentsByString($contents);
        
    }
    
    
    /**
    * Creates a XmlElement
    * 
    * @param string $xml_string
    * @throws \Exception on parse error
    */   
    public function setContentsByString( $xml_contents ){
        
        //Use libxml_use_internal_errors() to suppress all XML errors, and libxml_get_errors() to iterate over them afterwards. 
        
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        
        $xml_element = new \SimpleXMLElement($xml_contents);
        $errors = libxml_get_errors();       
        if( !($xml_element instanceof \SimpleXMLElement) || !empty($errors) ){
            $this->setValid( false );
            $this->setLoaded( false );
            $this->setErrors( $errors );
            $this->xml_element = null;
        }else{
            $this->xml_element = $xml_element;
            $this->setLoaded(true);
            $this->setErrors( $errors );
            $this->setValid(true);
        }
        libxml_clear_errors();
        
        if( !$this->isValid() ){
            throw new \Exception('XML Failed to parse.');
        }
                 
    }
    
    
    /**
    * Creates a XmlElement
    * 
    * @param string $xml_string
    */   
    public function setContentsBySimpleXMLElement( $xml_contents ){
        
        //Use libxml_use_internal_errors() to suppress all XML errors, and libxml_get_errors() to iterate over them afterwards. 
        if( !($xml_contents instanceof \SimpleXMLElement) ){
            throw new \Exception('First argument is expected to be of type \SimpleXMLElement');
        }
        
        $this->xml_element = $xml_contents;
        $this->setLoaded(true);
        $this->setValid(true);        
                 
    }
    
    
    /**
    * Gets the internal object used to manipulate the xml element
    * 
    * @return \SimpleXMLElement
    */    
    protected function getXmlElement(){
        
        $this->assertLoaded();
        return $this->xml_element;
                
    }
    
    
    /**
    * Sets whether this Xml Element is populated with content
    * 
    * @param boolean $loaded
    */    
    protected function setLoaded( $loaded = true ){
        
        if( !is_bool($loaded) ){
            throw new \Exception('Invalid type.  XmlElement::setLoaded expects a boolean argument.');
        }
        $this->loaded = $loaded;
        
    }
    
    
    /**
    * Sets whether this Xml Element is populated with content
    * 
    * @return boolean
    */    
    public function isLoaded(){
        
        return $this->loaded;
        
    }
    
    
    /**
    * Throws an \Exception if this object is not loaded.
    * 
    * @throws \Exception
    */    
    public function assertLoaded(){
        
        if( !$this->isLoaded() ){
            throw new \Exception('This method requires that this XmlElement is loaded first.');
        }
        
    }
    
    
    /**
    * Throws an \Exception if this object is not valid.
    * 
    * @throws \Exception
    */    
    public function assertValid(){
        
        if( !$this->isValid() ){
            throw new \Exception('This xml file is not valid.');
        }
        
    }
    
    
    /**
    * Sets whether this Xml Element is valid, well-formed XML
    * 
    * @param boolean $valid
    */    
    protected function setValid( $valid = true ){
        
        if( !is_bool($valid) ){
            throw new \Exception('Invalid type.  XmlElement::setValid expects a boolean argument.');
        }
        $this->valid = $valid;
        
    }  
          
    
    /**
    * Determines if the supplied XML content is valid XML
    * 
    * @return boolean
    */
    public function isValid(){
        
        return $this->valid;
        
    }
    
          
    /**
    * Sets the array of errors, if there were errors in parsing this element
    * 
    * @param array $errors
    */    
    protected function setErrors( $errors ){
        
        if( !is_array($errors) ){
            throw new \Exception('Invalid type.  XmlElement::setErrors expects an array as its first argument.');
        }
        
    }
    
    
    /**
    * Gets an array of errors (eg. Parse errors )
    * 
    * @return array
    */
    public function getErrors(){
        
        return $this->errors;
        
    }


    /**
    * Returns the contents of this xml element as a string.
    * 
    * @param boolean $pretty  //formats the xml string in a human-readable version
    * @return string  //encoded as utf-8
    */
    public function getXmlAsString( $pretty = false ){
        
        $this->assertLoaded();
        $xml = $this->getXmlElement()->asXML();
        $xml_string = str_replace( '<?xml version="1.0"?>', '<?xml version="1.0" encoding="UTF-8"?>', $xml);
        
        if( $pretty ){
            $xml_string = self::prettyFormatXmlString($xml_string);
        }
        
        return utf8_encode($xml_string);
        
    }
    
    
    /**
    * Adds an attribute to this element
    * 
    * @param string $name
    * @param string $value
    * @param string $namespace
    * @return XmlElement
    */
    public function addAttribute( $name, $value, $namespace = null ){
        
        $this->assertLoaded();
        $this->getXmlElement()->addAttribute( $name, $value, $namespace );
        return $this;
        
    }
    
    
    /**
    * Adds a child element to this element
    * 
    * @param string $name
    * @param string $value
    * @param string $namespace
    * @return XmlElement
    */
    public function addChild( $name, $value = null, $namespace = null ){
        
        $this->assertLoaded();
        $child = $this->getXmlElement()->addChild( $name, $value, $namespace );
        return new XmlElement($child);
                
    }
    
    
    /**
    * Formats the xml string provided as a pretty output.
    * 
    * @see http://gdatatips.blogspot.com/2008/11/xml-php-pretty-printer.html
    * 
    * @param string $xml_string
    * @param integer $indent_size
    * @return string
    */
    static public function prettyFormatXmlString( $xml_string, $indent_size = 4 ){
          
        $level = $indent_size;  
        $indent = 0; // current indentation level  
        $pretty = array();

        // get an array containing each XML element  
        $xml = explode( "\n", preg_replace('/>\s*</', ">\n<", $xml_string) );  

        // shift off opening XML tag if present  
        if( count($xml) && preg_match('/^<\?\s*xml/', $xml[0]) ){  
            $pretty[] = array_shift($xml);
        }

        foreach( $xml as $el ){
            
            if( preg_match('/^<([\w])+[^>\/]*>$/U', $el) ){
                
                // opening tag, increase indent  
                $pretty[] = str_repeat(' ', $indent) . $el;  
                $indent += $level;
                
            }else{
             
                if( preg_match('/^<\/.+>$/', $el) ){
                    $indent -= $level;  // closing tag, decrease indent  
                }
                if( $indent < 0 ){
                    $indent += $level;
                }
                $pretty[] = str_repeat( ' ', $indent ) . $el;
                
            }
            
        }
        
        $xml = implode("\n", $pretty);     
        return $xml;
        
    }    
    
    
    /**
    * Gets the name of this element (tag name).
    * 
    * @return string
    */
    public function getName(){
        
        $this->assertLoaded();
        return $this->getXmlElement()->getName();
        
    }
    
    
    /**
    * Gets this element as a JSON string.
    * 
    * @return string
    */
    public function getAsJsonString(){
        
        return json_encode( $this->getXmlElement() );
        
    }

    
}