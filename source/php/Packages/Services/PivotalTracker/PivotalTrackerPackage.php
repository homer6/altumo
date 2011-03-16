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
    protected $database = null;
    protected $collection_namespace = '';
    protected $client = null;
    
    
    /**
    * Constructor for this PivotalTracker package.
    * 
    * @param string $token 
    * //your PivotalTracker API token
    * 
    * @param MongoDB $database
    * //the collection for this package to persist
    * 
    * @throws \Exception //if $token is not a non-empty string
    * @throws \Exception //if $collection is not a MongoDB object
    * @return Altumo\Packages\Services\PivotalTracker\PivotalTrackerPackage
    */
    public function __construct( $token, $database, $collection_namespace = 'pivotal.' ){
        
        $this->setToken($token);
        $this->setDatabase($database);
        if( !is_null($collection_namespace) ){
            $this->setCollectionNamespace($collection_namespace);
        }
        
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
    * Setter for the database field on this PivotalTrackerPackage.
    * 
    * @param MongoDB $database
    * @throws \Exception //if $collection is not a MongoDB object
    */
    public function setDatabase( $database ){
    
        if( !($database instanceof \MongoDB) ){
            throw new \Exception('Database must be a MongoDB object.');
        }
        $this->database = $database;
        
    }
    
    
    /**
    * Getter for the database field on this PivotalTrackerPackage.
    * 
    * @return MongoDB
    */
    public function getDatabase(){
    
        return $this->database;
        
    }

    
    /**
    * Setter for the collection_namespace field on this PivotalTrackerPackage.
    * 
    * @param string $collection_namespace
    * @throws \Exception //if $collection_namespace is not a string
    */
    public function setCollectionNamespace( $collection_namespace ){
    
        $collection_namespace = \Altumo\Validation\Strings::assertString($collection_namespace);
        $this->collection_namespace = $collection_namespace;
        
    }
    
    
    /**
    * Getter for the collection_namespace field on this PivotalTrackerPackage.
    * 
    * @return string
    */
    public function getCollectionNamespace(){
    
        return $this->collection_namespace;
        
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
        
    
    
    
    /**
    * Refreshes all of the data from the Pivotal API service and insert or 
    * updates MongoDB.
    * 
    */
    public function refreshAllData(){

        //get projects
            $project_collection = $this->getProjectsCollection();
            $project_namespace = $this->getProjectsCollectionNamespace();
            $project_ids = array();    
            $projects = $this->getClient()->getAllProjects();
            foreach( $projects as $project ){
                $project_collection->update( array(
                    'id' => $project->id
                ), $project, true);
                $project_ids[] = $project->id;
            }
            //add indexes
            $project_collection->ensureIndex( array( $project_namespace . '.id', 1 ) );
            $project_collection->ensureIndex( array( $project_namespace . '.name', 1 ) );
        
        //get stories
            $story_collection = $this->getStoriesCollection();
            $story_namespace = $this->getStoriesCollectionNamespace();
            foreach( $project_ids as $project_id ){
                
                $stories = $this->getClient()->getAllStoriesByProjectId($project_id);
                $priority_order = 0;
                $all_parameters = array();
                
                //empty the aggregated project parameters to this project
                    $project_collection->update( array(
                        'id' => $project_id
                    ), array( '$set' => array( 'description' => new \stdClass() ) ), true);
                    
                
                foreach( $stories as $story ){
                    
                    //store the story order (priority).  The story order is the same as the API result order.
                        $priority_order++;
                        $story->priority_order = $priority_order;
                    
                    //if description is valid json, encode it as "parameters" and empty the description field.                    
                        $description = $story->description;
                        if( is_string($description) ){                            
                            $parameters = json_decode($description, true);
                        }else{
                            $parameters = null;
                        }
                        if( !is_null($parameters) ){
                            //save the parameters to the story as an object
                                $story->parameters = $parameters;
                            
                                $all_parameters = \Altumo\Arrays\Arrays::mergeArraysRecursivelyAsLists( $all_parameters, $parameters );
                                
                            //empty the description
                                $story->description = '';
                                
                        }else{
                            $story->parameters = null;
                        }
                    
                    //save/update the story
                        $story_collection->update( array(
                            'id' => $story->id
                        ), $story, true);
                }
                
                //save the aggregated project parameters to this project
                    $all_parameters = \Altumo\Arrays\Arrays::removeNullValuesRecursively( $all_parameters );
                    if( empty($all_parameters) ){
                        $all_parameters = new \stdClass();
                    }
                    $project_collection->update( array(
                        'id' => $project_id
                    ), array( '$set' => array( 'description' => $all_parameters ) ), true);

            }
            //add indexes
            $story_collection->ensureIndex( array( $story_namespace . '.id', 1 ) );
            $story_collection->ensureIndex( array( $story_namespace . '.project_id', 1 ) );
            $story_collection->ensureIndex( array( $story_namespace . '.priority_order', 1 ) );
                
    }
    
    
    /**
    * Gets the MongoCollection for the Projects collection.
    * 
    * @return \MongoCollection
    */
    protected function getProjectsCollection(){
        
        return $this->getDatabase()->createCollection( $this->getProjectsCollectionNamespace() ); 
        
    }
    
    /**
    * Gets the namespace for the Projects Collection
    * 
    * @return string
    */
    protected function getProjectsCollectionNamespace(){
        
        return $this->getCollectionNamespace() . 'projects';        
        
    }
    
    /**
    * Gets the MongoCollection for the Stories collection.
    * 
    * @return \MongoCollection
    */
    protected function getStoriesCollection(){
        
        return $this->getDatabase()->createCollection( $this->getStoriesCollectionNamespace() ); 
        
    }
    
    /**
    * Gets the namespace for the Stories Collection
    * 
    * @return string
    */
    protected function getStoriesCollectionNamespace(){
        
        return $this->getCollectionNamespace() . 'stories';
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
    * Gets an array of the project names (with the IDs as array keys).
    * The project names are in alphabetical order.
    * 
    * @return array
    */
    public function getProjectNames(){
    
        $collection = $this->getProjectsCollection();
        $namespace = $this->getProjectsCollectionNamespace();
        
        //get it from the database
            $cursor = $collection->find( array(), array( 'id' => 1, 'name' => 1 ) );
            $cursor->sort( array( 'name' => 1 ) );
            $projects = array();
            while( $cursor->hasNext() ){
                $project = $cursor->getNext();
                $projects[ $project['id'] ] = $project['name'];
            }
            return $projects;
            
    }
    
    
    /**
    * Gets a project by Pivotal project id
    * 
    * @param integer $project_id
    * @throws \Exception //if $project_id is not a positive integer
    * @throws \Exception //if no Project could be found.
    * @return array
    */
    public function getProjectById( $project_id ){
    
        $project_id = \Altumo\Validation\Numerics::assertPositiveInteger($project_id);
        
        $collection = $this->getProjectsCollection();
        
        //get it from the database
            $result = $collection->findOne( array( 'id' => (string)$project_id ) );
            if( !$result ){
                throw new \Exception('Project not found.');
            }
            return $result;
            
    }
    
    /**
    * Gets a stories by Pivotal project id
    * 
    * @param integer $project_id
    * @throws \Exception //if $project_id is not a positive integer
    * @throws \Exception //if no Project could be found.
    * @return array
    */
    public function getStoriesByProjectId( $project_id ){
    
        $project_id = \Altumo\Validation\Numerics::assertPositiveInteger($project_id);
        
        $collection = $this->getStoriesCollection();
        
        //get it from the database
            $cursor = $collection->find( array( 'project_id' => (string)$project_id ) );
            $cursor->sort( array( 'priority_order' => 1 ) );
            $stories = array();
            while( $cursor->hasNext() ){
                $story = $cursor->getNext();
                $stories[] = $story;
            }
            return $stories;
            
    }
    

    
}