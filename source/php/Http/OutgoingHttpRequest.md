Description
-----------

OutgoingHttpRequest is a server-side HTTP Client class.  It represents an 
outgoing http request using the curl library. Currently only supports GET, 
POST, PUT or DELETE.


Sample Usage
------------

This try block makes a POST request to a remote resource with curl, returns the 
response (with http headers and curl info).  It also demostrates how to return
the response wrapped in an object that allows you to retrieve parts of the 
response.

    try{
        
        //load class autoloader
            require_once( __DIR__ . '/loader.php' );  //you should ensure that is is the correct path for this file
    
        //make the response; return the response with the headers
            $client = new \Altumo\Http\OutgoingHttpRequest( 'http://www.domain.com/checkuser.php', array(
                'username' => 'user',
                'password' => 'pass',
                'submit' => ' Login '
            ));
            $client->setRequestMethod( \Altumo\Http\OutgoingHttpRequest::HTTP_METHOD_POST );
            
        //send the request (with optional arguments for debugging)           
            //the first true will return the response headers
            //the second true will turn on curl info so that it can be retrieved later
            $response = $client->send( true, true );
                
        //output the response and curl info
            \Altumo\Utils\Debug::dump( $response, $client->getCurlInfo() );
                    
        //alternatively, you can get the response wrapped in an object that allows you to retrieve parts of the response
            $http_response =  $client->sendAndGetResponseMessage( true );
            $status_code = $http_response->getStatusCode();
            $message_body = $http_response->getMessageBody();
            $full_http_response = $http_response->getRawHttpResponse();
            \Altumo\Utils\Debug::dump( $status_code, $message_body, $full_http_response );
                        
        
    }catch( \Exception $e ){
        
        //This will display an error if any exceptions have been thrown
        echo 'Error: ' . $e->getMessage();
        
    }
    

