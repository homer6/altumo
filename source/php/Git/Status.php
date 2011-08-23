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
* This class is used for retrieving information about a Git working tree.
* 
* @author Juan Jaramillo <juan.jaramillo@altumo.com>
*/
class Status{


    /**
    * Get an array of all the changed items in the working copy.
    * 
    * @return array
    *   // of files or directories modified as returned by status
    */
    static public function getChanges(){
        
        $git_status_output = `git status --porcelain`;
        
        return self::parseStatusOutput( $git_status_output );
        
    }    
    
    
    /**
    * Returns true if the current working copy has uncommitted changes.
    * 
    * @return bool
    */
    static public function hasChanges(){
        
        return count( self::getChanges() ) > 0;
        
    }
    
    
    /**
    * Get the hash of the commit where the tree is sitting at right now
    * 
    * @return string
    *   // the full hash of the commit where the working tree is at
    */
    static public function getCurrentHash(){
        
        $current_hash = `git rev-parse HEAD`;
        return trim( $current_hash );
        
    }    
    
    
    /**
    * Get the name of the current branch. If in "detached HEAD" mode, 
    * the hash of the current commit will be returned instead.
    * 
    * @return string
    *   // the name of the current branch or commit hash if in detached HEAD mode.
    */
    static public function getCurrentBranch(){
        
        $current_hash = `git name-rev --name-only HEAD`;
        return trim( $current_hash );
        
    }
    
    
    /**
    * Parses the output of git-status into an array. Assumes the "porcelain"
    * option was used to generate the output.
    * 
    * @param string $output
    *   // output from a git-status call to parse
    */
    static protected function parseStatusOutput( $output ){
        
        $changed_items = array();
        
        if( preg_match_all( '/^.+?\\s(.*)$/m', $output, $changes, PREG_SET_ORDER ) ){
            foreach( $changes  as $changed_item ){
                
                //TEMPORARY.. REMOVE ME.
                    if( preg_match( '/sfAltumoPlugin/m', $changed_item[1] ) ){
                        continue;
                    }
                
                $changed_items[] = $changed_item[1];
                
            }
        }
        
        return $changed_items;

    }
    
}