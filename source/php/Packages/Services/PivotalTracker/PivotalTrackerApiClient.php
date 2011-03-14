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
* This class represents an HTTP API client for Pivotal that returns translated
* JSON responses.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class PivotalTrackerApiClient{
    
    protected $token = null;
    
    /**
    * Constructor for this PivotalTrackerApiClient.
    * 
    * @param string $token //your PivotalTracker API token
    * 
    * @throws \Exception //if $token is not a non-empty string
    * @return Altumo\Packages\Services\PivotalTracker\PivotalTrackerApiClient
    */
    public function __construct( $token ){
        
        $this->setToken($token);
        
    }
    
    /**
    * Setter for the token field on this PivotalTrackerApiClient.
    * 
    * @param string $token
    * @throws \Exception //if $token is not a non-empty string
    */
    public function setToken( $token ){
    
        $token = \Altumo\Validation\Strings::assertNonEmptyString($token);
        $this->token = $token;
        
    }
    
    
    /**
    * Getter for the token field on this PivotalTrackerApiClient.
    * 
    * @return string
    */
    public function getToken(){
    
        return $this->token;
        
    }
    
    
    /**
    * Sends an HTTP request to the supplied url and returns the response as
    * an JSON-decoded php datastructure (usually an array; may be a string).
    *   eg. '/services/v3/activities?newer_than_version=134'
    * 
    * @param string $url
    * @return array
    */
    protected function sendRequest( $url ){
        
        $request = new \Altumo\Http\OutgoingHttpRequest( 'https://www.pivotaltracker.com' . $url );
        $request->addHeader( 'X-TrackerToken', $this->getToken() );
        $response = $request->send();

        return json_decode( \Altumo\Javascript\Json\JsonFormatter::convertXmlToJson( $response ) );
        
    }
    
    
    /**
    * Gets all projects visible from this api token (may include projects 
    * from several accounts).
    * 
    * @return array
    */
    public function getAllProjects(){
        
        try{
            $projects = $this->sendRequest( '/services/v3/projects' );
            if( property_exists($projects, 'project') ){
                $projects = $projects->project;
            }
        }catch( \Exception $e ){
            $projects = array();
        }
        return $projects;
        
    }
    
    
}