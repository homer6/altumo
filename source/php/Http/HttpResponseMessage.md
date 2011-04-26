Description
-----------

HttpResponseMessage is used to easily parse a full raw HTTP Response.  It can
also detect if the response is gzipped, and decompresses the message if it is.

Sample Usage
------------

This try block makes a request for an xml document with curl, returns the response
(with http headers), decompresses it and parses it as xml.  It then echos the
elements to the page.

    try{
        
        //load class autoloader
            require_once( __DIR__ . '/loader.php' );  //you should ensure that is is the correct path for this file
    
        //make the response; return the response with the headers
            $client = new \Altumo\Http\OutgoingHttpRequest( 'http://www.winnipegsun.com/home/rss.xml' );
            $response = $client->send( true );
        
        //parse the http response
            $http_response = new \Altumo\Http\HttpResponseMessage( $response );    
            $xml = $http_response->getMessageBody();  //this will unzip it if the response is gzipped
            
        //optionally, use the xml wrapper to find what you're looking for
            $xml_element = new \Altumo\Xml\XmlElement( $xml );    
            $title = $xml_element->xpath('channel/title');
            $link = $xml_element->xpath('channel/link');
            $copyright = $xml_element->xpath('channel/copyright');
            $items = $xml_element->queryWithXpath('channel/item', \Altumo\Xml\XmlElement::RETURN_TYPE_XML_ELEMENT );
        
            echo 'Title: ' . $title . '<br /><br />';
        
            foreach( $items as $item ){
                echo $item->xpath( 'title' ) . '<br />';            
                echo $item->xpath( 'link' ) . '<br /><br />';
            }
        
    }catch( \Exception $e ){
        
        echo 'Error: ' . $e->getMessage();
        
    }
    

