goog.provide( 'altumo.behaviour.InputWatermark' );

(function($){

    /**
    * input_watermark representing a watermark on an input box.
    * 
    * 
    * @class input_watermark
    * @namespace altumo.behaviour
    * @constructor
    * @param {Object} options   Collection of object options
    * @param {Element} element  Optional parameter referring to a DOM element.
    * 
    */
    altumo.behaviour.InputWatermark = function( options, element ){

        //if( (options !== undefined) ){
            this.init( options, element );
        //}
        
    
    };
    var InputWatermark = altumo.behaviour.InputWatermark;
    
    

    /**
    * Initializes this object with these options.
    */
    InputWatermark.prototype.init = function( options, element ){

        //default options
        this.options = {

            /**
            * text default option
            *
            * @config
            * @type {string}
            */
            text: 'Watermark'

            
        };

        //override the default options with the provided ones
        this.options = $.extend( true, {}, this.options, options );

        this.setText( this.options.text );

        this.setElement( element );
        
        this.draw();
            

    };
    

    /**
    * Sets up the DOM for this InputWatermark.
    * 
    * @method draw
    */
    InputWatermark.prototype.draw = function(){
    
        var $element = $( this.getElement() );
        
        this._isWatermarkShown = false;
        var that = this;
        
        var original_value = $element.attr('value');
        $element.data('original_value', original_value);
        
        if( $element.attr('type') === 'password' ){
            this._isPasswordField = true;                
        }else{
            this._isPasswordField = false;
        }
                  
        $element.blur(function(){
            if( $element.val() === '' ){
                that.showWatermark();
            }
        });        
        $element.focus(function(){
            if( that._isWatermarkShown ){
                that.hideWatermark();
            }
        });
        
        if( $element.val() === '' ){
            this.showWatermark();
        }
    
        
    };
    
    
    /**
    * Setter for the text field on this InputWatermark.
    * 
    * @method setText
    * @param {string} text
    */
    InputWatermark.prototype.setText = function( text ){
    
        this._text = text;
        
    };
    
    
    /**
    * Getter for the text field on this InputWatermark.
    * 
    * @method getText
    * @return {string}
    */
    InputWatermark.prototype.getText = function(){
    
        return this._text;
        
    };
        
        
    /**
    * Getter for the text field on this InputWatermark.
    * 
    * @method getText
    * @return {string}
    */
    InputWatermark.prototype.watermarkIsShown = function() {
        
        return this._isWatermarkShown;
        
    };
        

    /**
    * This method is used to turn set the value of the field, safely turning 
    * off the InputWatermark (hiding it).
    * 
    * @method getText
    * @return {string}
    */
    InputWatermark.prototype.setFieldValue = function( value ){
            
        this.hideWatermark();
        $( this.getElement() ).val( value );
        
    };
        
        
    /**
    * Shows the watermark.
    * 
    * @method showWatermark
    */
    InputWatermark.prototype.showWatermark = function() {
            
        var $element = $( this.getElement() );
        
        $element.addClass( 'behaviour_input_watermark' );
        $element.val( this.getText() );
        this._isWatermarkShown = true;

    };
        
        
    /**
    * Hides the watermark.
    * 
    * @method hideWatermark
    */
    InputWatermark.prototype.hideWatermark = function() {
        
        var $element = $( this.getElement() );

        $element.val('');
        $element.focus(function(){});
        $element.removeClass('behaviour_input_watermark');
        
        this._isWatermarkShown = false;

    };
        
        
    /**
    * Setter for the element field on this InputWatermark.
    * 
    * @method setElement
    * @param {Element} element
    */
    InputWatermark.prototype.setElement = function( element ){
    
        this._element = element;
        
    };
    
    
    /**
    * Getter for the element field on this InputWatermark.
    * 
    * @method getElement
    * @return {Element}
    */
    InputWatermark.prototype.getElement = function(){
    
        if( this._element === undefined ){
            this._element = document.createElement('input');
        }        
        return this._element;
        
    };
    

})(jQuery);



//jQuery Pluginify a class
//Classes that are attached to DOM elements ( eg. widgets or models ) need to be added as plugins.
if( altumo.behaviour.InputWatermark ){
    $.fn.extend({
        'behaviour_input_watermark': function(options) {
            return this.each(function(){
                $(this).data('behaviour_input_watermark', new altumo.behaviour.InputWatermark( options, this ) );
            });
        }
    });
}