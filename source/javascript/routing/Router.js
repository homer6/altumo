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
 * @fileoverview Routing manager for the Altumo Library
 * 
 * A directory of Routes for use in the client side.
 */
 
// Provides
    goog.provide( 'altumo.routing.Router' );

// Requires
    //goog.require( 'alt' );



altumo.routing = altumo.routing || {};

/**
 * Provides route-key to route resolution for client side widgets.
 *
 * @constructor
 * 
 * @param options
 *      - routes: Map of routes pre-load.
 **/
altumo.routing.Router = function( options ){

    // Data storage
        this._routingTable = {};
    

    // Configuration
        this.validRouteRegex = /^[a-zA-Z0-9_-]+$/m;
    
    // Load routes if provided
        if( options.routes ){
            this.loadRoutes( options.routes );
        }

};


/**
* Load all routes into the routing table
* 
* @param Array routes
*/
altumo.routing.Router.prototype.loadRoutes = function( routes ){
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
altumo.routing.Router.prototype.addRoute = function( route_key, route ){

    this.validateRouteKey( route_key, true );
    
    this._routingTable[ route_key ] = route;
    
};


/**
* Get a route from the routing table based on its key
* 
* @param string route_key       // Unique route key.
* 
* @return string                // If the route does not exist,
*                               // an exception will be thrown
* 
* @throws Exception             // If the key is invalid or non-existant
*/
altumo.routing.Router.prototype.getRouteByKey = function( route_key ){
    
    if( !this.validateRouteKey( route_key, true ) );
    
    if( this._routingTable[ route_key ] ){
        
        return this._routingTable[ route_key ];
        
    } else {
        
        throw 'Invalid route key ' + route_key;
        
    }

};


/**
* Gets a URL based on a route-key (prepended by "@") pr from a URL
* 
* The following expressions are accepted:
*   1 - route-key prepended with a "@"
*       e.g. @homepage
* 
*   2 - A url route (the same route will be returned)
*       e.g. /homepage/
* 
*   3 - A full URL. (the full URL will be returned)
*       e.g. http://my.test.com/homepage/
* 
* Note: this function does not validate input for cases 2 and 3
* 
* @param string expression      // An expression following the rules above.
* @returns string               // the URL being requested
* @throws Exception             // If an invalid route_key is given.
*/
altumo.routing.Router.prototype.getUrl = function( expression ){
    
    if( expression.indexOf( '@' ) === 0 ){
        var route_key = expression.substring( 1 );

        return this.getRouteByKey( route_key );
        
    } else {
        
        return expression;
        
    }
    
}



/**
* Checkes whether a route_key is valid.
* 
* route_key must match this.validRouteRegex
* 
* @param string route_key
* @param throw_on_invalid [false]   // throw an exception if the key is not valid
* @return bool                      // true on valid, false on invalid
*/
altumo.routing.Router.prototype.validateRouteKey = function( route_key, throw_on_invalid ){
    
    throw_on_invalid = throw_on_invalid || false;
    
    if( !route_key.match( this.validRouteRegex ) ){
        
        if( throw_on_invalid ){
            throw new Exception( 'Invalid route_key ' + route_key );
        }
        
        return false;
        
    }
    
    return true;
}