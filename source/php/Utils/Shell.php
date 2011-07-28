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
* This class is a collection of static functions for interacting with a bash
* shell.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class Shell{
    

    /**
    * Runs a bash $command, properly connecting STDIN, STDOUT and STDERR.
    * Useful for invoking interactive bash programs.
    * 
    * @param string $command
    * @return integer                       //the return value of the command
    */
    static public function runWithPipedOutput( $command ){
        
        //Thanks to Wrikken
        //See: http://stackoverflow.com/questions/6769313/how-can-i-invoke-the-mysql-interactive-client-from-php
            $descriptorspec = array(
               0 => STDIN,
               1 => STDOUT,
               2 => STDERR
            );            
            $process = proc_open( $command, $descriptorspec, $pipes, getcwd() );
            
            if( !$process ){
                throw new \Exception( 'Failed to run command.' );
            }
            
            return proc_close( $process );
        
    }
    

}