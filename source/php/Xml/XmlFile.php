<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/



namespace Altumo\Xml;

require_once __DIR__ . '/XmlElement.php';
 
 
/**
* An instance of this class represents an xml file on the filesystem.
* It is designed to be extended.
* 
* For an example of how to extend it, 
* @see \sfAltumoPlugin\Build\DatabaseBuildSequenceFile in 
*       https://github.com/homer6/sfAltumoPlugin
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
abstract class XmlFile{
    
    protected $filename = null;
    protected $read_only = null;
    
    protected $file_pointer = null;
    protected $file_contents = null;
    protected $xml_root = null;

    /**
    * Constructor for this XmlFile.
    * 
    * @param string $filename //full path of the filename
    * @param boolean $readonly //whether this XmlFile is going to be used as a readonly object (doesn't write to xml file)
    * @throws \Exception if file or directory is not writable
    * @return XmlFile
    */
    public function __construct( $filename, $readonly = true ){    
    
        $this->setFilename( $filename, $readonly );
     
    }
    
    
    
    /**
    * Setter for the filename field on this XmlFile.
    * 
    * The supplied file must be writable, or if the file doesn't exist already,
    * the directory that contains it must be writable.
    * 
    * This method effectively opens the file for writing.
    * 
    * @param string $filename //full path of the filename
    * @param boolean $readonly //whether this XmlFile is going to be used as a readonly object (doesn't write to xml file)
    * @throws \Exception if file or directory is not writable
    * @throws \Exception if readonly and file does not exist or is not readable
    */
    public function setFilename( $filename, $readonly = true ){
                
        $existing_umask = umask(0);
        umask(0111);
        
        try{
            if( !$readonly ){
                
                if( file_exists($filename) && is_writable($filename) ){
                    
                    $file_contents = file_get_contents($filename);
                    if( empty($file_contents) ){
                        $file_contents = $this->getDefaultEmptyFile();
                    }
                    $this->closeFile();
                    $this->filename = $filename;          
                    $file_pointer = fopen( $filename, 'w+' );
                    $this->setFileContents( $file_contents );
                    $this->setXmlRoot( new \Altumo\Xml\XmlElement($file_contents) );
                    $this->setFilePointer( $file_pointer );
                    
                }elseif( is_writable( dirname($filename) ) ){
                    
                    $this->closeFile();
                    $this->filename = $filename;
                    $file_pointer = fopen( $filename, 'w+' );
                    $file_contents = $this->getDefaultEmptyFile();
                    $this->setFileContents( $file_contents );
                    $this->setXmlRoot( new \Altumo\Xml\XmlElement($file_contents) );
                    $this->setFilePointer( $file_pointer );
                    
                }else{
                    throw new \Exception("File or directory is not writable. ($filename)");
                }
                
                $this->setReadOnly(false);
                
            }else{
                
                if( file_exists($filename) ){
                    
                    $file_contents = file_get_contents($filename);
                    if( empty($file_contents) ){
                        $file_contents = $this->getDefaultEmptyFile();
                    }
                    $this->filename = $filename;
                    $file_pointer = fopen( $filename, 'r' );
                    $this->setFileContents( $file_contents );
                    $this->setXmlRoot( new \Altumo\Xml\XmlElement($file_contents) );
                    $this->setFilePointer( $file_pointer );
                    
                    $this->setReadOnly(true);
                    
                }else{
                    throw new \Exception("File does not exist. ($filename)");
                }

                
            }
            
            umask($existing_umask);            
            
        }catch( Exception $e ){
            $this->closeFile(false);
            umask($existing_umask);
            throw $e;
        }
        
    }
    
    /**
    * Gets the default empty file as an xml string.
    * 
    * @return string
    */
    protected function getDefaultEmptyFile(){
        
        return '<Base/>';
        
    }
    
    
    /**
    * Getter for the filename field on this XmlFile.
    * 
    * @return string
    */
    public function getFilename(){
    
        return $this->filename;
        
    }
    
    
    /**
    * Setter for the file_pointer field on this XmlFile.
    * 
    * @param resource $file_pointer
    */
    protected function setFilePointer( $file_pointer ){
    
        $this->file_pointer = $file_pointer;
        
    }
    
    
    /**
    * Getter for the file_pointer field on this XmlFile.
    * 
    * @return resource
    */
    protected function getFilePointer(){
    
        return $this->file_pointer;
        
    }
        
        
    /**
    * Setter for the file_contents field on this XmlFile.
    * 
    * @param string $file_contents
    */
    protected function setFileContents( $file_contents ){
    
        $this->file_contents = $file_contents;
        
    }
    
    
    /**
    * Getter for the file_contents field on this XmlFile.
    * 
    * @return string
    */
    protected function getFileContents(){
    
        return $this->file_contents;
        
    }
        
        
    /**
    * Setter for the xml_root field on this XmlFile.
    * 
    * @param \Altumo\Xml\XmlElement $xml_root
    */
    protected function setXmlRoot( $xml_root ){
    
        $this->xml_root = $xml_root;
        
    }
    
    
    /**
    * Getter for the xml_root field on this XmlFile.
    * 
    * @return \Altumo\Xml\XmlElement
    */
    protected function getXmlRoot(){
    
        return $this->xml_root;
        
    }
        
        
    /**
    * Setter for the read_only field on this XmlFile.
    * 
    * @param boolean $read_only
    */
    protected function setReadOnly( $read_only ){
    
        $this->read_only = $read_only;
        
    }
    
    
    /**
    * Getter for the read_only field on this XmlFile.
    * 
    * @return boolean
    */
    protected function getReadOnly(){
    
        return $this->read_only;
        
    }
    
    
    /**
    * Determines whether this file has been opened as a read-only file.
    * 
    * @return boolean
    */
    public function isReadOnly(){
    
        return ( $this->getReadOnly() === true );
        
    }
    
    /**
    * Throws an exception if this file is not writable.
    * 
    * @throws \Exception if file is not writable
    */
    protected function assertFileWritable(){
        
        if( $this->isReadOnly() ){
            throw new \Exception('This methods requires the file to be opened as writable.');
        }
        
    }
        
    
    /**
    * Determines if this file is open.
    * 
    * @return boolean
    */
    protected function isFileOpen(){
        
        if( $this->getFilePointer() ){
            return true;
        }else{
            return false;
        }
        
    }
    
    
    /**
    * Throws an exception if this file is not open.
    * 
    * @throws \Exception if file is not open
    */
    protected function assertFileOpen(){
        
        if( !$this->isFileOpen() ){
            throw new \Exception('This methods requires the file to be loaded.');
        }
        
    }
    
    
        
    
    
    /**
    * Closes this file, if it's open. This will write to it by default.
    * 
    * @param boolean $write                 //whether this method should write 
    *                                         the contents before closing.  
    *                                         Defaults to yes.
    */
    public function closeFile( $write = true ){
        
        if( $this->isFileOpen() ){
            
            if( !$this->isReadOnly() ){
                $this->writeToFile();
            }
            
            fclose( $this->getFilePointer() ); 
            $this->filename = null;            
            $this->file_pointer = null;
            $this->file_contents = null;
            $this->xml_root = null;
            $this->read_only = null;
            
        }
        
    }
    
    
    /**
    * Saves the contents to the file.
    * 
    */
    public function writeToFile(){

        $this->assertFileOpen();
        $this->assertFileWritable();
        $file_contents = $this->getXmlRoot()->getXmlAsString(true);
        $file_pointer = $this->getFilePointer();
        ftruncate( $file_pointer, 0 );
        rewind( $file_pointer );
        fwrite( $this->getFilePointer(), $file_contents );

    }
    
    
    /**
    * Closes the file when the object is destroyed (this will write to the file
    * which is the desired behavior).
    * 
    */
    public function __destruct(){
        
        $this->closeFile();
        
    }
    
    
    
    
}
