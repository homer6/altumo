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
* This is an HTTP Client class.
* 
* Represents an outgoing http request using the curl library.
* Currently only supports GET, POST, PUT or DELETE.
* 
* It can be used to make the request through TOR:
* @see http://www.torproject.org/index.html.en
* 
* @see http://www.torproject.org/docs/debian.html.en  to install the 
* required TOR service to make anonymous requests available.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class OutgoingHttpRequest{

    protected $url = '';
    //eg. http://www.google.com
    
    protected $parameters = array();
    //associative array of all of the URL parameters
    
    protected $message_body = null;  
    //string of the http message body (if there is one)
    
    protected $anonymous = false;  
    //if true, uses the onion network to hide the request sender (you)
    
    protected $user_agent = null;  
    //if null, uses a random real world user agent for anonymous requests
    
    protected $request_method = 'GET';
    //the http request method that will be used when the request is sent
    
    protected $referrer = null;
    //if not null, sets this string as the referrer for anonymous requests
    
    protected $verify_ssl_peer = null;
    //if not null, sets this boolean value to CURLOPT_SSL_VERIFYPEER
    
    protected $cookie = null;
    //if not null, sets this string to CURLOPT_COOKIE
    
    protected $curl_info = array();
    //the values from curl_getinfo()
    
    protected $cookie_filename = null;
    //filename of the cookie jar (the file where all the cookies are stored 
    //for this request)
    
    protected $curl_handle = null;
    //the resource to this curl object
    
    protected $ssl_cert_data = null;
    
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_METHOD_DELETE = 'DELETE';
        
    /**
    * Contstructor for this Curl Request
    * All values passed in the parameters array will be url encoded.
    * 
    * @param string $url
    * @param array $url_parameters
    * @throws \Exception if the CURL library is not accessible.
    * @return OutgoingHttpRequest
    */
    public function __construct( $url, $url_parameters = array() ){
        
        //checks if CURL is loaded
            if( !extension_loaded('curl') ){               
                throw new \Exception('The PHP extension CURL must be loaded before making requests.');
            }
            
        //sets the parameters
            if( !empty($url_parameters) ){
                $this->setParameters($url_parameters);    
            }
            
        //sets the url
            $this->setUrl( $url );
        
    }
    
    /**
    * Sends the HTTP Request and returns the response as a string.
    * 
    * @param boolean $return_response_header
    * @param boolean $populate_curl_info
    * @throws \Exception on error.
    * @return string
    */
    public function send( $return_response_header = false, $populate_curl_info = false ){
        
        //get the url, given the request method (contains the parametes as GET parameters, if this is a GET request)
            $full_url = $this->getFullUrl();        
        
        //prepare the request        
            $curl_handle = $this->getCurlHandle();
            curl_setopt( $curl_handle, CURLOPT_RETURNTRANSFER, 1 );            
            if( $this->isPostRequest() ){
                curl_setopt( $curl_handle, CURLOPT_POST, 1 );
                curl_setopt( $curl_handle, CURLOPT_POSTFIELDS, $this->getMessageBody() );
            }else{
                curl_setopt( $curl_handle, CURLOPT_POST, 0 );
            }
            
            if( $this->isPutRequest() ){
                curl_setopt( $curl_handle, CURLOPT_CUSTOMREQUEST, "PUT" );
                $message_body = $this->getMessageBody();
                $temp_file = tmpfile();
                fwrite($temp_file, $message_body);
                fseek($temp_file, 0);
                curl_setopt( $curl_handle, CURLOPT_PUT, 1 );                
                curl_setopt( $curl_handle, CURLOPT_INFILE, $temp_file );
                curl_setopt( $curl_handle, CURLOPT_INFILESIZE, strlen($message_body));
            }
            
            if( $this->isDeleteRequest() ){
                curl_setopt( $curl_handle, CURLOPT_CUSTOMREQUEST, "DELETE" );
            }
            
            
            curl_setopt( $curl_handle, CURLOPT_URL, $full_url );
                
            $verify_ssl_peer = $this->getVerifySslPeer();  
            if( !is_null($verify_ssl_peer) ){
                curl_setopt( $curl_handle, CURLOPT_SSL_VERIFYPEER, $verify_ssl_peer );
            }
            
            curl_setopt( $curl_handle, CURLOPT_HEADER, $return_response_header );
            
            if( $populate_curl_info ){
                curl_setopt( $curl_handle, CURLINFO_HTTP_CODE, true );
                curl_setopt( $curl_handle, CURLINFO_FILETIME, true );
                curl_setopt( $curl_handle, CURLINFO_TOTAL_TIME, true );
                curl_setopt( $curl_handle, CURLINFO_NAMELOOKUP_TIME, true );
                curl_setopt( $curl_handle, CURLINFO_CONNECT_TIME, true );
                curl_setopt( $curl_handle, CURLINFO_PRETRANSFER_TIME, true );
                curl_setopt( $curl_handle, CURLINFO_STARTTRANSFER_TIME, true );
                curl_setopt( $curl_handle, CURLINFO_REDIRECT_TIME, true );
                curl_setopt( $curl_handle, CURLINFO_SIZE_UPLOAD, true );
                curl_setopt( $curl_handle, CURLINFO_SIZE_DOWNLOAD, true );
                curl_setopt( $curl_handle, CURLINFO_SPEED_DOWNLOAD, true );
                curl_setopt( $curl_handle, CURLINFO_SPEED_UPLOAD, true );
                curl_setopt( $curl_handle, CURLINFO_HEADER_SIZE, true );
                curl_setopt( $curl_handle, CURLINFO_HEADER_OUT, true );
                curl_setopt( $curl_handle, CURLINFO_REQUEST_SIZE, true );
                curl_setopt( $curl_handle, CURLINFO_SSL_VERIFYRESULT, true );
                curl_setopt( $curl_handle, CURLINFO_CONTENT_LENGTH_DOWNLOAD, true );
                curl_setopt( $curl_handle, CURLINFO_CONTENT_LENGTH_UPLOAD, true );
                curl_setopt( $curl_handle, CURLINFO_CONTENT_TYPE, true );
                //curl_setopt( $curl_handle, CURLOPT_COOKIELIST, true );
            }else{
                curl_setopt( $curl_handle, CURLINFO_HTTP_CODE, false );
                curl_setopt( $curl_handle, CURLINFO_FILETIME, false );
                curl_setopt( $curl_handle, CURLINFO_TOTAL_TIME, false );
                curl_setopt( $curl_handle, CURLINFO_NAMELOOKUP_TIME, false );
                curl_setopt( $curl_handle, CURLINFO_CONNECT_TIME, false );
                curl_setopt( $curl_handle, CURLINFO_PRETRANSFER_TIME, false );
                curl_setopt( $curl_handle, CURLINFO_STARTTRANSFER_TIME, false );
                curl_setopt( $curl_handle, CURLINFO_REDIRECT_TIME, false );
                curl_setopt( $curl_handle, CURLINFO_SIZE_UPLOAD, false );
                curl_setopt( $curl_handle, CURLINFO_SIZE_DOWNLOAD, false );
                curl_setopt( $curl_handle, CURLINFO_SPEED_DOWNLOAD, false );
                curl_setopt( $curl_handle, CURLINFO_SPEED_UPLOAD, false );
                curl_setopt( $curl_handle, CURLINFO_HEADER_SIZE, false );
                curl_setopt( $curl_handle, CURLINFO_HEADER_OUT, false );
                curl_setopt( $curl_handle, CURLINFO_REQUEST_SIZE, false );
                curl_setopt( $curl_handle, CURLINFO_SSL_VERIFYRESULT, false );
                curl_setopt( $curl_handle, CURLINFO_CONTENT_LENGTH_DOWNLOAD, false );
                curl_setopt( $curl_handle, CURLINFO_CONTENT_LENGTH_UPLOAD, false );
                curl_setopt( $curl_handle, CURLINFO_CONTENT_TYPE, false );
                //curl_setopt( $curl_handle, CURLOPT_COOKIELIST, false ); throws a warning on newer versions of curl.
            }
            
            //use a secutiy certificate if data is set
                if( $this->getSslCertificateData() != null ){
                    curl_setopt( $curl_handle, CURLOPT_SSLCERT, $this->getSslCertificateAsTempFilePath() );
                }
            
            
            
            //set the user agent and proxy through local tor service if this is an annonymous request
                if( $this->hasUserAgent() ){
                    
                    $user_agent = $this->getUserAgent(); 
                    curl_setopt( $curl_handle, CURLOPT_USERAGENT, $user_agent );
                    
                }else{
                    
                    if( $this->isAnonymous() ){
                                    
                        //make the anonymous HTTP request                    
                            curl_setopt( $curl_handle, CURLOPT_PROXY, '127.0.0.1:9050' );
                            curl_setopt( $curl_handle, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5 );
                            
                            //set the user agent and referrer
                                $user_agent = $this->getRandomUserAgent();
                                
                                curl_setopt( $curl_handle, CURLOPT_USERAGENT, $user_agent );
                                if( $this->hasReferrer() ){
                                    curl_setopt( $curl_handle, CURLOPT_REFERER, $this->getReferrer() );
                                }
                                
                            curl_setopt( $curl_handle, CURLOPT_FOLLOWLOCATION, 1 );
                            curl_setopt( $curl_handle, CURLOPT_TIMEOUT, 300 );
                                                
                    }
                    
                }
                
            //set additional headers
                if( $this->hasHeaders() ){
                    curl_setopt( $curl_handle, CURLOPT_HTTPHEADER, $this->getHeadersAsPlainArray() );                
                }
            
            
            //set the cookie
                if( $cookie = $this->getCookie() ){
                    curl_setopt( $curl_handle, CURLOPT_COOKIE, $cookie );
                }
                
            //saves the cookies into the cookiejar
                $cookie_filename = $this->getCookieFilename();
                curl_setopt( $curl_handle, CURLOPT_COOKIEFILE, $cookie_filename );
                curl_setopt( $curl_handle, CURLOPT_COOKIEJAR, $cookie_filename );
            
            
        //make the request and return the result
            $response = curl_exec($curl_handle);         
            if( $populate_curl_info ){
                $this->setCurlInfo( curl_getinfo($curl_handle) );
            }
            if( $response === false ){
                $error_message = curl_error($curl_handle);
                //curl_close($curl_handle);
                throw new \Exception('CURL Request Failed: ' . $error_message );
            }else{
                //curl_close($curl_handle);
            }
            
            if( isset($temp_file) ){
                fclose($temp_file);
            }
            
            return $response;
                 
    }
    
    /**
    * Sends the HTTP Request and returns the response as a HttpResponseMessage.
    * 
    * @param boolean $populate_curl_info
    * @throws \Exception on error.
    * @return \Altumo\Http\HttpResponseMessage
    */
    public function sendAndGetResponseMessage( $populate_curl_info = false ){
        
        $response = $this->send( true, $populate_curl_info );
        return new \Altumo\Http\HttpResponseMessage($response);
            
    }
    
    
    /**
    * Sets the URL parameters for this request.
    * This WILL REPLACE the any pre-existing parameters.
    * 
    * @param array $parameters
    */
    public function setParameters( $parameters ){
        
        if( !is_array( $parameters ) ){
            throw new \Exception('Parameters expects an array.');
        }
        $this->parameters = $parameters;
        
    }
    
    /**
    * Sets the message body for POST, PUT or DELETE requests. This will replace
    * any parameters that have been set.
    * 
    * @param string $message_body
    * @throws \Exception                    //if $message_body is not a string
    */
    public function setMessageBody( $message_body ){
        
        if( !is_string($message_body) ){
            throw new \Exception('Message body is expected to be a string.');
        }
        $this->message_body = $message_body;
        
    }
    
    
    /**
    * Getter for the message_body field on this OutgoingHttpRequest.
    * Returns null if there is no message body.
    * 
    * @return string
    */
    public function getMessageBody(){
    
        return $this->message_body;
        
    }
    
    
    /**
    * Accessor for the parameters
    *
    * @return array 
    */
    public function getParameters(){
        
        return $this->parameters;
        
    }
    
        
    /**
    * Determines if this request has get parameters.
    * 
    * @return boolean
    */
    public function hasParameters(){
        
        return !empty($this->parameters);
        
    }
    
    
    /**
    * Sets the message body to a urlencoded encoded string from the provided
    * array.
    * 
    * @param array $post_parameters         //an associative array of parameters
    *                                         that will be urlencoded and replace
    *                                         the message body
    * 
    * @throws \Exception                    //if $post_parameters is not an array
    */
    public function setPostParameters( $post_parameters ){
        
        if( !is_array($post_parameters) ){
            throw new \Exception('POST parameters must be an array.');
        }
        
        $this->setMessageBody( http_build_query( $parameters ) );

    }
    
    
    /**
    * Sets the URL for this request.
    * 
    * @param string $url
    */
    public function setUrl( $url ){
        
        if( !is_string($url) ){
            throw new \Exception('First parameters expects a string.');
        }
        $this->url = $url;
        
    }
    
    /**
    * Gets the URL for this request. (without the get parameters)
    * 
    * @retrun string
    */
    public function getUrl(){
        
        return $this->url;
        
    }
    
    
    /**
    * Setter for the anonymous field on this OutgoingHttpRequest.
    * 
    * @param boolean $anonymous
    */
    public function setAnonymous( $anonymous ){
    
        if( !is_bool($anonymous) ){
            throw new \Exception('Parameter must be boolean.');
        }
        $this->anonymous = $anonymous;
        
    }
    
    
    /**
    * Getter for the anonymous field on this OutgoingHttpRequest.
    * 
    * @return boolean
    */
    public function getAnonymous(){
    
        return $this->anonymous;
        
    }
    
    
    /**
    * Determines if this request will go through the onion network.
    * 
    * @return boolean
    */
    public function isAnonymous(){
    
        return $this->anonymous;
        
    }
    
    
    /**
    * Setter for the user_agent field on this OutgoingHttpRequest.
    * 
    * @param string $user_agent
    */
    public function setUserAgent( $user_agent ){
    
        $this->user_agent = $user_agent;
        
    }
    
    
    /**
    * Getter for the user_agent field on this OutgoingHttpRequest.
    * 
    * @return string
    */
    public function getUserAgent(){
    
        return $this->user_agent;
        
    }        
    
    /**
    * Determines if this request has a customer User Agent set
    * 
    * @return boolean
    */
    public function hasUserAgent(){
    
        return !is_null($this->user_agent);
        
    }    
    
    
    /**
    * Gets a random real-world user agent.
    * 
    *   eg.
    *       Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0)
    * 
    * @return string
    */
    public function getRandomUserAgent(){
    
        $agents = array();
        $agents[] = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6';
        $agents[] = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/531.22.7 (KHTML, like Gecko) Version/4.0.5 Safari/531.22.7';
        $agents[] = 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.2.6) Gecko/20100625 Firefox/3.6.6';
        $agents[] = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0)';
        $agents[] = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)';
        return $agents[array_rand($agents)];
        
    }
    
    /**
    * Gets the url with the combined url-encoded get parameters.
    * 
    * @return string
    */
    public function getFullUrl(){
        
        //get return the url without the get parameters if it's a post request
            if( $this->getRequestMethod() !== OutgoingHttpRequest::HTTP_METHOD_GET ) return $this->getUrl();
        
        $parameters = $this->getParameters();
        if( empty($parameters) ){
            return $this->getUrl();
        }else{
            return $this->getUrl() . '?' . http_build_query( $parameters );
        }        
            
    }
    
    
    /**
    * Setter for the request_method field on this OutgoingHttpRequest.
    * 
    * @param string $request_method  // OutgoingHttpRequest::HTTP_METHOD_GET or OutgoingHttpRequest::HTTP_METHOD_POST
    */
    public function setRequestMethod( $request_method ){
    
        //validate the request method
            $allowed_request_methods = array(
                OutgoingHttpRequest::HTTP_METHOD_GET,
                OutgoingHttpRequest::HTTP_METHOD_POST,
                OutgoingHttpRequest::HTTP_METHOD_PUT,            
                OutgoingHttpRequest::HTTP_METHOD_DELETE            
            );
            if( !in_array( $request_method, $allowed_request_methods ) ){
                throw new \Exception('Unsupported request method.');
            }
            
        $this->request_method = $request_method;
        
    }
    
    
    /**
    * Getter for the request_method field on this OutgoingHttpRequest.
    * 
    * @return string
    */
    public function getRequestMethod(){
    
        return $this->request_method;
        
    }
    
    
    /**
    * Determines if this is a HTTP POST request.
    * 
    * @return boolean
    */
    public function isPostRequest(){
        
        return ( $this->getRequestMethod() === OutgoingHttpRequest::HTTP_METHOD_POST );
        
    }
    
    
    /**
    * Determines if this is a HTTP GET request.
    * 
    * @return boolean
    */
    public function isGetRequest(){
        
        return ( $this->getRequestMethod() === OutgoingHttpRequest::HTTP_METHOD_GET );
        
    }
    
    
    /**
    * Determines if this is a HTTP PUT request.
    * 
    * @return boolean
    */
    public function isPutRequest(){
        
        return ( $this->getRequestMethod() === OutgoingHttpRequest::HTTP_METHOD_PUT );
        
    }
    
    
    /**
    * Determines if this is a HTTP DELETE request.
    * 
    * @return boolean
    */
    public function isDeleteRequest(){
        
        return ( $this->getRequestMethod() === OutgoingHttpRequest::HTTP_METHOD_DELETE );
        
    }
    
    
    
    /**
    * Setter for the referrer field on this OutgoingHttpRequest.
    * 
    * @param string $referrer
    */
    public function setReferrer( $referrer ){
    
        $this->referrer = $referrer;
        
    }
    
    
    /**
    * Getter for the referrer field on this OutgoingHttpRequest.
    * 
    * @return string
    */
    public function getReferrer(){
    
        return $this->referrer;
        
    }
        
    /**
    * Determines if this Referrer has been set.
    * 
    * @return boolean
    */
    public function hasReferrer(){
    
        return !is_null($this->referrer);
        
    }
        
    
    /**
    * Setter for the verify_ssl_peer field on this OutgoingHttpRequest.
    * 
    * @param boolean $verify_ssl_peer
    * @throws \Exception if $verify_ssl_peer is not a boolean
    */
    public function setVerifySslPeer( $verify_ssl_peer ){
    
        if( !is_bool($verify_ssl_peer) ){
            throw new \Exception('Boolean parameter expected.');
        }
        $this->verify_ssl_peer = $verify_ssl_peer;
        
    }
    
    
    /**
    * Getter for the verify_ssl_peer field on this OutgoingHttpRequest.
    * 
    * @return boolean
    */
    public function getVerifySslPeer(){
    
        return $this->verify_ssl_peer;
        
    }
    
    
    /**
    * Setter for the cookie field on this OutgoingHttpRequest.
    * 
    * @param string $cookie
    */
    public function setCookie( $cookie ){
    
        $this->cookie = $cookie;
        
    }
    
    
    /**
    * Getter for the cookie field on this OutgoingHttpRequest.
    * 
    * @return string
    */
    public function getCookie(){
    
        return $this->cookie;
        
    }
    
    
    /**
    * Setter for the curl_info field on this OutgoingHttpRequest.
    * 
    * @param array $curl_info
    */
    public function setCurlInfo( $curl_info ){
    
        $this->curl_info = $curl_info;
        
    }
    
    
    /**
    * Getter for the curl_info field on this OutgoingHttpRequest.
    * 
    * @return array
    */
    public function getCurlInfo(){
    
        return $this->curl_info;
        
    }
    
    /**
    * Setter for the headers field on this OutgoingHttpRequest.
    * 
    * @param array $headers
    */
    public function setHeaders( $headers ){
    
        $this->headers = $headers;
        
    }
    
    
    /**
    * Getter for the headers field on this OutgoingHttpRequest.
    * 
    * @return array
    */
    public function getHeaders(){
    
        return $this->headers;
        
    }
    
    /**
    * Gets the headers in an array suitable to be passed into CURL (non-associative array)
    * 
    * @return array
    */
    public function getHeadersAsPlainArray(){
    
        $plain_array = array();
        foreach( $this->headers as $key => $value ){
            $plain_array[] = "$key: $value";
        }        
        return $plain_array;
        
    }
    
    /**
    * Determines if this request has extra headers
    * 
    * @return boolean
    */
    public function hasHeaders(){
    
        return ( !empty($this->headers) == true );
        
    }
        
    /**
    * Adds a single http header to this request.
    * 
    * @param string $name
    * @param string $value
    * @throws \Exception if $name or $value are not strings
    */
    public function addHeader( $name, $value ){
        
        if( !is_string($name) || !is_string($value) ){
            throw new \Exception('Header name and value must both be strings.');
        }
        $this->headers[$name] = $value;
        
    }
    
    /**
    * Setter for the cookie_filename field on this OutgoingHttpRequest.
    * 
    * @param string $cookie_filename
    */
    public function setCookieFilename( $cookie_filename ){
    
        $this->cookie_filename = $cookie_filename;
        
    }
    
    /**
    * Gets the filename that contains the cookies (cookiejar).
    * Creates a temp file if a specific file is not set.
    * 
    * @return string
    */
    public function getCookieFilename(){
        
        if( is_null($this->cookie_filename) ){
            $this->cookie_filename = tempnam( sys_get_temp_dir(), 'CookieJar_' );
        }
        
        return $this->cookie_filename;
    }
    
    
    /**
    * Setter for the curl_handle field on this OutgoingHttpRequest.
    * 
    * @param string $curl_handle
    */
    public function setCurlHandle( $curl_handle ){
    
        $this->curl_handle = $curl_handle;
        
    }
    
    
    /**
    * Getter for the curl_handle field on this OutgoingHttpRequest.
    * 
    * @return string
    */
    public function getCurlHandle(){
    
        if( is_null($this->curl_handle) ){
            $this->curl_handle = curl_init();
            curl_setopt( $this->curl_handle, CURLOPT_FRESH_CONNECT, true );
            curl_setopt( $this->curl_handle, CURLOPT_COOKIESESSION, true );
        }
        
        return $this->curl_handle;
        
    }
    
    /**
    * Closes the curl handle
    * 
    */
    protected function closeCurlHandle(){
        
        if( !is_null($this->curl_handle) ){
            if( is_resource($this->curl_handle) ){
                curl_close($this->curl_handle);
            }
            $this->curl_handle = null;
        }
        
    }
    
    /**
    * Sets the SSL certificate to use for the request.
    * 
    * @param mixed $ssl_cert_data should be the contents of a PEM ssl certificate file.
    * @returns OutgoingHttpRequest
    */
    public function setSslCertificateData( $ssl_cert_data ){
        
        $this->ssl_cert_data = $ssl_cert_data;
        
        return $this;
        
    }
    
    
    /**
    * Get the path to an SSL Cert file to use on the next request.
    **/
    public function getSslCertificateData(){
        
        return $this->ssl_cert_data;
        
    }
    
    /**
    * put your comment there...
    * 
    * @param string $temp_path
    */
    private function getSslCertificateAsTempFilePath( $temp_path = null ){
        
        $ssl_cert_data = $this->getSslCertificateData();
        
        if( is_null( $temp_path ) ) $temp_path = sys_get_temp_dir();
        
        $temp_filename = $temp_path . '/cws_pem_' . sha1( $ssl_cert_data );
        
        if( is_readable( $temp_filename ) ){
            return $temp_filename;
        }
        
        if( file_put_contents( $temp_filename, $this->getSslCertificateData()) === false ){
            throw new Exception( 'Unable to write to temporary certificate file.' );
        }
        
    }
    
    
    /**
    * Deletes the temp cookie file created by this request.
    * 
    */
    public function __destruct(){
          
        $this->closeCurlHandle();
        if( file_exists($this->getCookieFilename()) ){
            unlink( $this->getCookieFilename() );
        }        
               
    }
    
    

    
}
