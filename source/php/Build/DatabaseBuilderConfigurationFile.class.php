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
* database builds. It is used to store the configuration of the database
* build.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class DatabaseBuilderConfigurationFile extends \Altumo\Xml\XmlFile{
 
    protected $database_directory = null;
    
    
    /**
    * Constructor for this \Altumo\Xml\XmlFile.
    * 
    * @param string $filename               //full path of the filename
    * @param boolean $readonly              //whether this \Altumo\Xml\XmlFile 
    *                                         is going to be used as a readonly 
    *                                         object (doesn't write to xml file)
    * @throws \Exception                    //if file or directory is not 
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
        
        $xml_template = new \Altumo\Xml\XmlElement('<DatabaseBuilderConfiguration/>');
            $settings = $xml_template->addChild('Settings');
                $settings->addChild( 'DropOnNewSnapshot', 'false' );
            $database = $xml_template->addChild('Database');
                $database->addChild( 'DatabaseName', 'changeme' );
                $database->addChild( 'Hostname', 'changeme' );
                $database->addChild( 'Username', 'changeme' );
                $database->addChild( 'Password', 'changeme' );
                
        return $xml_template->getXmlAsString(true);
        
    }
   
    
    /**
    * Gets the database connection database name as a string.
    * 
    * @return string
    */
    public function getDatabaseName(){
        
        $this->assertFileOpen();
        return $this->getXmlRoot()->xpath( 'Database/DatabaseName', false );
        
    }
   
    
    /**
    * Gets the database connection hostname as a string.
    * 
    * @return string
    */
    public function getDatabaseHostname(){
        
        $this->assertFileOpen();
        return $this->getXmlRoot()->xpath( 'Database/Hostname', false );
        
    }
   
    
    /**
    * Gets the database connection username as a string.
    * 
    * @return string
    */
    public function getDatabaseUsername(){
        
        $this->assertFileOpen();
        return $this->getXmlRoot()->xpath( 'Database/Username', false );
        
    }
   
    
    /**
    * Gets the database connection password as a string.
    * 
    * @return string
    */
    public function getDatabasePassword(){
        
        $this->assertFileOpen();
        return $this->getXmlRoot()->xpath( 'Database/Password', false );
        
    }
   
    
    /**
    * Determines whether the builder should drop all tables when a new snapshot
    * is found.
    * 
    * @throws \Exception                    //if DropOnNewSnapshot cannot be 
    *                                         interpreted as boolean
    * @return boolean
    */
    public function dropOnNewSnapshots(){
        
        $this->assertFileOpen();
        return \Altumo\Validation\Booleans::assertLooseBoolean( 
            $this->getXmlRoot()->xpath( 'Settings/DropOnNewSnapshot', false ) 
        );
        
    }
        
    
    /**
    * Setter for the database_directory field on this 
    * DatabaseBuilderConfigurationFile.
    * 
    * @param string $database_directory
    */
    public function setDatabaseDirectory( $database_directory ){
    
        $this->database_directory = $database_directory;
        
    }
    
    
    /**
    * Getter for the database_directory field on this 
    * DatabaseBuilderConfigurationFile.
    * 
    * @return string
    */
    public function getDatabaseDirectory(){
    
        return $this->database_directory;
        
    }

    
}
