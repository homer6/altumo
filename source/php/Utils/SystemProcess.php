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
    protected static function assertPsExists(){

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
    * Optionally, results can be filtered by user_id (uid) or by command using a 
    * regular expression.
    * 
    * @return array
    *   // array of processes that are running. Each entry looks like this:
    *       array(
    *           process_id => 12345,
    *           user_id => theuser,
    *           command => /user/bin/hello --there
    *       )
    */
    public static function getRunningProcesses( $filter_by_user_id = null, $filter_by_command_regex = null){

        // Validate parameters
            if( !is_null($filter_by_user_id) ){
                
                $filter_by_user_id = \Altumo\Validation\Strings::assertNonEmptyString(
                    $filter_by_user_id,
                    '$filter_by_user_id expects non-empty string'
                );
                
            }
            
            if( !is_null($filter_by_command_regex) ){
                
                $filter_by_command_regex = \Altumo\Validation\Strings::assertNonEmptyString(
                    $filter_by_command_regex,
                    '$filter_by_command_regex expects non-empty string'
                );
                
            }


        self::assertPsExists();
        
        // get a list of all system processes
            $all_processes = `ps -eo pid,user,cmd`;
        
        // parse the list into fields
            preg_match_all('/^\\s*(?P<pid>\\d+)\\s+(?P<uid>.*?)\\s+(?P<cmd>.*?)$/m', $all_processes, $matches );
        
        // parse into an array
            $output = array();
            
            foreach( $matches["pid"] as $process_index => $process_id ){
            
                $user_id = $matches["uid"][$process_index];
                $command = $matches["cmd"][$process_index];
                
                // filter by user id
                    if( !is_null($filter_by_user_id) ){
                        
                        if( strcmp($filter_by_user_id, $user_id) != 0 ){
                            continue;
                        }
                        
                    }            
                        
                // filter by command
                    if( !is_null($filter_by_command_regex) ){
                        
                        if( !preg_match($filter_by_command_regex, $command) ){
                            continue;
                        }
                        
                    }
                
                
                $output[] = array(
                    'process_id' =>     $process_id,
                    'user_id' =>        $user_id,
                    'command' =>        $command
                );

            }

        return $output;
          
    }
    
    
    /**
    * Sends a SIGKILL (9) to the process_id (pid) given.
    * 
    * 
    * @param int $process_id    // system pid to kill
    * 
    * @returns true if process was killed, false otherwise.
    */
    public static function killProcess( $process_id ){
        
        $process_id = \Altumo\Validation\Numerics::assertUnsignedInteger(
            $process_id,
            '$process_id expects unsinged integer'
        );
        
        $result = \Altumo\Utils\Shell::runWithPipedOutput( "kill -9 {$process_id}" );
        
        return $result == 0;
        
    }

}