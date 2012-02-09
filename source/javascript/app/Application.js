goog.provide( 'altumo.app.Application' );

/**
 * Base for a Frontend Application. Extend your myApp.app.Application from this.
 * 
 * @constructor
 * @param {Object} options
 */
altumo.app.Application = function( options ){
    
    this.init( options );
    
};


/**
 * Initializes the App, sets up event handlers, etc. 
 * If this.options.renderViewToElement is provided, the View's element will be
 * appended to it i.e. the App View will be rendered.
 * 
 * @param {Object} options
 */
altumo.app.Application.prototype.init = function( options ){
    
    /**
    * Default options
    * 
    * @protected
    * @type {Object}
    */
        this.options = {

            /**
            * sample comments
            * @config
            * @type {sampleType}
            */
            //myOption: null,

        };
        
    /**
    * App View
    * 
    * @protected
    * @type Backbone.View
    */
        this.view = null;


    //override the default options with the provided ones
        this.options = $.extend( true, {}, this.options, options );

};


/**
 * Gets the Application View, creates a new one if one doesn't exist.
 * 
 * @return {altumo.view.View}
 */
altumo.app.Application.prototype.getView = function(){
    
    if( !this.view ){
        this.view = this.createView();
    }

    return this.view;

};


/**
 * Creates and returns a new instance of View to be used as the app view.
 * This method is to be overridden by your implementation.
 * 
 * @protected
 * @return {altumo.view.View}
 */
altumo.app.Application.prototype.createView = function(){
    
    throw "Application must implement createView";
    
};


/**
 * Gets the Router, creates a new one if one doesn't exist.
 * 
 * @return {altumo.router.Router}
 */
altumo.app.Application.prototype.getRouter = function(){
    
    if( !this.router ){
        
        // create a new router
            this.router = this.createRouter();
        
        // start tracking history
            Backbone.history.start({pushState: true});

    }

    return this.router;

};


/**
 * Creates and returns a new instance of View to be used as the app view.
 * This method is to be overridden by your implementation.
 * 
 * @protected
 * @return {altumo.router.Router}
 */
altumo.app.Application.prototype.createRouter = function(){
    
    throw "Application must implement createRouter";
    
};