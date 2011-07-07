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
 * @fileoverview global Altumo object
 *
 * alt is a global object and it's an instance of altumo.core.Altumo
 */
 
// Provides
    goog.provide( 'altumo.core.Altumo' );

// Requires
    goog.require( 'altumo.configuration.Config' );
    goog.require( 'altumo.routing.Router' );

altumo.core = altumo.core || {};
    
/**
* Initializes all the core components of the Altumo singleton
* 
* @param Object options
*       // Options:
*           - routes      // A list of routes indexed by route key.
*/
altumo.core.Altumo = function( options ){

    var alt = this;
    
    // Initialize Config
        this.config = new altumo.configuration.Config();

    // Initialize the Router
        this.router = new altumo.routing.Router( { routes: options.routes } );

};