<?php
    
    $project_root = realpath( dirname(__FILE__) . '/../../../' );
    $web_root = $project_root . '/htdocs/project';
    $database_dir = $project_root . '/database';
    $cli_path = dirname(__FILE__);

    try{
        
        $shell_file = $cli_path . '/connect-database.sh';

        //always write the shell file before executing: parameters may have changed
            require_once(  $web_root . '/lib/build/DatabaseBuilderConfigurationFile.class.php' );
            
            $default_builder_configuration_file = $database_dir . '/builder-configuration.xml';

            $xml_builder_configuration = new DatabaseBuilderConfigurationFile( $default_builder_configuration_file );
            
            
            $command = "mysql -u" . $xml_builder_configuration->getDatabaseUsername() .
                        " -p" . $xml_builder_configuration->getDatabasePassword() .
                        " -h" . $xml_builder_configuration->getDatabaseHostname() .
                        " " . $xml_builder_configuration->getDatabaseName() . "\n";
            
            umask(0022);            
            file_put_contents( $shell_file, $command );
            chmod( $shell_file, 0755 );

    
    }catch( Exception $e ){
        echo $e->getMessage() . "\n";
    }
    
