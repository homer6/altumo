/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
goog.provide( 'altumo.model.Collection' );

/**
* @fileoverview base Model class
*/

altumo = altumo || {};
altumo.model = altumo.model || {};

/**
 * Provides a base Collection class that application collections should extend.
 * This intermediary class between Backbone.js and the application
 * allows for altumo-api specific functionality.
 * 
 * @constructor
 * @param {Object} options
 */
altumo.model.Collection = Backbone.Collection.extend({

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
    * @param response
    */
    parse: function( response ){

        // Validate response
            if( !response[this.resourcePluralName] || !response[this.resourcePluralName][0] ){
                this.trigger( 'error', this, "Unexpected response, resource not found by name" );
            }
            
        return response[this.resourcePluralName];

    }

});