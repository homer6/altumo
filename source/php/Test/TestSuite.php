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




namespace Altumo\Test;


/**
* This class is the entry point the run all unit tests. It is called directly 
* from the CLI (or web application).
* 
* To run tests, you pass the directory that contains the tests to the
* constructor. Any file that has a filename that follows the pattern:
* xxxxTest.php and contains a class that inherits from \Altumo\Test\UnitTest
* will be run. 
* 
* Within each UnitTest class, if there is a "run()" method defined, the test
* suite will only invoke that one method. However, if no "run()" method is
* defined, each method whose name begins with "test" will be invoked in the 
* order that they're defined. 
* 
* If the method "setup" exist, it'll be run before all of the tests.
* If the method "tearDown" exist, it'll be run after all of the tests.
* 
* Each unit test is instantiated only once for all of the unit tests, so you
* can define protected members or methods to setup, tear down, or whatever.
* 
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class TestSuite{
    
    protected $results = array();
    protected $counts = array();
    
    
    /**
    * Runs all of the unit tests in the suppied directory (and all of the 
    * subdirectories).
    * 
    * @param string $test_directory
    * @return TestSuite
    */
    public function __construct( $test_directory ){
        
        $this->counts['pass'] = 0;
        $this->counts['fail'] = 0;
        $this->counts['error'] = 0;
        $this->counts['total'] = 0;
        
        $this->runTests( $test_directory );
        
    }
    
    
    /**
    * Runs all of the unit tests in the suppied directory (and all of the 
    * subdirectories).
    * 
    * @param string $test_directory
    */
    protected function runTests( $test_directory ){
        
        $files = \Altumo\Utils\Finder::type('file')->name('*Test.php')->in( $test_directory );
        
        foreach( $files as $file ){

            //determine the class name
                $class_name = '\\Altumo\\Test\\' . basename( $file, '.php' );
            
            //skip the base class
                if( $class_name == '\\Altumo\\Test\\UnitTest' ){
                    continue;
                }
                
            //include the file
                require_once( $file );
            
            //display which unit test is being run
                $this->output( "\n" . $file . ':' );
            
            //instantiate and run the unit test
                $unit_test = new $class_name( $this );
                
                if( method_exists( $unit_test, 'setup' ) ){
                    $unit_test->setup();
                }
                
                if( method_exists( $unit_test, 'run' ) ){
                    
                    $unit_test->run();
                    
                }else{
                    
                    $methods = get_class_methods( $class_name );
                    
                    foreach( $methods as $method ){
                        
                        if( preg_match('/^test(.*?)$/', $method, $regs) ){
                            $test_name = $regs[1];
                            $this->output( "\n\t" . $test_name . ':' );
                            $unit_test->$method();
                        }
                        
                    }
                    
                }
                    
                if( method_exists( $unit_test, 'tearDown' ) ){
                    $unit_test->tearDown();
                }
                
                $this->output( "\n" );
            
        }
        
        $this->displayReport();
        
    }
    
    
    /**
    * Writes the output to a location. Defaults to stdout.
    * 
    * @param string $line
    */
    public function output( $line ){
        
        echo $line;
        
    }
        
    
    /**
    * Getter for the results field on this TestSuite.
    * 
    * @return array
    */
    protected function getResults(){
    
        return $this->results;
        
    }
    
    
    /**
    * Adds a test result to this TestSuite.
    * 
    * @param \Altumo\Test\UnitTestResult $result
    */
    public function addResult( $result ){
    
        $this->output( '.' );
        $this->counts['total']++;
        
        
        switch( $result->getResult() ){
            
            case \Altumo\Test\UnitTestResult::RESULT_SUCCESS:
                $this->counts['pass']++;
                break;
                
            case \Altumo\Test\UnitTestResult::RESULT_FAILURE:
                $this->counts['fail']++;
                $this->output( "\n\t\t" . $result->getSummary() );
                break;                
            
            case \Altumo\Test\UnitTestResult::RESULT_ERROR:
                $this->counts['error']++;
                $this->output( "\n\t\t" . $result->getSummary() );
                break;
        };
        
        $this->results[] = $result;
        
    }
    
    
    /**
    * Adds a test result to this TestSuite.
    * 
    */
    public function displayReport(){
    
        $this->output( "\n" );
        $this->output( "\nPass: " . $this->counts['pass'] . '/' . $this->counts['total'] );
        $this->output( "\nFail: " . $this->counts['fail'] );
        $this->output( "\nError: " . $this->counts['error'] );
                
        $this->output( "\n\n" );
        
    }
    
    
}