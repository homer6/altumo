<?php

/*
 * This file is part of the Altumo library.
 *
 * (c) Steve Sperandeo <steve.sperandeo@altumo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Altumo\String;
 
   


 /**
 * This class represents a url.
 * 
 * You can use it to parse, validate or build urls.
 * 
 * eg.
 *   $url = 'http://username:password@hostname/path?arg=value#anchor';
 * 
 * 
 * @see http://ca.php.net/manual/en/function.parse-url.php
 * @see http://www.ietf.org/rfc/rfc1738.txt
 * @author Steve Sperandeo <steve.sperandeo@altumo.com>
 */
class Url{

    protected $scheme = null;               //http, https, ftp
    protected $host = null;                 //a domain name or IP address
    protected $port = null;
    protected $user = null;
    protected $password = null;
    protected $path = null;
    protected $query_string = null;         //after the question mark ?
    protected $anchor = null;               //after the hashmark #
    
    protected $valid = false;               //whether this is a valid url
    protected $ip_host = false;             //whether the hostname is an ip address
    
    
    /**
    * Constructor. Optionally pass a full url string.
    * 
    * @param mixed $full_url_string
    * @throws \Exception                    //if $full_url_string is provided 
    *                                         URL is invalid.
    * @return Url
    */
    public function __construct( $full_url_string = null ){
                
        if( !is_null($full_url_string) ){
            if( is_string($full_url_string) ){
                $this->setFullUrl($url);
            }else{
                throw new \Exception( 'Url must be a string if it\'s provided.' );
            }
        }
        
    }
    
    
    /**
    * Sets all internal values to default (null)
    * 
    */
    protected function clearUrl(){

        $scheme = null;
        $host = null;
        $port = null;
        $user = null;
        $password = null;
        $path = null;
        $query_string = null;

        $valid = false;

    }
    
    
    /**
    * Sets all of the internal values from a full URL.
    * 
    * @param string $url
    * @throws \Exception                    //if URL is invalid
    */
    public function setFullUrl( $url ){
        
        try{
            
            $this->clearUrl();
            
            $result = parse_url($url);
            if( $result === false ){
                throw new \Exception( 'Malformed URL.' );
            }

            if( array_key_exists('scheme', $result) && array_key_exists('host', $result) ){
                $this->setScheme( $result['scheme'] );
                $this->setHost( $result['host'] );
            }else{
                throw new \Exception( 'Invalid URL. Both a schema and host are required.' );
            }
            
            if( array_key_exists('port', $result) ){
                $this->setPort( $result['port'] );
            }
            
            if( array_key_exists('user', $result) ){
                $this->setUser( $result['user'] );
            }
            
            if( array_key_exists('pass', $result) ){
                $this->setPassword( $result['pass'] );
            }
            
            if( array_key_exists('path', $result) ){
                $this->setPath( $result['path'] );
            }
            
            if( array_key_exists('query', $result) ){
                $this->setQueryString( $result['query'] );
            }
            
            if( array_key_exists('fragment', $result) ){
                $this->setAnchor( $result['fragment'] );
            }
            
            $this->setValid(true);
                        
        }catch( \Exception $e ){
                        
            $this->clearUrl();
            throw $e;
            
        }
        
    }
    
    
    /**
    * Determines if the supplied URL is a valid one.
    * 
    * @return boolean
    */
    static public function isValidUrl( $url ){
        
        try{
            
            $url_object = new \Altumo\String\Url( $url );
            return true;
            
        }catch( \Exception $e ){
            
            return false;
            
        }

    }
    
    
    
    
    /**
    * Setter for the scheme field on this Url.
    * This also changes the scheme to lowercase.
    * 
    * @param string $scheme
    * @throws \Exception                    //if scheme is empty
    * @throws \Exception                    //if scheme is not a string
    * @throws \Exception                    //if scheme is not supported
    */
    public function setScheme( $scheme ){
    
        if( is_string($scheme) ){
            throw new \Exception( 'The scheme must be a string.' );
        }
    
        if( empty($scheme) ){
            throw new \Exception( 'The scheme cannot be empty.' );
        }
        
        $lower_scheme = strtolower($scheme);        
        $allowed_schemes = array(
            'http',
            'https',
            'ftp'//,
            //'mailto'
        );
        if( !in_array($lower_scheme,$allowed_schemes) ){
            throw new \Exception( 'The scheme is not supported.' );
        }
        
        $this->scheme = $lower_scheme;
        
    }
    
    
    /**
    * Getter for the scheme field on this Url.
    * 
    * @return string
    */
    public function getScheme(){
    
        return $this->scheme;
        
    }
    
    
    /**
    * Determines if there's a scheme field on this Url.
    * 
    * @return boolean
    */
    public function hasScheme(){
    
        return !is_null($this->scheme);
        
    }
        
    
    /**
    * Setter for the host field on this Url.
    * 
    * @param string $host
    * @throws \Exception                    //if this hostname is invalid
    */
    public function setHost( $host ){
        
        //match a domain name (but not an IP)
            if( preg_match('/^((([a-zA-Z0-9]|([a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]))\.)*([a-zA-Z]|([a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9])))$/', $host) ){
                $this->host = $host;
                $this->ip_host = false;
                return;
            }
        
        //match an IP
            if( preg_match('/^([0-9]{1,3}(\.[0-9]{1,3}){3})$/', $host) ){
                $this->host = $host;
                $this->ip_host = true;
                return;
            }
        
        throw new \Exception( 'Invalid URL hostname.' );
        
        
    }
    
    
    /**
    * Getter for the host field on this Url.
    * 
    * @return string
    */
    public function getHost(){
    
        return $this->host;
        
    }
    
    
    /**
    * Determines if there's a host field on this Url.
    * 
    * @return boolean
    */
    public function hasHost(){
    
        return !is_null($this->host);
        
    }
        
    
    /**
    * Setter for the port field on this Url.
    * 
    * @param integer $port
    * @throws \Exception                    //if this port is invalid
    */
    public function setPort( $port ){
        
        if( is_integer($port) ){
            $port = (string)$port;
        }

        if( preg_match('/^[0-9]+$/', $port) ){
            $this->port = (integer)$port;
        }else{
            throw new \Exception( 'Invalid port.' );
        }
        
    }
    
    
    /**
    * Getter for the port field on this Url.
    * 
    * @return integer
    */
    public function getPort(){
    
        return $this->port;
        
    }
    
    
    /**
    * Determines if there's a port field on this Url.
    * 
    * @return boolean
    */
    public function hasPort(){
    
        return !is_null($this->port);
        
    }
    
    
    /**
    * Returns the hostname and the port (if supplied) as a string.
    * 
    *   eg. hostname:80
    *       hostname
    * 
    * @throws \Exception                    //if this URL doesn't have a host
    * @return string
    */
    public function getHostPort(){
        
        if( !$this->hasHost() ){
            throw new \Exception( 'This URL doesn\'t have a host.' );
        }
        
        if( $this->hasPort() ){
            return $this->getHost() . ':' . $this->getPort();
        }else{
            return $this->getHost();
        }
        
    }
    
    
    /**
    * Determines if this hostname is an IP address.
    * 
    * @return boolean
    */
    public function isHostAnIpAddress(){
    
        return $this->ip_host;
        
    }
            
    
    /**
    * Setter for the user field on this Url.
    * 
    * @param string $user
    * @throws \Exception                    //if this user is invalid
    */
    public function setUser( $user ){
    
        if( preg_match('#^((([-$_\.+\*\!\'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;\?&=])+)$#', $user) ){
            $this->user = $user;
        }else{
            throw new \Exception( 'Invalid user format.' );
        }
        
    }
    
    
    /**
    * Getter for the user field on this Url.
    * 
    * @return string
    */
    public function getUser(){
    
        return $this->user;
        
    }

    
    /**
    * Determines if there's a user field on this Url.
    * 
    * @return boolean
    */
    public function hasUser(){
    
        return !is_null($this->user);
        
    }
        
    
    /**
    * Setter for the password field on this Url.
    * 
    * @param string $password
    * @throws \Exception                    //if this password is invalid
    */
    public function setPassword( $password ){
    
        if( preg_match('#^((([-$_\.+\*\!\'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;\?&=])+)$#', $password) ){
            $this->password = $password;
        }else{
            throw new \Exception( 'Invalid user format.' );
        }
        
    }
    
    
    /**
    * Getter for the password field on this Url.
    * 
    * @return string
    */
    public function getPassword(){
    
        return $this->password;
        
    }

    
    /**
    * Determines if there's a password field on this Url.
    * 
    * @return boolean
    */
    public function hasPassword(){
    
        return !is_null($this->password);
        
    }

    
    /**
    * Returns the username and the password (if supplied) as a string.
    * 
    *   eg. username:password
    *       username
    * 
    * @throws \Exception                    //if this URL doesn't have a user
    * @return string
    */
    public function getLogin(){
        
        if( !$this->hasLogin() ){
            throw new \Exception( 'This URL doesn\'t have a login.' );
        }
        
        if( $this->hasPassword() ){
            return $this->getUser() . ':' . $this->getPassword();
        }else{
            return $this->getUser();
        }
        
    }

    
    /**
    * Determines if this URL has a user and/or password.
    * 
    * @return boolean
    */
    public function hasLogin(){
        
        return $this->hasUser();
        
    }
    
    
    /**
    * Setter for the path field on this Url.
    * 
    * @param string $path
    * @throws \Exception                    //if this path is invalid
    */
    public function setPath( $path ){
    
        if( preg_match('#^(((([-$_\.+\*\!\'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;:@&=])+)(\/((([-$_\.+\*\!\'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;:@&=])+))*)$#', $path) ){
            $this->path = $path;
        }else{
            throw new \Exception( 'Invalid path format.' );
        }
        
    }
    
    
    /**
    * Getter for the path field on this Url.
    * 
    * @return string
    */
    public function getPath(){
    
        return $this->path;
        
    }
        

