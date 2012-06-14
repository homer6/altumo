<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
* (c) Juan Jaramillo <juan.jaramillo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/




namespace Altumo\Utils;


/**
* Useds for manipulating dates.
* 
* @see http://www.symfony-project.org/plugins/sfDateTime2Plugin
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class Date extends sfDate\sfDate{
    
    protected $time_zone = null;
    protected $date_time_zone = null;


    const DATE_PERIOD_DAY = 1;
    const DATE_PERIOD_WEEKTODATE = 2;
    const DATE_PERIOD_MONTHTODATE = 3;
    const DATE_PERIOD_YEARTODATE = 4;
    
    
    /**
    * Constructor for this date object.
    * 
    * @param timestamp/string/sfDate $date
    */
    public function __construct( $date = null ){
        
        parent::__construct( $date );        
        $this->date_time_zone = new \DateTimeZone( date_default_timezone_get() );        
        
    }
    
    
    /**
    * Sets the time zone.
    * 
    * @param string $time_zone
    */
    public function setTimeZone( $time_zone ){
        
        $this->time_zone = $time_zone;
        try{
            $this->date_time_zone = new \DateTimeZone( $time_zone );
        }catch( \Exception $e ){
                        
        }
        
    }

    
    /**
    * Gets the time zone.
    * 
    * @return string
    */
    public function getTimeZone(){
        
        return $this->time_zone;
        
    }
    
    
    /**
    * Converts this datetime to the supplied timezone.
    * Uses the default_date_timezone if this Date's timezone hasn't been set.
    * Returns a new \Altumo\Utils\Date object.
    * 
    * @param string|\DateTimeZone $timezone
    * @return \Altumo\Utils\Date
    */
    public function convertToTimeZone( $timezone ){
        
        if( is_null($this->date_time_zone) ){
            $current_timezone = new \DateTimeZone( date_default_timezone_get() );
        }else{
            $current_timezone = $this->date_time_zone;
        }
        
        if( !($timezone instanceof \DateTimeZone) ){
            $user_timezone = new \DateTimeZone( $timezone );
        }else{
            $user_timezone = $timezone;
        }
        
        if( $current_timezone->getName() == $user_timezone->getName() ){
            return new \Altumo\Utils\Date( $this->format('U') );
        }
        
        $system_timezone = $current_timezone;
        $user_time = new \DateTime( $this->format('c') );
        $system_offset = $system_timezone->getOffset( $user_time );
        $user_offset = $user_timezone->getOffset( $user_time );
        
        $new_date = new \Altumo\Utils\Date( $user_time->format('U') + ( -1 * $system_offset ) +  $user_offset );
        $new_date->setTimeZone( $user_timezone->getName() );
        
        return $new_date;
        
    }


    /**
     * Returns beginning or ending of a period
     * 
     * @param string $phrase
     * @param bool $beginning True for boundary beginning, False for boundary ending
     * 
     * @throws \Exception if $beginning=false
     * 
     * @return self
     */
    public function getBoundaryDateByPeriod( $period, $beginning=true )
    {
    	$this_timestamp = $this->get();
    	
    	switch( $period ){
    		
    		case static::DATE_PERIOD_DAY:

    			if ( $beginning ) {
    				
	    			// get the beginning of the day
	    			$result_ts = strtotime( 'today', $this_timestamp );
	    			
    			} else {
    				
    				throw new \Exception( 'Bounday ending not yet supported' );
    				
    			}
    			
    			break;
    			
    		case static::DATE_PERIOD_WEEKTODATE:
    			
    			if ( $beginning ) {

    				// get date of the first day in week from '2012-W12-1'
    				$result_ts = strtotime( date ("Y-\WW-1", $this_timestamp) );
    				
				} else {
					
    				throw new \Exception( 'Bounday ending not yet supported' );
    				
    			}
    				
    			break;
    			
    		case static::DATE_PERIOD_MONTHTODATE:
    			
    			if ( $beginning ) {
    				
    				// get beginning of month
	    			$result_ts = strtotime( sprintf('%s %s', date('M', $this_timestamp), date('Y', $this_timestamp) ) );
    				break;
    				
    			} else {

    				throw new \Exception( 'Bounday ending not yet supported' );
   				
    			}
    			
    		case static::DATE_PERIOD_YEARTODATE:
    			
    			if ( $beginning ) {
    				
    				// get beginning of year
	    			$result_ts = mktime(0, 0, 0, 1, 1, date('Y', $this_timestamp) );
    				break;
    				
				} else {
					
    				throw new \Exception( 'Bounday ending not yet supported' );
    				
    			}
    			
    		default:
    			// if period not recognized,
    			
    			// throw exception
    			throw new \Exception('Period not recognized');
    			
    	} // switch

    	
    	if ( $result_ts ) {
    		
	    	$result_date = new static;
	    	$result_date->set( $result_ts );
	    	
	    	return $result_date;
    	}
    	
    } // getBoundaryDateByPeriod

    
}
