<?php

/*
 * This file is part of the Altumo library.
 *
 * (c) Steve Sperandeo <steve.sperandeo@altumo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Altumo\Build;
 

/**
* This class is an object that updates the database based on the current state
* of the database (via the log file) compared to the state of the application
* models (via the database sequence file). It is used to ensure that an 
* environment can be updated, along with the model classes, to ensure that 
* both the database and the class models are always in sync.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class DatabaseBuilder{
    
    const BUILD_TYPE_DROP = 'drop';
    const BUILD_TYPE_UPGRADE_SCRIPT = 'upgrade_script';
    const BUILD_TYPE_SNAPSHOT = 'snapshot';
    
    
    protected $database_builder_configuration_file = null;
    protected $database_build_sequence_file = null;
    protected $database_build_log_file = null;
    
    
    /**
    * Creates a new DatabaseBuilder object
    * 
    * @param DatabaseBuilderConfigurationFile $database_builder_configuration_file
    * @param DatabaseBuildSequenceFile $database_build_sequence_file
    * @param DatabaseBuildLogFile $database_build_log_file
    * @return DatabaseBuilder
    */
    public function __construct( 
            DatabaseBuilderConfigurationFile $database_builder_configuration_file, 
            DatabaseBuildSequenceFile $database_build_sequence_file, 
            DatabaseBuildLogFile $database_build_log_file 
    ){
        
        $this->setDatabaseBuilderConfigurationFile( $database_builder_configuration_file );
        $this->setDatabaseBuildSequenceFile( $database_build_sequence_file );
        $this->setDatabaseBuildLogFile( $database_build_log_file );
                
        $this->initialize();
                
    }
    
    
    /**
    * Sets up the database connection and other startup functionality.
    * This is called from the constructor.
    * 
    * @throws PdoException //if cannot connect to database
    */
    protected function initialize(){
        
        //this is just used to check if 
        $pdo = new PDO(
            'mysql:host=' . $this->getDatabaseBuilderConfigurationFile()->getDatabaseHostname() . ';dbname=' . $this->getDatabaseBuilderConfigurationFile()->getDatabaseName(),
            $this->getDatabaseBuilderConfigurationFile()->getDatabaseUsername(),
            $this->getDatabaseBuilderConfigurationFile()->getDatabasePassword(),
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
        
    }
    
    
    /**
    * Setter for the database_builder_configuration_file field on this DatabaseBuilder.
    * 
    * @param DatabaseBuilderConfigurationFile  $database_builder_configuration_file
    */
    protected function setDatabaseBuilderConfigurationFile( $database_builder_configuration_file ){
    
        $this->database_builder_configuration_file = $database_builder_configuration_file;
        
    }
    
    
    /**
    * Getter for the database_builder_configuration_file field on this DatabaseBuilder.
    * 
    * @return DatabaseBuilderConfigurationFile 
    */
    protected function getDatabaseBuilderConfigurationFile(){
    
        return $this->database_builder_configuration_file;
        
    }
        
    
    /**
    * Setter for the database_build_sequence_file field on this DatabaseBuilder.
    * 
    * @param DatabaseBuildSequenceFile  $database_build_sequence_file
    */
    protected function setDatabaseBuildSequenceFile( $database_build_sequence_file ){
    
        $this->database_build_sequence_file = $database_build_sequence_file;
        
    }
    
    
    /**
    * Getter for the database_build_sequence_file field on this DatabaseBuilder.
    * 
    * @return DatabaseBuildSequenceFile 
    */
    protected function getDatabaseBuildSequenceFile(){
    
        return $this->database_build_sequence_file;
        
    }
        
    
    /**
    * Setter for the database_build_log_file field on this DatabaseBuilder.
    * 
    * @param DatabaseBuildLogFile $database_build_log_file
    */
    protected function setDatabaseBuildLogFile( $database_build_log_file ){
    
        $this->database_build_log_file = $database_build_log_file;
        
    }
    
    
    /**
    * Getter for the database_build_log_file field on this DatabaseBuilder.
    * 
    * @return DatabaseBuildLogFile
    */
    protected function getDatabaseBuildLogFile(){
    
        return $this->database_build_log_file;
        
    }
        
    
    /**
    * Drops all of the tables in this database.
    * 
    * @param array $parameters //CLI Parameters
    * @return integer //number of scripts that were applied
    */
    public function drop( $parameters ){
        
        $script_count = 0;
        
        //get the latest applied hash
            $last_applied_script = $this->getDatabaseBuildLogFile()->getLastLogEntry();
            if( is_null($last_applied_script) ){
                $last_applied_type = null;
                $last_applied_hash = null;                
            }else{
                $last_applied_type = strtolower($last_applied_script->getName());
                $last_applied_hash = $last_applied_script->xpath('attribute::hash');
            }
            
        //exit if last build was a drop
            if( is_null( $last_applied_hash ) || $last_applied_type == self::BUILD_TYPE_DROP ){
                //no work to do, last change was a drop
                return;
            }
            
        //get the last drop and apply it
            $previous_drops = $this->getDatabaseBuildSequenceFile()->getDropHashesBefore( $last_applied_hash );
            if( empty($previous_drops) ){
                throw new \Exception('No previous drops found.');
            }else{
                $this->applyScript( end($previous_drops), self::BUILD_TYPE_DROP );
                ++$script_count;
            }
            
        return $script_count;
            
    }
    
    
    /**
    * Applies any available database build scripts to the current database.
    * Does not apply any that have already been applied to this database.
    * 
    * @param array $parameters //CLI Parameters
    * @return integer //number of scripts that were applied
    */
    public function build( $parameters ){
        
        $script_count = 0;
        
        //get the latest applied hash
            $last_applied_script = $this->getDatabaseBuildLogFile()->getLastLogEntry();
            if( is_null($last_applied_script) ){
                $last_applied_type = null;
                $last_applied_hash = null;
            }else{
                $last_applied_type = strtolower($last_applied_script->getName());
                $last_applied_hash = $last_applied_script->xpath('attribute::hash');
            }
        
        //if empty or if the last command was a drop, assume empty and apply the latest snapshot and all subsequent upgrades
        //else, apply all the unapplied upgrades
            if( is_null( $last_applied_hash ) || $last_applied_type == self::BUILD_TYPE_DROP ){
                
                $snapshot_hash = $this->getDatabaseBuildSequenceFile()->getLastestSnapshotHash();
                if( !$snapshot_hash ){
                    throw new \Exception('There are no snapshots to run.  At least one is required on a new database.');
                }
                $this->applyScript( $snapshot_hash, self::BUILD_TYPE_SNAPSHOT ); 
                ++$script_count;
                                
                $hash = $snapshot_hash;
                
            }else{
                
                $hash = $last_applied_hash;
                
            }
            
            $upgrade_hashes = $this->getDatabaseBuildSequenceFile()->getUpgradeHashesSince( $hash );
            foreach( $upgrade_hashes as $upgrade_hash ){
                $this->applyScript( $upgrade_hash, self::BUILD_TYPE_UPGRADE_SCRIPT );
                ++$script_count;
            }
            
        return $script_count;
        
    }
    
    
    /**
    * Applies a drop, snapshot or upgrade script to the current database.
    * 
    * @param string $hash
    * @param string $build_type
    * @throws \Exception if build_type is unknown
    * @throws \Exception if script file does not exist
    */    
    protected function applyScript( $hash, $build_type = self::BUILD_TYPE_UPGRADE_SCRIPT ){
        
        //validate build type
            if( !in_array($build_type, array( self::BUILD_TYPE_UPGRADE_SCRIPT, self::BUILD_TYPE_DROP, self::BUILD_TYPE_SNAPSHOT ) ) ){
                throw new \Exception('Unknown build type.');
            }
        
        //determine the sql script filename and ensure the file exists
            $database_filename =  $this->getDatabaseBuilderConfigurationFile()->getDatabaseDirectory() . '/' . $build_type . 's/' . $build_type . '_' . $hash . '.sql';
            if( !file_exists($database_filename) ){
                throw new \Exception('Script File ' . $database_filename . ' does not exist.');
            }
        
        //build and run the shell command (using the mysql client)
            $command = "mysql -u" . $this->getDatabaseBuilderConfigurationFile()->getDatabaseUsername() .
                        " -p" . $this->getDatabaseBuilderConfigurationFile()->getDatabasePassword() .
                        " -h" . $this->getDatabaseBuilderConfigurationFile()->getDatabaseHostname() .
                        " " . $this->getDatabaseBuilderConfigurationFile()->getDatabaseName() .
                        " < " . $database_filename;
            `$command`;
        
        //log the action
            switch( $build_type ){
                case self::BUILD_TYPE_UPGRADE_SCRIPT:
                    $this->getDatabaseBuildLogFile()->addUpgrade( $hash );
                    break;
                    
                case self::BUILD_TYPE_DROP:
                    $this->getDatabaseBuildLogFile()->addDrop( $hash );
                    break;
                    
                case self::BUILD_TYPE_SNAPSHOT:
                    $this->getDatabaseBuildLogFile()->addSnapshot( $hash );
                    break;
                    
                default:
                    throw new \Exception('Unknown build type.');
                
            }
                
        
    }
    
    
}