    /**
    * Determines if there's a path field on this Url.
    * 
    * @return boolean
    */
    public function hasPath(){
    
        return !is_null($this->path);
        
    }
    
    
    /**
    * Setter for the query_string field on this Url.
    * 
    * @param string $query_string
    * @throws \Exception                    //if this query_string is invalid
    */
    public function setQueryString( $query_string ){
    
        if( preg_match('#^((([-$_\.+\*\!\'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;:@&=])+)$#', $query_string) ){
            $this->query_string = $query_string;
        }else{
            throw new \Exception( 'Invalid query string format.' );
        }
                
    }
     
   
    /**
    * Getter for the query_string field on this Url.
    * 
    * @return string
    */
    public function getQueryString(){
    
        return $this->query_string;
        
    }

    
    /**
    * Determines if there's a query_string field on this Url.
    * 
    * @return boolean
    */
    public function hasQueryString(){
    
        return !is_null($this->query_string);
        
    }
        
    
    /**
    * Setter for the anchor field on this Url.
    * 
    * @param string $anchor
    */
    public function setAnchor( $anchor ){
    
        $this->anchor = $anchor;
        
    }
    
    
    /**
    * Getter for the anchor field on this Url.
    * 
    * @return string
    */
    public function getAnchor(){
    
        return $this->anchor;
        
    }
    
    
    /**
    * Determines if there's an anchor field on this Url.
    * 
    * @return boolean
    */
    public function hasAnchor(){
    
        return !is_null($this->anchor);
        
    }
        
    
    /**
    * Setter for the valid field on this Url.
    * 
    * @param boolean $valid
    */
    protected function setValid( $valid ){
    
        $this->valid = $valid;
        
    }
    
    
    /**
    * Getter for the valid field on this Url.
    * 
    * @return boolean
    */
    public function isValid(){
    
        return $this->valid;
        
    }
    
    
    /**
    * Returns this full URL as a string.
    * 
    * @throws \Exception                    //if this URL is not valid
    * @return string
    */
    public function getUrl(){
        
        if( !$this->isValid() ){
            throw new \Exception( 'This URL is invalid.' );
        }
        
        $url_string = $this->getScheme();
        $url_string .= '://'; 
        if( $this->hasLogin() ){
            $url_string .= $this->getLogin() . '@';
        }
        $url_string .= $this->getHostPort();
        
        
        
        
    }
    
    
}

