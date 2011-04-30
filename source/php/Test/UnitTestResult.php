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
* This class represents the outcome of a test assertion.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class UnitTestResult{

    //result outcomes
    const RESULT_SUCCESS = 0;
    const RESULT_FAILURE = 1;
    const RESULT_ERROR = 2;
    
    protected $filename = null;
    protected $line_number = null;
    protected $result = null;
    protected $description = null;

    
    /**
    * Initializes this unit test result with all of the details.
    * 
    * @param string $filename
    * @param integer $line_number
    * @param integer $result
    * @param string $description
    * @return \Altumo\Test\UnitTestResult
    */
    public function __construct( $filename, $line_number, $result = self::RESULT_SUCCESS, $description = null ){
        
        $this->setFilename( $filename );
        $this->setLineNumber( $line_number );
        $this->setResult( $result );
        $this->setDescription( $description );
        
    }
    
    
    /**
    * Setter for the filename field on this UnitTestResult.
    * 
    * @param string $filename
    */
    public function setFilename( $filename ){
    
        $this->filename = $filename;
        
    }
    
    
    /**
    * Getter for the filename field on this UnitTestResult.
    * 
    * @return string
    */
    public function getFilename(){
    
        return $this->filename;
        
    }
        
    
    /**
    * Setter for the line_number field on this UnitTestResult.
    * 
    * @param integer $line_number
    */
    public function setLineNumber( $line_number ){
    
        $this->line_number = $line_number;
        
    }
    
    
    /**
    * Getter for the line_number field on this UnitTestResult.
    * 
    * @return integer
    */
    public function getLineNumber(){
    
        return $this->line_number;
        
    }
        
    
    /**
    * Setter for the result field on this UnitTestResult.
    * 
    * @param integer $result
    */
    public function setResult( $result ){
    
        $this->result = $result;
        
    }
    
    
    /**
    * Getter for the result field on this UnitTestResult.
    * 
    * @return integer
    */
    public function getResult(){
    
        return $this->result;
        
    }
        
    
    /**
    * Setter for the description field on this UnitTestResult.
    * 
    * @param string $description
    */
    public function setDescription( $description ){
    
        $this->description = $description;
        
    }
    
    
    /**
    * Getter for the description field on this UnitTestResult.
    * 
    * @return string
    */
    public function getDescription(){
    
        return $this->description;
        
    }
    
    /**
    * Returns a line with the Result, Filename, Line Number and Description
    * 
    * @return string
    */
    public function getSummary(){
        
        $summary = '';
        switch( $this->getResult() ){
            
            case \Altumo\Test\UnitTestResult::RESULT_SUCCESS:
                $summary = 'Pass: ';      
                break;
            
            case \Altumo\Test\UnitTestResult::RESULT_FAILURE:
                $summary = 'Fail: ';
                break;
            
            case \Altumo\Test\UnitTestResult::RESULT_ERROR:
                $summary = 'Error: ';
                break;

        };
        
        $summary .= $this->getFilename() . ':' . $this->getLineNumber();
        
        $description = $this->getDescription();
        if( $description !== null ){
            $summary .= ' - ' . $description;
        }
        
        return $summary;
        
        
    }
    
        
        
}