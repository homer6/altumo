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
 * base.js initialized the alt.* global namespace and initializes Altumo by
 * calling the alt.load() class function.
 */
 
goog.provide( 'alt' );
goog.provide( 'alt.Config' );
goog.provide( 'alt.Config.routing' );
goog.provide( 'alt.widget' );

goog.require( 'goog.dom' );

/**
* 
*/
var a = function(title, content, noteContainer) {
  this.title = title;
  this.content = content;
  this.parent = noteContainer;
};