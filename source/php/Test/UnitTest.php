<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/




namespace Altumo\Test;
 
/**
* This class holds all of the unit tests. It is the base class for all unit
* tests. It is designed to be extended.
* 
* Within each UnitTest class, if there is a "run()" method defined, the test
* suite will only invoke that one method (so you can control the order that 
* the tests run in). However, if no "run()" method is defined, each method 
* whose name begins with "test" will be invoked in the order that they're 
* defined. 
* 
* Each unit test is instantiated only once for all of the unit tests, so you
* can define protected members or methods to setup, tear down, or whatever.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class UnitTest{
    
    protected $test_suite = null;

    /**
    * Initializes this unit test with a reference to the calling test suite.
    * 
    * @param \Altumo\Test\TestSuite $test_suite
    * @return \Altumo\Test\UnitTest
    */
    public function __construct( $test_suite ){
        
        $this->setTestSuite( $test_suite );
        
    }

    
    /**
    * Asserts that this $value is true. Records the outcome.
    * 
    * @param boolean $value
    * @param string $description            //a description of the assertion
    *                                         eg. X should be 5.
    */
    public function assertTrue( $value, $description = null ){
        
        $exception = new \Exception();        
        $trace = $exception->getTrace();
        
        //var_dump($trace);
        // exit();
        
        array_shift($trace);
        $call = array_shift($trace);
        
        $filename = $call['file'];
        $line_number = $call['line'];
        
        if( !is_bool($value) ){
            $result = new \Altumo\Test\UnitTestResult( $filename, $line_number, $result = \Altumo\Test\UnitTestResult::RESULT_ERROR, $description );
        }else{
            if( $value === true ){
                $result = new \Altumo\Test\UnitTestResult( $filename, $line_number, $result = \Altumo\Test\UnitTestResult::RESULT_SUCCESS, $description );
            }else{
                $result = new \Altumo\Test\UnitTestResult( $filename, $line_number, $result = \Altumo\Test\UnitTestResult::RESULT_FAILURE, $description );
            }
        }
    
        $this->getTestSuite()->addResult( $result );

    }   
    
    /**
    * Setter for the test_suite field on this UnitTest.
    * 
    * @param \Altumo\Test\TestSuite $test_suite
    */
    protected function setTestSuite( $test_suite ){
    
        $this->test_suite = $test_suite;
        
    }
    
    
    /**
    * Getter for the test_suite field on this UnitTest.
    * 
    * @return \Altumo\Test\TestSuite
    */
    protected function getTestSuite(){
    
        return $this->test_suite;
        
    }
    
    /**
    * Writes the output to a location. Defaults to stdout.
    * 
    * @param string $line
    */
    protected function output( $line ){
        
        $this->getTestSuite()->output( $line );
        
    }
    
    
}