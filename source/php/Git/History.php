<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Altumo\Git;


/**
* This class is used to retrieve the git revision history.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class History{
    
    

    /**
    * Gets the last commit as an array.
    * The SHA1 is the array key and the value is a string with the comments of the commit (on one line)
    * 
    * @return array
    */
    static public function getLastCommit(){
        
        //get the revision log
            $git_log_output = `git log --pretty=oneline -n 1`;
            $revisions = self::parseOneLineFormat( $git_log_output );
            return $revisions;
        
    }
    
    
    /**
    * Gets the last commit as a string hash.
    * 
    * @return string
    */
    static public function getLastCommitHash(){
        
        //get the revision log        
            $last_commit = self::getLastCommit();
            return reset(array_keys($last_commit));
        
    }    

    
    /**
    * Parses the output from git log into an array.
    *  eg. git log --pretty=oneline
    * 
    *    returned array looks like this:
    *      array(2) {
    *         ["7b8e0cee0a58f05ef9e48e6daabfa07c3ebe728d"]=>
    *         string(34) "Fixed bug in Not interested button"
    *         ["d5180559dbc1d39fb802a17b832b9e52f3f2a964"]=>
    *         string(17) "Updated task list"
    *      }
    *
    * @param string $git_log_result
    * @return array
    */
    static protected function parseOneLineFormat( $git_log_result ){
        
        $revisions = array();
        preg_match_all( '/^(.*?)\\s(.*?)$/m', $git_log_result, $results, PREG_SET_ORDER );    
        foreach( $results as $result ){
            $revisions[ $result[1] ] = $result[2];
        }
        return $revisions;
        
    }
    
    
}  

