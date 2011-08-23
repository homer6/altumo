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
    * Checkout a named branch or hash. 
    * 
    * Command is executed with piped output.
    * @see Altumo\Utils\Shell::runWithPipedOutput
    * 
    * 
    * @param bool $quiet
    *   // Run command in quiet mode. (ommit output)
    *
    * @return void
    */
    static public function checkout( $checkout_point, $quiet = true ){
        
        $command = "git checkout" . self::getQuietFlag($quiet) . $checkout_point;

        \Altumo\Utils\Shell::runWithPipedOutput( $command );
        
    }    


    /**
    * Updates submodules recursively
    * 
    * 
    * @param bool $quiet
    *   // Run command in quiet mode. (ommit output)
    * 
    * @return void
    */
    static public function updateSubmodulesRecursively( $quiet = true ){

        $command = "git submodule" . self::getQuietFlag($quiet) . "update --recursive --init";

        \Altumo\Utils\Shell::runWithPipedOutput( $command );
        
    }   
    
    
    /**
    * Returns " --quiet " (with spaces) if $quiet is true 
    * or empty string otherwise.
    * 
    * @param bool $quiet
    * 
    * @return string
    */
    static protected function getQuietFlag( $quiet ){
        
        return $quiet
            ? ' --quiet '
            : '';
            
    } 
    
}