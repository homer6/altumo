#!/usr/local/bin/php
<?php
    
    $project_root = realpath( dirname(__FILE__) . '/../../../' );
    $web_root = $project_root . '/htdocs/project';

    try{
        
        require_once(  $web_root . '/lib/build/ApplicationBuilder.class.php' );
            
        $usage = <<<USAGE
usage: build-application.php COMMAND [ARGS]

The available application builder commands are:
   update     Pulls from repository, clears cache, updates database


USAGE;
    
        if( $argc == 1 ){
            echo $usage;
            exit();
        }
        
        $command = $argv[1];
        $arguments = $argv;
        array_shift( $arguments );
        array_shift( $arguments );
        
        $application_builder = new ApplicationBuilder( $project_root );
                
        switch( $command ){
            
            case 'update':
                    $application_builder->update( $arguments );
                    echo "Don't forget to run environment-specific sql statements if this is your first build.\n";
                break;
            
            default:
                echo $usage;
                exit();
            
        }
        
    
    }catch( Exception $e ){
        echo $e->getMessage() . "\n";
    }
    

    

    
    
    
    