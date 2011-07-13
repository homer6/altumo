<?php

/*
 * This file is part of the Altumo library.
 *
 * (c) Steve Sperandeo <steve.sperandeo@altumo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Altumo\Build;
 

/**
* An instance of this class represents the xml log file that is used to perform 
* database builds. This log records all of the build operations that have been 
* applied to this environment.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class DatabaseBuildLogFile extends \Altumo\Xml\XmlFile{
 
    
    /**
    * Constructor for this \Altumo\Xml\XmlFile.
    * 
    * @param string $filename               //full path of the filename
    * @param boolean $readonly              //whether this \Altumo\Xml\XmlFile 
    *                                         is going to be used as a readonly
    *                                         object (doesn't write to xml file)
    * @throws \Exception                   //if file or directory is not 
    *                                         writable
    * @return \Altumo\Xml\XmlFile
    */
    public function __construct( $filename, $readonly = true ){    
    
        parent::__construct( $filename, $readonly );
     
    }
    
    
    /**
    * Gets the default empty file as an xml string.
    * 
    * @return string
    */
    protected function getDefaultEmptyFile(){
        
        return '<BuildLog/>';
        
    }
    
    
    /**
    * Adds a record of an upgrade.  This happens when an upgrade gets applied to a database.
    * 
    * 
    * @param string $hash
    * 
    */    
    public function addUpgrade( $hash ){
        
        $this->assertFileOpen();
        $this->assertFileWritable();
        
        $xml_root = $this->getXmlRoot();
            $change = $xml_root->addChild('Upgrade');
            
                $change->addAttribute( 'hash', $hash );
                $change->addAttribute( 'datetime', date('c') );
        
        
    }    
    
    
    /**
    * Adds a record of a drop.  This happens when a drop gets applied to a database.
    * 
    * 
    * @param string $hash
    * 
    */    
    public function addDrop( $hash ){
        
        $this->assertFileOpen();
        $this->assertFileWritable();
        
        $xml_root = $this->getXmlRoot();
            $change = $xml_root->addChild('Drop');
            
                $change->addAttribute( 'hash', $hash );
                $change->addAttribute( 'datetime', date('c') );
                
    }
    
    
    /**
    * Adds a record of a snapshot.  This happens when a snapshot gets applied to a database.
    * 
    * 
    * @param string $hash
    * 
    */    
    public function addSnapshot( $hash ){
        
        $this->assertFileOpen();
        $this->assertFileWritable();
        
        $xml_root = $this->getXmlRoot();
            $change = $xml_root->addChild('Snapshot');
            
                $change->addAttribute( 'hash', $hash );
                $change->addAttribute( 'datetime', date('c') );
        
        
    }
    
    
    /**
    * Gets the last log entry
    * Returns null if no entry found.
    * 
    * @return \Altumo\Xml\XmlElement
    */
    public function getLastLogEntry(){
        
        $this->assertFileOpen();
        $last_entry_query_result = $this->getXmlRoot()->queryWithXpath( 'child::*[last()]', \Altumo\Xml\XmlElement::RETURN_TYPE_XML_ELEMENT, false );
        if( empty($last_entry_query_result) ){
            return null;
        }else{
            return reset($last_entry_query_result);
        }
        
    }
    
    
    /**
    * Gets the hash of the last log entry
    * Returns null if no entry found.
    * 
    * @return string
    */
    public function getLastLogEntryHash(){
        
        $this->assertFileOpen();
        return $this->getXmlRoot()->xpath( 'child::*[last()]/attribute::hash', false );
        
    }

}
