/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
* (c) Juan Jaramillo <juan.jaramillo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/




goog.provide( 'altumo.configuration.Config' );


/**
* @fileoverview Config handler for the Altumo Library.
* 
* Keeps global configuration items including routing.
* 
* @author Juan Jaramillo <juan.jaramillo@altumo.com>
*/
altumo.configuration = altumo.configuration || {};


/**
 * Provides configuration storage for routing and other global applaication
 * settings.
 *
 * @constructor
 **/
altumo.configuration.Config = function(){
    
    // Data storage
        this._config = {};

};


/**
* Get a config setting
* 
*/
altumo.configuration.Config.prototype.get = function( setting_key, default_return ){

};


/**
* Get a config setting
* 
*/
altumo.configuration.Config.prototype.set = function( setting_key, value ){

};
