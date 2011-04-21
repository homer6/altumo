<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/




namespace Altumo\Http;
 
/**
* This class is used to easily parse a full raw HTTP Response.
* 
* @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec6.html#sec6
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class HttpResponseMessage{

    protected $status_line = '';
    protected $http_version = '';
    protected $status_code = null;
    protected $reason_phrase = '';
            
    protected $headers = array();
    protected $lowercase_headers = array();
    protected $message_body = '';
    protected $raw_http_response = '';
    
    /**
    * Contstructor for this raw HTTP Response.
    * 
    * @param string $raw_http_response
    * @throws \Exception if $raw_http_response is not a string.
    * @throws \Exception if $raw_http_response is not a valid http response.
    * @return HttpResponseMessage
    */
    public function __construct( $raw_http_response ){
        
        //sets the parameters
            if( !is_string($raw_http_response) ){
                throw new \Exception('The PHP extension CURL must be loaded before making requests.');
            }
            
        //sets the url
            $this->parseRawHttpResponse($raw_http_response);
        
    }
    
    /**
    * Parses the raw HTTP response into its logical parts.
    * After this is called, use the accessors to get the logical parts of 
    * the HTTP Response.
    * 
    * @throws \Exception if $raw_http_response is not a valid http response.
    * @param string $raw_http_response
    */
    public function parseRawHttpResponse( $raw_http_response ){
        
        $this->setRawHttpResponse($raw_http_response);
        $raw_response_parts = preg_split( '/\\r\\n/', $raw_http_response );
        
        $status_line = array_shift($raw_response_parts);
        $this->setStatusLine($status_line);
        
        while( 1 ){
            $line = array_shift($raw_response_parts);
            if( !empty($line) ){
                $this->addHeaderLine( $line );
            }else{
                break;
            }
        }

        $this->setMessageBody( implode('', $raw_response_parts) );
        
    }
    
   
    /**
    * Setter for the status_line field on this HttpResponseMessage.
    * 
    * @param string $status_line
    */
    protected function setStatusLine( $status_line ){
    
        $status_line_parts = preg_split( '/ /', $status_line );
        if( count($status_line_parts) < 3 ){
            throw new \Exception('Malformed http status line.');
        }
        $this->status_line = $status_line;
        
        $this->setHttpVersion( array_shift($status_line_parts) );
        $this->setStatusCode( intval( array_shift($status_line_parts) ) );
        $this->setReasonPhrase( implode('', $status_line_parts) );
        
    }
    
    
    /**
    * Getter for the status_line field on this HttpResponseMessage.
    * 
    * @return string
    */
    public function getStatusLine(){
    
        return $this->status_line;
        
    }
        
    
    /**
    * Setter for the http_version field on this HttpResponseMessage.
    * 
    * @param string $http_version
    */
    protected function setHttpVersion( $http_version ){
    
        $this->http_version = $http_version;
        
    }
    
    
    /**
    * Getter for the http_version field on this HttpResponseMessage.
    * 
    * @return string
    */
    public function getHttpVersion(){
    
        return $this->http_version;
        
    }
        
    
    /**
    * Setter for the status_code field on this HttpResponseMessage.
    * 
    * @param integer $status_code
    */
    protected function setStatusCode( $status_code ){
    
        $this->status_code = $status_code;
        
    }
    
    
    /**
    * Getter for the status_code field on this HttpResponseMessage.
    * 
    * @return integer
    */
    public function getStatusCode(){
    
        return $this->status_code;
        
    }
        
    
    /**
    * Setter for the reason_phrase field on this HttpResponseMessage.
    * 
    * @param string $reason_phrase
    */
    protected function setReasonPhrase( $reason_phrase ){
    
        $this->reason_phrase = $reason_phrase;
        
    }
    
    
    /**
    * Getter for the reason_phrase field on this HttpResponseMessage.
    * 
    * @return string
    */
    public function getReasonPhrase(){
    
        return $this->reason_phrase;
        
    }
    
    
    /**
    * Adds a whole HTTP header line.
    * eg. 
    *   Vary: Accept-Encoding
    * 
    * @throws \Exception //if header line is malformed
    * @param string $header_line
    */
    protected function addHeaderLine( $header_line ){
    
        $header_line_parts = preg_split( '/:\s?/', $header_line );
        if( count($header_line_parts) < 2 ){
            throw new \Exception('Malformed response header line.');
        }
        
        $header_field_name = array_shift($header_line_parts);

        $lowercase_header_field_name = strtolower($header_field_name);
        $this->headers[$header_field_name] = implode('',$header_line_parts);
        $this->lowercase_headers[$lowercase_header_field_name] = $header_field_name;
        
    }
    
    /**
    * Determines if this response has the supplied header field name.
    * Performs a case-insensitive comparison.
    * eg.
    *   content-type
    * 
    * @param string $header_field_name
    * @throws \Exception //if $header_field_name is not a string
    * @return boolean
    */
    public function hasHeader( $header_field_name ){
        
        if( !is_string($header_field_name) ){
            throw new \Exception('Header field name must be a string.');
        }
        
        return array_key_exists( strtolower($header_field_name), $this->lowercase_headers );
        
    }
    
    
    /**
    * Gets the value for the supplied header field name.
    * Returns NULL if the header field was not found.
    * Performs a case-insensitive comparison.
    * eg.
    *   content-type
    * 
    * @param string $header_field_name
    * @throws \Exception //if $header_field_name is not a string
    * @return boolean
    */
    public function getHeader( $header_field_name ){
        
        $lower_case_header_name = strtolower($header_field_name);
        if( !$this->hasHeader($lower_case_header_name) ){
            return null;
        }else{
            return $this->headers[ $this->lowercase_headers[$lower_case_header_name] ];
        }
        
    }
            
    
    /**
    * Setter for the headers field on this HttpResponseMessage.
    * 
    * @param array $headers
    */
    protected function setHeaders( $headers ){
    
        $this->headers = $headers;
        
    }
    
    
    /**
    * Getter for the headers field on this HttpResponseMessage.
    * 
    * @return array
    */
    public function getHeaders(){
    
        return $this->headers;
        
    }
        
    
    /**
    * Setter for the message_body field on this HttpResponseMessage.
    * 
    * @param string $message_body
    */
    protected function setMessageBody( $message_body ){
    
        $this->message_body = $message_body;
        
    }
    
    
    /**
    * Getter for the message_body field on this HttpResponseMessage.
    * If this message body was gzipped, it will unzip it.
    * 
    * @throws \Exception //if content was gzipped encoded, but 
    * @return string
    */
    public function getUncompressedMessageBody(){
    
        $content_encoding = $this->getHeader('Content-Encoding');
        if( !is_null($content_encoding) && strtolower($content_encoding) == 'gzip' ){
            if( !extension_loaded('zlib') ){
                throw new \Exception('The php zlib extension was not loaded. It is required to inflate this message body.');
            }else{
                return gzinflate( substr($this->message_body, 10) );
            }
        }
        
        return $this->message_body;
        
    }
    
    /**
    * Getter for the message_body field on this HttpResponseMessage.
    * 
    * @return string
    */
    public function getMessageBody(){
    
        return $this->message_body;
        
    }
        
    
    /**
    * Setter for the raw_http_response field on this HttpResponseMessage.
    * 
    * @param string $raw_http_response
    */
    protected function setRawHttpResponse( $raw_http_response ){
    
        $this->raw_http_response = $raw_http_response;
        
    }
    
    
    /**
    * Getter for the raw_http_response field on this HttpResponseMessage.
    * 
    * @return string
    */
    public function getRawHttpResponse(){
    
        return $this->raw_http_response;
        
    }
    
}
