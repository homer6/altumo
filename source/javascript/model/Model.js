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
    
    /**
    * Returns the api url that will be called to interact with this resource.
    * @return {string} The api route for this resource.
    */
    url: function() {
        
        var api_route = ( altumo.config.api_route || '/api' );
        
        if( api_route.charAt(api_route.length-1) != '/' ){
            api_route += '/';
        }
        
        return  api_route + this.resourceName;

    },
    
    
    /**
    * Parses the response received from the API for this resource.
    * @this altumo.model.Model
    * @param response
    */
    parse: function( response ){
        
        // Validate response
            if( !response[this.resourcePluralName] || !response[this.resourcePluralName][0] ){
                this.trigger( 'error', this, "Unexpected response, resource not found by name" );
            }
            
        return response[this.resourcePluralName][0];

    }
    
});