/*
    Regex Patterns derived from the RFC:


            @see http://www.ietf.org/rfc/rfc1738.txt
        
            login          = ((((([-$_\.+\*\!'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;\?&=])+):((([-$_\.+\*\!'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;\?&=])+)@)((((([a-zA-Z0-9]|([a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]))\.)*([a-zA-Z]|([a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9])))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]+)?))
            hostport       = ((((([a-zA-Z0-9]|([a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]))\.)*([a-zA-Z]|([a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9])))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]+)?)
            host           = (((([a-zA-Z0-9]|([a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]))\.)*([a-zA-Z]|([a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9])))|([0-9]{1,3}(\.[0-9]{1,3}){3}))
            hostname       = ((([a-zA-Z0-9]|([a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]))\.)*([a-zA-Z]|([a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9])))
            domainlabel    = ([a-zA-Z0-9]|([a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]))
            toplabel       = ([a-zA-Z]|([a-zA-Z][-a-zA-Z0-9]*[a-zA-Z0-9]))
            alphadigit     = [a-zA-Z0-9]
            hostnumber  (ipv4)     
                           = ([0-9]{1,3}(\.[0-9]{1,3}){3})
                        (ipv6)
                           = @see http://forums.dartware.com/viewtopic.php?t=452
                           = /^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$/
                           
            port           = [0-9]+
            user           = ((([-$_\.+\*\!'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;\?&=])+)
            password       = ((([-$_\.+\*\!'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;\?&=])+)
            
            urlpath        = ([-$_\.+\*\!'\(\),a-zA-Z0-9;\/\?:@&=]|%[0-9A-Fa-f]{2})+
            
            
            alpha          = [a-zA-Z]
            digit          = [0-9]
            safe           = [-$_\.+]
            extra          = [\*\!'\(\),]
            national       = [{}\|\\^~\[\]\`]
            punctuation    = [<>#%"]


            reserved       = [;\/\?:@&=]
            hex            = [0-9A-Fa-f]
            escape         = %[0-9A-Fa-f]{2}

            unreserved     = [-$_\.+\*\!'\(\),a-zA-Z0-9]
            uchar          = ([-$_\.+\*\!'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})
            xchar          = ([-$_\.+\*\!'\(\),a-zA-Z0-9;\/\?:@&=]|%[0-9A-Fa-f]{2})
            digits         = [0-9]+

            
            ; HTTP

            httpurl        = "http://" hostport [ "/" hpath [ "?" search ]]
            hpath          = (((([-$_\.+\*\!'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;:@&=])+)(\/((([-$_\.+\*\!'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;:@&=])+))*)
            hsegment       = ((([-$_\.+\*\!'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;:@&=])+)
            search         = ((([-$_\.+\*\!'\(\),a-zA-Z0-9]|%[0-9A-Fa-f]{2})|[;:@&=])+)
            

            
            Omg... more RFC... 
            I haven't implemented this one, it's newer
                       
            http://tools.ietf.org/html/rfc3986
            
*/

