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




namespace Altumo\Http;


/**
* This is a base REST JSON API Client class. It is meant to be
* extended to create clients for specific APIs.
*
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class ApiClient{

    //the api authorization token
    protected $token = null;

    //the hostname that his client will connect to
    protected $hostname = null;

    //the curl session
    protected $session = null;

    //whether the http request requires third-party verification on the
    //service's cert; defaults to true
    protected $verify_ssl_peer = true;

    //the last response of the api client (for debugging)
    protected $last_response = null;

    //whether this ApiClient will attempt to use https; false will use http
    protected $https = true;


    /**
    * Constructor for this ApiClient.
    *
    * @param string $token                  //your API token
    *
    * @throws \Exception                    //if $token is not a non-empty string
    * @return \Altumo\Http\ApiClient
    */
    public function __construct( $token ){

        $this->setToken($token);
        //set the hostname in the constructor when you extend this class

    }

    /**
    * Destructor for this ApiClient.
    *
    */
    public function __destruct(){

        if( !is_null($this->session) ){
            unset($this->session);
        }

    }

    /**
    * Setter for the token field on this ApiClient.
    *
    * @param string $token
    * @throws \Exception //if $token is not a non-empty string
    */
    public function setToken( $token ){

        $token = \Altumo\Validation\Strings::assertNonEmptyString($token);
        $this->token = $token;

    }


    /**
    * Getter for the token field on this ApiClient.
    *
    * @return string
    */
    public function getToken(){

        return $this->token;

    }



    /**
    * Setter for the hostname field on this ApiClient.
    *
    * @param string $hostname
    */
    public function setHostname( $hostname ){

        $this->hostname = $hostname;

    }


    /**
    * Getter for the hostname field on this ApiClient.
    *
    * @return string
    */
    public function getHostname(){

        return $this->hostname;

    }



    /**
    * Setter for the session field on this ApiClient.
    *
    * @param \Altumo\Http\OutgoingHttpRequest $session
    */
    protected function setSession( $session ){

        $this->session = $session;

    }


    /**
    * Getter for the session field on this ApiClient.
    *
    * @return \Altumo\Http\OutgoingHttpRequest
    */
    public function getSession(){

        //there is an outstanding bug in the curl library that prevents
        //persistent sessions from working properly
        //so we create a temporarily create a new OutgoingHttpRequest
        //for every request (hence the 1 below)
        if( 1 || !($this->session instanceof \Altumo\Http\OutgoingHttpRequest) ){
            if( $this->getHttps() ){
                $scheme = 'https';
            }else{
                $scheme = 'http';
            }
            $request = new \Altumo\Http\OutgoingHttpRequest( $scheme . '://' . $this->getHostname() );
            $request->addHeader( 'Accept', 'application/json' );
            $request->setVerifySslPeer( $this->getVerifySslPeer() );
            $this->session = $request;
        }

        return $this->session;

    }


    /**
    * Setter for the verify_ssl_peer field on this ApiClient.
    * Do not turn this off when connecting to a production system.
    *
    * @param boolean $verify_ssl_peer
    */
    public function setVerifySslPeer( $verify_ssl_peer ){

        $this->verify_ssl_peer = $verify_ssl_peer;

    }


    /**
    * Getter for the verify_ssl_peer field on this ApiClient.
    *
    * @return boolean
    */
    public function getVerifySslPeer(){

        return $this->verify_ssl_peer;

    }


    /**
    * Setter for the https field on this ApiClient.
    *
    * @param boolean $https
    * @throws \Exception                    //if $https is not a boolean
    */
    public function setHttps( $https ){

        $this->https = \Altumo\Validation\Booleans::assertLooseBoolean( $https );

    }


    /**
    * Getter for the https field on this ApiClient.
    *
    * @return boolean
    */
    public function getHttps(){

        return $this->https;

    }


    /**
    * Sends an HTTP request to the supplied url and returns the response as
    * an JSON-decoded php object.
    *
    * @param string $url                    //route of the API method
    *                                         eg. '/system-events'
    *
    * @param array $url_parameters          //array of parameters for GET
    *
    * @param string $request_method         //defaults to GET
    *
    * @param string $message_body           //defaults to null (no message body)
    *
    * @throws \Exception                    //if there was a communication error
    * @throws \Exception                    //if the response message body was
    *                                         not valid json
    * @throws \Exception                    //if the $request_method was invalid
    *
    * @return stdClass
    */
    protected function sendRequest( $url, $url_parameters = array(), $request_method = null, $message_body = null ){

        //prepare and send the request
            if( is_null($request_method) ){
                $request_method = \Altumo\Http\OutgoingHttpRequest::HTTP_METHOD_GET;
            }
            $request = $this->getSession();
            if( $this->getHttps() ){
                $scheme = 'https';
            }else{
                $scheme = 'http';
            }
            $request->setUrl( $scheme . '://' . $this->getHostname() . $url );
            $request->setParameters( $url_parameters );
            $request->setRequestMethod( $request_method );
            $request->setMessageBody( $message_body );

            //For debugging the request and response
            /*
            if( strstr($url,'/system-event') && $request_method == \Altumo\Http\OutgoingHttpRequest::HTTP_METHOD_POST ){
                $response = $request->send(); //true, true );
                //\Altumo\Utils\Debug::dump($response, $request->getCurlInfo());
                \Altumo\Utils\Debug::dump($response);
            }else{
                $response = $request->send();
            }*/
            $this->last_response = $request->send();

        //process the response
            $result_decoded = json_decode( $this->last_response );
            if( is_null($result_decoded) ){
                throw new \Exception('Invalid server response. Valid JSON expected. ');
            }else{
                return $result_decoded;
            }

    }


    /**
    * Optionally builds a string to add to the end of the URL.
    *   eg.
    *       /2,4,5,6,8
    *
    * @param array $ids
    * @throws \Exception                    //if $ids is not null or an
    *                                         array of positive integers.
    * @return string
    */
    static protected function constructIdsUrlSuffix( $ids = null ){

        $url_suffix = '';
        if( !is_null($ids) ){
            $ids = \Altumo\Validation\Arrays::sanitizeCsvArrayPostitiveInteger( $ids );
            return '/' . implode(',', $ids);
        }

        return '';

    }


    /**
    * Encodes a value into a JSON string. Throws an exception if it fails to
    * encode.
    *
    * @param mixed $ids
    * @throws \Exception                    //if encoding fails
    * @return string
    */
    static protected function encodeAsJson( $value ){

        $encoded_value = json_encode($value);
        if( !is_string($encoded_value) ){
            throw new \Exception('Value could not be encoded as JSON.');
        }
        if( $encoded_value === 'null' ){
            throw new \Exception('Null value passed to be encoded as JSON.');
        }
        return $encoded_value;

    }


    /**
    * Does a var_dump on the last response (and any args passed to this
    * method). This will exit the script.
    *
    */
    public function dumpLastResponse(){

        $args = func_get_args();
        if( !empty($args) ){
            \Altumo\Utils\Debug::dump( $this->last_response, $args );
        }else{
            \Altumo\Utils\Debug::dump( $this->last_response );
        }                

    }


}