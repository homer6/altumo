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
    goog.provide( 'alt.configuration.Config' );

// Requires
    //goog.require( 'alt' );
    goog.require( 'alt.routing.Router' );


/**
 * Provides configuration storage for routing and other global applaication
 * settings.
 *
 * @constructor
 **/
alt.configuration = alt.configuration || {};

alt.configuration.Config = function(){
    
    // Internal namespaces
        this.Router = new alt.routing.Router();

};