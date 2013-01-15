<?php
/*
* This file is part of the Altumo library.
*
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
* (c) Juan Jaramillo <juan.jaramillo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/


/**
 * This is a class for rendering code-less string templates
 * 
 * @package Altumo
 * @subpackage Template
 */
namespace Altumo\Template;



class Simple{

	protected $opening_delimiter = '{{';
	protected $closing_delimiter = '}}';
	
	
	/**
	 * @return self
	 */
	public static function create()
	{
		return new static;
	}
	
	
	/**
	 * @param string $template
	 * 
	 * @return self
	 */
	public function setTemplate( $template )
	{
		$this->template = $template;
		
		return $this;
	}
	
	
	/**
	 * @param mixed $data
	 * 
	 * @return self
	 */
	public function setData( $data )
	{
		$this->data = $data;
		
		return $this;
	}
	
	
	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}
	
	
	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}
	
	
	
	/**
	 * @throws Exception if $data doesn't validate
	 * @return string
	 */
	public function render()
    {
    	$data = $this->getData();
    	
		// validate $data
    	if (
    		( null !== $data ) // if $data is not null, 
    		&& ( ! is_array( $data ) )  // and not an array
    		&& ( ! is_object( $data ) )  // and not an object,
    	)
    		throw new \Exception( '$data expects a null, array or object' );

    	
    	$template = $this->getTemplate();
    	
    	// validate $template
    	if (
    		( null !== $template )
    		&& ! is_scalar( $template )
    	)
    		throw new \Exception( '$template expects a scalar value' );
    	    	
   		
   		$pattern = sprintf(
   			'/%s([a-zA-Z0-9-_.]+)%s/',
   			preg_quote( $this->opening_delimiter ),
   			preg_quote( $this->closing_delimiter )
   		);

   		
   		$matches = array();
   		   		
   		preg_match_all( $pattern, $template, $matches );
    	
   		if ( ! isset( $matches[0] ) || ! isset( $matches[1] ) ) return $template;
    	
   		foreach( $matches[1] as $tag_index => $full_tag ) {
    				
   			$tag_with_delimiters = $matches[ 0 ][ $tag_index ];
    				
   			$tag_tree = explode( '.', $full_tag );
    	
   			$drilldown = $data;
    				
   			$tag_value = null;
    				
   			foreach( $tag_tree as $tag_level => $tag ) {
    	
   				$drilldown_array = $drilldown;
   				if ( is_object( $drilldown ) ) $drilldown_array = get_object_vars( $drilldown );
    	
   				if ( ! isset( $drilldown_array[ $tag ] ) ) break;
    	
   				$drilldown = $drilldown_array[ $tag ];
    	
   				if ( $tag_level == ( count( $tag_tree ) -1 ) ) {
   					if ( is_scalar( $drilldown ) ) $tag_value = $drilldown;
   				}
    	
   			}
    				
   			$template = str_replace( $tag_with_delimiters, $tag_value, $template );
    				
   		}
   	
   		return $template;
    }


	/**
	 * @param string $opening
	 * @param string $closing
	 * 
	 * @return self
	 */
	public function setDelimiters( $opening, $closing )
	{
		$this->opening_delimiter = $opening;
		$this->closing_delimiter = $closing;

		return $this;
	}

}
