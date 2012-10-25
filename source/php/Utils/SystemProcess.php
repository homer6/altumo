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
* This class facilitates interacting with system processes that are running.
* 
* @author Juan Jaramillo <juan.jaramillo@altumo.com>
*/
class SystemProcess{  

    /**
    * Throws an exception if this script cannot find the ps binary. It looks
    * for it in the system PATH only.
    * 
    * @return void
    * 
    * @throws \Exception
    *   // if 'ps' does not exist in the system's PATH
    */
    protected function assertPsExists(){

        $ps_location = shell_exec( 'which ps' );
        
        \Altumo\Validation\Strings::assertNonEmptyString(
            $ps_location,
            'SystemProcess was not able to execute \'ps\''
        );

    }

    
    /**
    * Retrieves a list of processes that are currently running in the system
    * and that are visible to the system user that is executing this script.
    * 
    * @return array
    *   // array of processes that are running/. Each entry looks like this:
    *       array(
    *           process_id => 12345,
    *           user_id => theuser,
    *           command => /user/bin/hello --there
    *       )
    */
    public static function getRunningProcesses(){
    
        $command = "ps -eo pid,user,cmd";
        
        // get a list of all system processes
            $all_processes = `$command`;
        
        // parse the list into fields
            preg_match_all('/^\\s*(?P<pid>\\d+)\\s+(?P<uid>.*?)\\s+(?P<cmd>.*?)$/m', $all_processes, $matches );
        
        // parse into an array
            $output = array();
            
            foreach( $matches["pid"] as $process_index => $process_id ){
            
                $output[] = array(
                    'process_id' => $process_id,
                    'user_id' => $matches["uid"][$process_index],
                    'command' => $matches["cmd"][$process_index]
                );
                
            }
            
            \Altumo\Utils\Debug::dump($output);
            
        return $output;
          
    }

}
