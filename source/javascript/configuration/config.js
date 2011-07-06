/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
* (c) Juan Jaramillo <juan.jaramillo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

/**
 * @fileoverview Config handler for the Altumo Library.
 * 
 * Keeps global configuration items including routing.
 */
 
// Provides
    goog.provide( 'alt.configuration' );
    goog.provide( 'alt.configuration.Config' );

// Requires
    goog.require( 'alt' );
    goog.require( 'goog.storage.Storage' );

/**
 * Provides configuration storage for routing and other global applaication
 * settings.
 *
 * @constructor
 **/

alt.configuration.Config = function(){
    goog.base( this );
    
    this.routingTable = {};
};

goog.inherits( alt.configuration.Config, goog.storage.Storage );

/**
* Load all routes into the configuration
* All routes are automaticall
*/
alt.configuration.Config.prototype.loadRoutes = function( routes ){
    
    me = this;
    
    $.each( routes, function( route_key, route ){
        
        me.routingStorage.set( route_key, route );
        
    });
    
};