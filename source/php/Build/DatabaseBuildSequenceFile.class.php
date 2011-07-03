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
* database builds. This log records all of the build operations that exist in
* the application.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class DatabaseBuildSequenceFile extends \Altumo\Xml\XmlFile{
 
    
    /**
    * Constructor for this \Altumo\Xml\XmlFile.
    * 
    * @param string $filename //full path of the filename
    * @param boolean $readonly //whether this \Altumo\Xml\XmlFile is going to be used as a readonly object (doesn't write to xml file)
    * @throws \Exception if file or directory is not writable
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
        
        return '<BuildSequence/>';
        
    }
    
    
    /**
    * Adds a build sequence change.
    * 
    * 
    * @param string $hash
    * @param boolean $upgrade
    * @param boolean $drop
    * @param boolean $snapshot
    * 
    */    
    public function addChange( $hash, $upgrade = null, $drop = null, $snapshot = null ){
        
        $this->assertFileOpen();
        $this->assertFileWritable();
        
        $xml_root = $this->getXmlRoot();
            $change = $xml_root->addChild('Change');
            
                $change->addAttribute( 'hash', $hash );
                            
                if( !is_null($upgrade) ){
                    if( $upgrade ){
                        $change->addAttribute( 'upgrade', 'true' );
                    }else{
                        $change->addAttribute( 'upgrade', 'false' );
                    }
                }
            
                if( !is_null($drop) ){
                    if( $drop ){
                        $change->addAttribute( 'drop', 'true' );
                    }else{
                        $change->addAttribute( 'drop', 'false' );
                    }
                }
            
                if( !is_null($snapshot) ){
                    if( $snapshot ){
                        $change->addAttribute( 'snapshot', 'true' );
                    }else{
                        $change->addAttribute( 'snapshot', 'false' );
                    }
                }
        
        
    }
    
    
    /**
    * Gets the hash of the latest snapshot
    * 
    * @return string
    */
    public function getLastestSnapshotHash(){
        
        $this->assertFileOpen();
        return $this->getXmlRoot()->xpath( 'Change[@snapshot="true"][last()]/attribute::hash', false );
        
    }
    
    
    /**
    * Gets an array of commit hashes (that an upgrade 
    * script was present in) since (but not including)
    * the supplied commit hash.
    * 
    * @param $since_hash
    * @return array
    */
    public function getUpgradeHashesSince( $since_hash ){
        
        return $this->getHashesSince( $since_hash, 'upgrade' );
        
    }
    
    
    /**
    * Gets an array of commit hashes (that an snapshot 
    * script was present in) since (but not including)
    * the supplied commit hash.
    * 
    * @param $since_hash
    * @return array
    */
    public function getSnapshotHashesSince( $since_hash ){
        
        return $this->getHashesSince( $since_hash, 'snapshot' );
        
    }
    
    
    /**
    * Gets an array of commit hashes (that a drop 
    * script was present in) since (but not including)
    * the supplied commit hash.
    * 
    * @param $since_hash
    * @return array
    */
    public function getDropHashesSince( $since_hash ){
        
        return $this->getHashesSince( $since_hash, 'drop' );
        
    }
    
    
    /**
    * Gets an array of commit hashes (that an upgrade 
    * script was present in) before (AND including the current hash)
    * the supplied commit hash.
    * 
    * @param $before_hash
    * @return array
    */
    public function getUpgradeHashesBefore( $before_hash ){
        
        return $this->getHashesBefore( $before_hash, 'upgrade' );
        
    }
    
    
    /**
    * Gets an array of commit hashes (that a snapshot 
    * script was present in) before (AND including the current hash)
    * the supplied commit hash.
    * 
    * @param $before_hash
    * @return array
    */
    public function getSnapshotHashesBefore( $before_hash ){
        
        return $this->getHashesBefore( $before_hash, 'snapshot' );
        
    }
    
    
    /**
    * Gets an array of commit hashes (that a drop 
    * script was present in) before (AND including the current hash)
    * the supplied commit hash.
    * 
    * @param $before_hash
    * @return array
    */
    public function getDropHashesBefore( $before_hash ){
        
        return $this->getHashesBefore( $before_hash, 'drop' );
        
    }
    
    
    /**
    * Gets an array of commit hashes (that an $attribute 
    * script was present in) since (but not including)
    * the supplied commit hash.
    * 
    * @param string $since_hash
    * @param string $attribute
    * @return array
    */
    protected function getHashesSince( $since_hash, $attribute ){
        
        $this->assertFileOpen();
        
        if( !in_array($attribute, $this->getValidAttributes()) ){
            throw new \Exception('Invalid attribute.');
        }
        
        //if the "since" hash was not found, set the hash position to so that it will match all entries
        if( count( $this->getXmlRoot()->queryWithXpath( 'Change[@hash="' . $since_hash . '"]', \Altumo\Xml\XmlElement::RETURN_TYPE_STRING, false ) ) == 0 ){
            $hash_position = 0;
        }else{
            //get the position of the since_hash
            $hash_position = count( $this->getXmlRoot()->queryWithXpath( 'Change[@hash="' . $since_hash . '"]/preceding-sibling::*', \Altumo\Xml\XmlElement::RETURN_TYPE_STRING, false ) ) + 1;
        }
        
        //get the changes
        $changes = $this->getXmlRoot()->queryWithXpath( 'Change[position() > ' . $hash_position . '][@' . $attribute . '="true"]/attribute::hash', \Altumo\Xml\XmlElement::RETURN_TYPE_STRING, false );        
        
        return $changes;
        
    }
    
    
    /**
    * Gets an array of the valid attibutes a Change element may have
    * 
    * @return array
    */
    protected function getValidAttributes(){
        
        return array( 'upgrade', 'drop', 'snapshot' );
        
    }
    
    
    /**
    * Gets an array of commit hashes (that an $attribute 
    * script was present in) before (AND including)
    * the supplied commit hash.
    * 
    * @param string $before_hash
    * @param string $attribute
    * @return array
    */
    protected function getHashesBefore( $before_hash, $attribute ){
        
        $this->assertFileOpen();
        
        if( !in_array($attribute, $this->getValidAttributes()) ){
            throw new \Exception('Invalid attribute.');
        }
        
        //if the "since" hash was not found, set the hash position to so that it will match all entries
        if( count( $this->getXmlRoot()->queryWithXpath( 'Change[@hash="' . $before_hash . '"]', \Altumo\Xml\XmlElement::RETURN_TYPE_STRING, false ) ) == 0 ){
            $hash_position = count( $this->getXmlRoot()->queryWithXpath( 'Change', \Altumo\Xml\XmlElement::RETURN_TYPE_STRING, false ) );
        }else{
            //get the position of the before_hash
            $hash_position = count( $this->getXmlRoot()->queryWithXpath( 'Change[@hash="' . $before_hash . '"]/preceding-sibling::*', \Altumo\Xml\XmlElement::RETURN_TYPE_STRING, false ) ) + 1;
        }
        
        //get the changes
        $changes = $this->getXmlRoot()->queryWithXpath( 'Change[position() <= ' . $hash_position . '][@' . $attribute . '="true"]/attribute::hash', \Altumo\Xml\XmlElement::RETURN_TYPE_STRING, false );        
        
        return $changes;
        
    }

    
}