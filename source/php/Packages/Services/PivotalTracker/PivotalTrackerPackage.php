<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/




namespace Altumo\Packages\Services\PivotalTracker;
 
 
/**
* This class represents an single interface to the Pivotal Tracker service.
* It is a package, so it depends on MongoDB and the mongo pecl extension.
* 
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class PivotalTrackerPackage{
        
    protected $token = null;
    protected $collection = null;
    protected $client = null;
    
    
    /**
    * Constructor for this PivotalTracker package.
    * 
    * @param string $token 
    * //your PivotalTracker API token
    * 
    * @param MongoCollection $collection 
    * //the collection for this package to persist
    * 
    * @throws \Exception //if $token is not a non-empty string
    * @throws \Exception //if $collection is not a MongoDB object
    * @return Altumo\Packages\Services\PivotalTracker\PivotalTrackerPackage
    */
    public function __construct( $token, $collection ){
        
        $this->setToken($token);
        $this->setCollection($collection);
        
    }
    
    /**
    * Setter for the token field on this PivotalTrackerPackage.
    * 
    * @param string $token
    * @throws \Exception //if $token is not a non-empty string
    */
    public function setToken( $token ){
    
        $token = \Altumo\Validation\Strings::assertNonEmptyString($token);
        $this->token = $token;
        
    }
    
    
    /**
    * Getter for the token field on this PivotalTrackerPackage.
    * 
    * @return string
    */
    public function getToken(){
    
        return $this->token;
        
    }
        
        
    /**
    * Setter for the collection field on this PivotalTrackerPackage.
    * 
    * @param MongoCollection $collection
    * @throws \Exception //if $collection is not a MongoCollection object
    */
    public function setCollection( $collection ){
    
        if( !($collection instanceof \MongoCollection) ){
            throw new \Exception('Collection must be a MongoCollection object.');
        }
        $this->collection = $collection;
        
    }
    
    
    /**
    * Getter for the collection field on this PivotalTrackerPackage.
    * 
    * @return MongoCollection
    */
    public function getCollection(){
    
        return $this->collection;
        
    }
        
    
    
    /**
    * Gets an array of the project names (with the IDs as array keys).
    * 
    * @return array
    */
    public function getProjectNames(){
    
        $collection = $this->getCollection();
        
        //get it from the database (if not stale)
                
        
        //get it from the remote service
        $projects = $this->getClient()->getAllProjects();
        
        $collection->insert($projects);
        
        return $projects;
              
        
    }
        
        
    /**
    * Setter for the client field on this PivotalTrackerPackage.
    * 
    * @param PivotalTrackerApiClient $client
    */
    protected function setClient( $client ){
    
        $this->client = $client;
        
    }
    
    
    /**
    * Getter for the client field on this PivotalTrackerPackage.
    * 
    * @return PivotalTrackerApiClient
    */
    protected function getClient(){
    
        if( is_null($this->client) ){
            $this->client = new \Altumo\Packages\Services\PivotalTracker\PivotalTrackerApiClient( $this->getToken() );
        }
        
        return $this->client;
        
    }
        
    
    
    
}