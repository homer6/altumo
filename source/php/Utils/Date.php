<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
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
class Date extends sfDate{
    
    protected $time_zone = null;
    
    public function setTimeZone( $time_zone ){
        
        $this->time_zone = $time_zone;
        
    }
    
    public function getTimeZone(){
        
        return $this->time_zone;
        
    }
    
    
}