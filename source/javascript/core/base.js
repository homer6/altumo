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
 * @fileoverview Bootstrap for the Altumo JS Linbrary
 *
 * base.js initializes the alt.* global namespace and loads Altumo by
 * calling the alt.load() class function.
 */
 
// Provides
    goog.provide( 'alt' );

// Requires
    goog.require( 'alt.configuration.Config' );


/**
* Initializes all the core components of the Altumo library
* 
* @param Object options
*       // Options:
*           - routes      // A list of routes indexed by route key.
*/
alt.load = function( options ){

    // Create config object
        alt.Config = new alt.configuration.Config();

    // Load Routing table
        alt.Config.Router.loadRoutes( options.routes );
};