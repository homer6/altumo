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
    
    
    /**
    * Sets the time zone.
    * 
    * @param string $time_zone
    */
    public function setTimeZone( $time_zone ){
        
        $this->time_zone = $time_zone;
        
    }

    
    /**
    * Gets the time zone.
    * 
    * @return string
    */
    public function getTimeZone(){
        
        return $this->time_zone;
        
    }
    
    /*
    public function __set_state( $array ){
        echo (object) $array;
    }
    */
        
      // 'time_zone' => NULL,\n   'ts' => 1316456162,\n   'init' => 1316456162,\n))
    
        
    
}