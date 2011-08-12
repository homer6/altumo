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
* This class facilitates easy writing to a log file on the web server for 
* debugging purposes.
* 
* Defaults to a filename called "console.log" in the system temp directory.
*   eg. /tmp/console.log
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class Console{
    
    protected $filename = null;
    protected $file_pointer = null;
    
    
    /**
    * Creates a Console object. Opens the filename provided.
    * Defaults to a filename called "console.log" in the system temp directory.
    *   eg. /tmp/console.log
    * 
    * 
    * @param string $filename
    * @throws \Exception                    //if the directory location or file 
    *                                         is not writable or couldn't open
    * @throws \Exception                    //if $filename is provided and isn't
    *                                         a string
    * @return \Altumo\Utils\Console
    */
    public function __construct( $filename = null ){
        
        if( is_null($filename) ){            
            $filename = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'console.log';            
        }
        
        if( !is_string($filename) ){
            throw new \Exception('Filename is expected to be a string.');
        }
        
        $file_pointer = fopen( $filename, 'a+' );
        if( $file_pointer === false ){
            throw new \Exception('Could not console log file for writing.');
        }
        
        $this->setFilePointer( $file_pointer );
        $this->setFilename( $filename );
        
    }
    
    
    /**
    * Destructor. Closes the file and ends the session.
    * 
    */
    public function __destruct(){
                        
        fclose( $this->getFilePointer() );
        
    }
    
       
    /**
    * Writes a message to the console log file.
    * 
    * @param string $message
    */
    public function write( $message ){
        
        fwrite( $this->getFilePointer(), $message . "\n\n" );
        
    }
    
       
    /**
    * Outputs (to the console log) the variables passed to this function in a 
    * format like var_dump.
    * This is a polyvaradic function.  You can pass many parameters.
    * 
    */
    public function writeDump(){
        
        $args = func_get_args();
        if( empty($args) ) return;
        ob_start();
        foreach( $args as $arg ){
            var_dump($arg);
        }
        $output = ob_get_contents();
        ob_end_clean();
        
        $this->write($output);
        
    }
    
    
    /**
    * Outputs (to the console log) the variables passed to this function in a 
    * format like var_dump.
    * This is a polyvaradic function.  You can pass many parameters.
    *    
    * @throws \Exception                    //if dump() coun't write to the log 
    *                                         file couldn't write
    */
    static public function dump(){
        
        $args = func_get_args();
        if( empty($args) ) return;
                
        $console = new \Altumo\Utils\Console();
        call_user_func_array( array($console, 'writeDump'), $args );
        unset($console);
        
    }
    
    
    /**
    * Setter for the filename field on this Console.
    * 
    * @param string $filename
    */
    protected function setFilename( $filename ){
    
        $this->filename = $filename;
        
    }
    
    
    /**
    * Getter for the filename field on this Console.
    * 
    * @return string
    */
    public function getFilename(){
    
        return $this->filename;
        
    }
    
    
    /**
    * Setter for the file_pointer field on this Console.
    * 
    * @param resource $file_pointer
    */
    protected function setFilePointer( $file_pointer ){
    
        $this->file_pointer = $file_pointer;
        
    }
    
    
    /**
    * Getter for the file_pointer field on this Console.
    * 
    * @return resource
    */
    protected function getFilePointer(){
    
        return $this->file_pointer;
        
    }
        
    
}
        