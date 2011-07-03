<?php
  

/**
* This class is used to retrieve the git revision history.
* 
* 
*/
class GitHistory{
    
    
    /**
    * Gets an array of sql files to move to the appropriate directories.
    * Files must be in the database/new folder AND be in the current commit to qualify for being moved.
    * 
    * @param string $commit_hash
    * @param string $project_root  //full pathname of the project root (without the trailing slash)
    * @param string $database_dir  //full pathname of the database directory (without the trailing slash)
    * @return array
    */
    static public function getFilesToMove( $commit_hash, $project_root, $database_dir ){
        
          
        $sql_files_in_filesystem = \Altumo\Utils\Finder::type('file')->name('*.sql')->in( $database_dir . '/new' );
        
        $sql_files_in_commit = self::getNewDatabaseFilesByCommit( $commit_hash, $project_root );
        
        $move_sql_files = array();
        foreach( $sql_files_in_filesystem as $sql_file_in_filesystem ){
            if( in_array($sql_file_in_filesystem, $sql_files_in_commit) ){
                $move_sql_files[] = $sql_file_in_filesystem;
            }
        }
        
        return $move_sql_files;
        
    }    
    
    
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
    * Gets an array of files that were in the supplied commit
    * 
    * @param string $commit_hash
    * @param string $project_root  //full pathname of the project root (without the trailing slash)
    * 
    * @return array
    */
    static public function getNewDatabaseFilesByCommit( $commit_hash, $project_root ){
        
        $git_command = 'git show --name-status ' . $commit_hash;
        $git_output = `$git_command`;
           
        $files = array();
        preg_match_all( '%^(([DMA])\\s+(database/new/(.*)\\.sql))?$%im', $git_output, $results, PREG_SET_ORDER );     
        foreach( $results as $result ){
            if( array_key_exists(3,$result) ){
                $files[] = $project_root . '/' . $result[3];
            }            
        }
        
        return $files;

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

