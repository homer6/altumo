#!/usr/local/bin/php
<?php
    
    $project_root = realpath( dirname(__FILE__) . '/../../../' );
    $web_root = $project_root . '/htdocs/project';
    $database_dir = $project_root . '/database';

    try{
        
        require_once(  $web_root . '/lib/build/DatabaseBuilder.class.php' );
        
    
        $usage = <<<USAGE
usage: build-database.php COMMAND [ARGS]

The available database builder commands are:
   build      Modifies an existing database according to available build files
   drop       Drops all of the tables in the database
   init       Create an empty database configuration file


USAGE;
    
        if( $argc == 1 ){
            echo $usage;
            exit();
        }

        $default_build_sequence_file = $database_dir . '/build-sequence.xml';
        $default_build_log_file = $database_dir . '/build-log.xml';
        $default_builder_configuration_file = $database_dir . '/builder-configuration.xml';
        
        $command = $argv[1];
        $arguments = $argv;
        array_shift( $arguments );
        array_shift( $arguments );
        
        //initialize builder, if there is meaningful work to do
            if( in_array( $command, array('build', 'drop') ) ){
                $xml_build_sequence = new DatabaseBuildSequenceFile( $default_build_sequence_file );
                $xml_build_log = new DatabaseBuildLogFile( $default_build_log_file, false );
                $xml_builder_configuration = new DatabaseBuilderConfigurationFile( $default_builder_configuration_file );
                $xml_builder_configuration->setDatabaseDirectory( $database_dir );
                
                $database_builder = new DatabaseBuilder( $xml_builder_configuration, $xml_build_sequence, $xml_build_log );
            }        
                
        switch( $command ){
            
            case 'build':
                    $number_of_scripts_executed = $database_builder->build( $arguments );
                    
                break;
                
            case 'drop':
                    $number_of_scripts_executed = $database_builder->drop( $arguments );   
            
                break;
                
            case 'init':                    
                    $xml_builder_configuration = new DatabaseBuilderConfigurationFile( $default_builder_configuration_file, false );
                    $xml_build_log = new DatabaseBuildLogFile( $default_build_log_file, false );
                    $number_of_scripts_executed = 0;
                break;
            
            default:
                echo $usage;
                exit();
            
        }
        
        echo $number_of_scripts_executed . ' scripts executed successfully.' . "\n" ;
        
    
    }catch( Exception $e ){
        echo $e->getMessage() . "\n";
    }
    

    

    
    
    
    