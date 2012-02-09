/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
goog.provide( 'altumo.view.SectionalApplicationView' );

goog.require( 'altumo.view.View' );

/**
* @fileoverview base Model class
*/

altumo = altumo || {};
altumo.view = altumo.view || {};

/**
 * Provides a base View class that applications that are organized in major sections
 * (usually switching from section to section using menu buttons) can extend from.
 * E.g. a 3 page website with 3 menu buttons. Each button loads one page. This view
 * facilitates loading and removal of these page views.
 * 
 * @constructor
 * @param {Object} options
 */
altumo.view.SectionalApplicationView = altumo.view.View.extend({
    
    /**
    * Returns the api url that will be called to interact with this resource.
    * @return {string} The api route for this resource.
    */
    initialize: function() {

        altumo.view.View.prototype.initialize.call( this );

    },
    
    
    /**
    * Loads a Page View, which is a View that represents a major section in your
    * application. For example, when clicking on a menu button, 
    * 
    * @param page_view
    */
    loadPageView: function( page_view ){

        // remove previously loaded page
            if( this.current_page_view ){
                this.current_page_view.remove();
            }
            
        // store pointer to current page view
            this.current_page_view = page_view;
            
        // render view
            var $page_container = $( document.createElement( 'div' ) )
                .appendTo( this.getPageContainer() );
            
            page_view.setElement( $page_container );
            page_view.render();

    },
    
    /**
    * Sets the current page container element, which is the element within the
    * DOM that contains the page view.
    * 
    * @param {Object} $element
    */
    setPageContainer: function( $element ){
        
        this.page_container_element = $element;
        
    },
    

    /**
    * Gets the current page container element, which is the element within the
    * DOM that contains the page view.
    * 
    * @return {Object} $element
    */
    getPageContainer: function(){
        
        return this.page_container_element;
        
    }
    
});