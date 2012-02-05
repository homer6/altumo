/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

goog.provide( 'altumo.api' );
goog.provide( 'altumo.api.AltumoApiClient' );


/**
* @fileoverview Base API Client
*/


/**
* Provides a base Altumo API client side library.
* 
* This Class is to be extended by each Widget to implement
* specific functionallity.
* 
* @author Juan Jaramillo <juan.jaramillo@altumo.com>
*/
altumo = altumo || {};
altumo.api = altumo.api || {};


/**
* Constructs this base API Client.
* 
* @constructor
*/
altumo.api.AltumoApiClient = function(){


};


/**
* Sends an http request to the server.
* 
* This method should almost never be called directly. It is a simple abstraction
* of $.ajax to facilitate REST requests for the Altumo Api.
* 
* 
* @param string route               // A route-key or url to call
* @param string http_method         // http method to use
* @param Map headers                // key-value set of headers to send
* @param Map parameters             // key-value set of parameters 
*                                   // (will be appended to url)
* @param Object request_body        // the contents of the request to be sent
*                                   // to the server. It will be stringified 
*                                   // automatically.
* @param function callback          // function to be called once the server
*                                   // response is processed.
* 
* @return void
* @throws Exception                 // if an invalid route-key is given as the
*                                   // route to be used.
*/
altumo.api.AltumoApiClient.prototype.sendRequest = function( route, http_method, headers, parameters, request_body, callback, errorCallback ){
    
    me = this;

    // Get url to call
        var url = alt.router.getUrl( route );
    
    // Validate http_method
        http_method = http_method.match(/^(GET|POST|PUT|DELETE)$/m)
            ? http_method 
            : 'POST';

    // Append parameters to url
        url += ( url.indexOf('?') ? '?' : '&' ) + $.param( parameters );

    // Serialize request_body
        request_body = JSON.stringify( request_body, null, '' );
        
    // Use default error callback if none provided
        errorCallback = errorCallback || altumo.api.AltumoApiClient.prototype.handleErrorArray;
        

    $.ajax( url, {
        
        cache: false,
        
        context: me,
        
        type: http_method,
        
        headers: headers,
        
        data: request_body,
        
        dataType: "jsonp",
        
        accepts: "application/json",

        success: function( data, textStatus, jqXHR ){            

            // callback is passed so that it can be called afterwards
                me.handleSuccessfulResponse( data, textStatus, jqXHR, callback, errorCallback );

        },
        
        error: function( jqXHR, textStatus, errorThrown ){
            
            // callback is passed so that it can be called afterwards
                this.handleErroneousResponse(  jqXHR, textStatus, errorThrown, callback, errorCallback );

        },
        
    });
    
};


/**
* This function is invoqued when a request receives a successful response.
* 
* Here is where more api-specific data interpretation takes place.
* 
*   1)  If the response payload contains any "errors", they will be passed
*       to errorCallback() (if provided, or to this.defaultErrorCallback())
* 
*   *********************** IDEAS/TODO *****************************************
* 
*   2) Handle paging
* 
*   3) Allow for a model to be passed and be hydrated with the data received.
* 
*   ****************************************************************************
* 
* @param Object data            // data as returned by $.ajax
* 
* @param string textStatus      // text representing the status of the response
*                                   ("success", "notmodified", "error", 
*                                    "timeout", "abort", or "parsererror")
* 
* @param Object jqXHR           // jqXHR jQuery XHR object
* 
* @param function callback      // A function optionally provided by the application
*                                  It will be called with the following parameters
*                                    - data    // same data object
* 
* @param function errorCallback // A function optionally provided by the application
*                                  will be called with an array of error strings
* 
* @return void
*/
altumo.api.AltumoApiClient.prototype.handleSuccessfulResponse = function( data, textStatus, jqXHR, callback, errorCallback ){
    
    if( data.errors && data.errors.length ){
        errorCallback( data.errors );
    }

    if( typeof( callback ) == 'function' ){
        callback( data );
    }
    
};


/**
* This function is invoqued when a request receives an erroneous response.
* 
* This function will be trggered under the following circumstances:
* 
*   1) A non 200 status was received
* 
*   2) Call timed out / was aborted
* 
*   3) Mal-formed data was received. e.g. invalid JSON
* 
*   * see http://api.jquery.com/jQuery.ajax/ for more information.
* 
* Errors are converted to an array and passed to the errorCallback function.
* 
* 
* @param Object jqXHR           // jqXHR jQuery XHR object
* 
* @param string textStatus      // text representing the status of the response
*                                  ( "timeout", "error", "abort", "parsererror")
*
* @param string errorThrown     // string representation of the error 
*                               // e.g. "Not Found" or "Internal Server Error."
* 
* @param function errorCallback // A function optionally provided by the application
*                                  will be called with an array of error strings
* 
* @return void
*/
altumo.api.AltumoApiClient.prototype.handleErroneousResponse = function( jqXHR, textStatus, errorThrown, errorCallback ){

    var errors = [];
    
    errors.push( errorThrown );
    
    errorCallback( errors );
    
};


/**
* This is the default "errorCallback" function for handling the array of errors
* that can be returned by an API call.
* 
* As a default, it will alert() the errors one-by-one. The application can pass
* a different "errorCallback" function to sendRequest() in order to
* handle errors differently.
* 
* @param Array errors               // Array of error strings
* 
* @return void
*/
altumo.api.AltumoApiClient.prototype.handleErrorArray = function( errors ){

    $.each( errors, function( index, error ){
        alert( error );
    });
    
};


/** Sample request code.

var api_client = new altumo.api.AltumoApiClient();


api_client.sendRequest( 
    '@api_list_users', 
    'PUT', 
    { "Authorization": "Basic czEPgiRdcAPIedJGOpMFbNwyuDKkzCqP", "X-API-Version":"1.0" },
    { "page_size":"30" }, 
    { "request": "stuff", "goes":"here" },
    function( data ){
        console.log( 'data', data );
    }
);
*/
