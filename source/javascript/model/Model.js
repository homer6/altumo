/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
goog.provide( 'altumo.model.Model' );

/**
* @fileoverview base Model class
*/

altumo = altumo || {};
altumo.model = altumo.model || {};

/**
 * Provides a base Model class that application models should extend.
 * This intermediary class between Backbone.js' model and the application model
 * allows for altumo-api specific parsing and error handling.
 * 
 * @constructor
 * @param {Object} options
 */
altumo.model.Model = Backbone.Model.extend({
    
    urlRoot: altumo.config.api_route,
    
    /**
    * Returns the api url that will be called to interact with this resource.
    * @return {string} The api route for this resource.
    */
    url: function() {
        
        var api_route = ( this.urlRoot || '/api' );
        
        if( api_route.charAt(api_route.length-1) != '/' ){
            api_route += '/';
        }
        
        api_route += this.resourceName;
        
        if( this.id ){
            api_route += '/' + this.id;
        }
        
        return  api_route;

    },
    
    
    /**
    * Parses the response received from the API for this resource.
    * @this altumo.model.Model
    * @param {object} response
    */
    parse: function( response ){

        // If this is an API response, parse it as such
            if( this.isAltumoApiResponse(response) ){
                
                if( response[this.resourcePluralName][0] ){
                    return response[this.resourcePluralName][0];
                }

                return [];
                
            }
            
        // If this is data pased from a collection render operation, parse parse
        // individual object
            if( typeof(response) == 'object' ){
                return response;
            }

        this.trigger( 'error', this, "Unexpected response." );

    },
    
    
    /**
    * Returns true if a response object originated from an Altumo API response.
    * @protected
    * 
    * @param {object} response 
    * @return {bool}
    */
    isAltumoApiResponse: function( response ){
        
        // Look for required altumo API parameters and the presense of the resource
        // name in plural within the response.
        
        if( response['has_many_pages'] != undefined
            && response['total_results'] != undefined
            && response[this.resourcePluralName] != undefined
        ) return true;

        return false;

    }
    
});