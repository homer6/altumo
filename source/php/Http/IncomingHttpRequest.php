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
* This class is used to model an incoming http request to the server.
* It's useful for getting the http message body and http headers from
* a given request. It can also rebuild an incoming request.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
* 
*/
class IncomingHttpRequest{
        
    protected $headers = null;
    protected $message_body = null;
    
    
    /**
    * Retrieves the headers and message body from the current HTTP request.
    * 
    * @return IncomingHttpRequest
    */
    function __construct() {
        
        $this->setHeaders( getallheaders() );
        $this->setMessageBody( @file_get_contents('php://input') );
        
    }
   
    
    /**
    * Gets the full HTTP Requst as a string (headers + message body).
    * 
    * @return string
    */
    function getFullRawRequest(){
    
        $raw_request = '';
        
        foreach( $this->getHeaders() as $header => $value ){
            $raw_request .= $header . ': ' . $value . "\n";
        }
        
        $raw_request .= "\n";
        
        $raw_request .= $this->getMessageBody();
        
        return $raw_request;
        
    }

    
    /**
    * Setter for the headers field on this IncomingHttpRequest.
    * 
    * @param array $headers
    */
    protected function setHeaders( $headers ){
    
        $this->headers = $headers;
        
    }
    
    
    /**
    * Getter for the headers field on this IncomingHttpRequest.
    * 
    * @return array
    */
    public function getHeaders(){
    
        return $this->headers;
        
    }
        
    
    /**
    * Gets a specific HTTP header from this IncomingHttpRequest.
    * Returns null if not found (or $default, if provided).
    * 
    * @package string $header
    * @param mixed $default
    * @return string
    */
    public function getHeader( $header, $default = null ){
    
        if( !is_string($header) ){
            return null; 
        }
        if( array_key_exists($header, $this->headers ) ){
            return $this->headers[$header];
        }else{
            return $default;
        }
        
    }

    
    /**
    * Setter for the message_body field on this IncomingHttpRequest.
    * 
    * @param string $message_body
    */
    protected function setMessageBody( $message_body ){
    
        $this->message_body = $message_body;
        
    }
    
    
    /**
    * Getter for the message_body field on this IncomingHttpRequest.
    * 
    * @return string
    */
    public function getMessageBody(){
    
        return $this->message_body;
        
    }
    
    
    /**
    * Determines whether the current request is using HTTPS.
    * 
    * @return boolean
    */
    static public function isSecure(){
    
        if( array_key_exists('HTTP_X_FORWARDED_PROTO', $_SERVER) ){
            $http_x_forwarded_proto = $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ];
        }else{
            $http_x_forwarded_proto = null;
        }
    
        if( array_key_exists('HTTP_X_FORWARDED_PORT', $_SERVER) ){
            $http_x_forwarded_port = $_SERVER[ 'HTTP_X_FORWARDED_PROTO' ];
        }else{
            $http_x_forwarded_port = null;
        }
        
        if( $_SERVER['SERVER_PORT'] != 443 && $http_x_forwarded_proto != 'https' && $http_x_forwarded_port != 443 ){
            return false;
        }
        
        return true;
        
    }


}