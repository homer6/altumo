/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
goog.provide( 'altumo.router.Router' );

/**
* @fileoverview base Model class
*/

altumo = altumo || {};
altumo.router = altumo.router || {};

/**
 * Provides a base Router class that the application Router will extend.
 * This is merely to augment or override Backbone's Router within
 * altumo.
 * 
 * @constructor
 * @param {Object} options
 */
altumo.router.Router = Backbone.Router.extend({    
});