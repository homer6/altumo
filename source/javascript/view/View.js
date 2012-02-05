/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
goog.provide( 'altumo.view.View' );

/**
* @fileoverview base Model class
*/

altumo = altumo || {};
altumo.view = altumo.view || {};

/**
 * Provides a base View class that application views should extend.
 * This intermediary class between Backbone.js' view and the application view
 * allows for generic altumo-workflow specific operations to be centralized..
 * 
 * @constructor
 * @param {Object} options
 */
altumo.view.View = Backbone.View.extend({
    
    /**
    * Returns the api url that will be called to interact with this resource.
    * @return {string} The api route for this resource.
    */
    initialize: function() {

        Backbone.View.prototype.initialize.call( this );
        
        // If this view has a model, bind it to render.
        if( this.model ){
            this.model.bind( "change", this.render, this );
        }

    }
    
    
});