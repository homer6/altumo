#!/usr/local/bin/php
<?php
    
    //The database dir must be writable by all users.
    
    
    $project_root = realpath( dirname(__FILE__) . '/../../../' );
    $web_root = $project_root . '/htdocs/project';
    $database_dir = $project_root . '/database';

    require_once(  $web_root . '/lib/build/GitHistory.class.php' );
    require_once(  $web_root . '/lib/build/DatabaseBuildSequenceFile.class.php' );
    require_once(  $web_root . '/lib/vendor/symfony/lib/util/sfFinder.class.php' );


    //get the last commit hash        
        $last_commit_hash = GitHistory::getLastCommitHash();        
        
    //check to see if there are any new scripts in the "new" folder  
        $sql_files = GitHistory::getFilesToMove( $last_commit_hash, $project_root, $database_dir );
        if( empty($sql_files) ){
            exit('No SQL files to move.');
        }
        
    //move the files to the appropriate place and auto-commit
        $move_commands = array();
        
        $has_snapshot = false;
        $has_drop = false;
        $has_upgrade = false;
        
        foreach( $sql_files as $sql_file ){
            
            //detach the extension
                if( preg_match('%^(.*/)(.*?)(\\.sql)?$%im', $sql_file, $regs) ){
                    $file_path = $regs[1];
                    $file_stub = $regs[2];
                    $file_extension = $regs[3];
                }else{
                    exit('Cannot find file with extension.');
                }
                
            //assemble the new filename                
                switch( $file_stub ){
                    
                    case 'drop':
                        $new_filename = $database_dir . '/drops/' . $file_stub . '_' . $last_commit_hash . $file_extension;
                        $has_drop = true;
                        break;
                    
                    case 'upgrade_script':
                        $new_filename = $database_dir . '/upgrade_scripts/' . $file_stub . '_' . $last_commit_hash . $file_extension;
                        $has_upgrade = true;
                        break;
                    
                    case 'snapshot':
                        $new_filename = $database_dir . '/snapshots/' . $file_stub . '_' . $last_commit_hash . $file_extension;
                        $has_snapshot = true;
                        break;
                        
                    default:
                        exit( 'Error: unknown filetype ('. $file_stub . ').' );
                    
                }
            
            //move the file and add it to the git repository
                $move_commands[] = 'git mv ' . $sql_file . ' ' . $new_filename;
                
        }
        
        //so there are no moves executed if one of the files fails to validate
        foreach( $move_commands as $move_command ){
            `$move_command`;
        }
        
    //update the build sequence log
        $database_file = $database_dir . '/build-sequence.xml';
        $xml_build_sequence = new DatabaseBuildSequenceFile( $database_file, false );
        $xml_build_sequence->addChange( $last_commit_hash, $has_upgrade, $has_drop, $has_snapshot );
        $xml_build_sequence->closeFile();
        $shell_command = "git add $database_file";
        `$shell_command`;
                
    //commit the files
        $shell_command = 'git commit -m "Autocommit: moving sql files to appropriate locations for commit ' . $last_commit_hash . '"';
        `$shell_command`;
            
        


        