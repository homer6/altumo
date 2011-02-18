<?php

/*
 * This file is part of the Altumo library.
 *
 * (c) Steve Sperandeo <steve.sperandeo@altumo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Altumo\String;
 
   


 /**
 * This class contains a number of string helper functions.
 * 
 * @author Steve Sperandeo <steve.sperandeo@altumo.com>
 */
class String{

    /**
    * Inserts one string ($addition) into another ($destination) at a given 
    * string $offset.
    * 
    * @param string $addition //new string to add into $destination
    * @param string $destination //existing string that will contain $addition
    * @param integer $offset //offset in $destination that we will place $addition
    * 
    * @see http://forums.digitalpoint.com/showthread.php?t=182666#post1785645
    * 
    * @return string
    */
    static public function insert( $addition, $destination, $offset ){    
        $left = substr( $destination, 0, $offset );
        $right = substr( $destination, $offset );
        return $left . $addition . $right;
    }
    
}


