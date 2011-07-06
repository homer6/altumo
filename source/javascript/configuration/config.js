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

/**
 * Provides configuration storage for routing and other global applaication
 * settings.
 *
 * @constructor
 **/
alt.configuration = alt.configuration || {};

alt.configuration.Config = function(){
    //goog.base( this );
    
    this.routingTable = {};
};


/**
* Load all routes into the configuration
* All routes are automatically
* 
* @param Array routes
*/
alt.configuration.Config.prototype.loadRoutes = function( routes ){
    
    me = this;
    
    $.each( routes, function( route_key, route ){
        
        me.addRoute( route_key, route );
        
    });
    
};


/**
* Add a route to the routing table.
* 
* @param string route_key       // Unique route key
* @param string route           // Route
*/
alt.configuration.Config.prototype.addRoute = function( route_key, route ){
    
    this.routingTable[ route_key ] = route;
    
};


/**
* Add a route to the routing table.
* 
* @param string route_key       // Unique route key
* 
* @return string
*/
alt.configuration.Config.prototype.getRoute = function( route_key ){
    
    return this.routingTable[ route_key ] || null;
    
};