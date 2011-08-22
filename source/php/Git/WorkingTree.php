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


namespace Altumo\Git;


/**
* This class provides some tools for working with a Git working tree
* 
* @author Juan Jaramillo <juan.jaramillo@altumo.com>
*/
class WorkingTree{


    /**
    * Checkout a named branch or hash
    * 
    * Note:
    *   This does not perform any error handling nor validation. If there
    *   are errors checking out, they will be ignored.
    * 
    * @return void
    */
    static public function checkout( $checkout_point ){
        
        `git checkout $checkout_point`;
        
    }    


    /**
    * Updates submodules recursively
    * 
    * @return void
    */
    static public function updateSubmodulesRecursively(){
        
        `git submodule --update --recursive --init`;
        
    }    
    
    
}  

