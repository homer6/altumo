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
* Useds for manipulating Person Names
* 
* @author Juan Jaramillo <juan.jaramillo@altumo.com>
*/
class PersonName {

    protected $first_name = null;
    protected $last_name = null;


    /**
    * Makes a new instance of PersonName
    * 
    * @return \Altumo\Utils\PersonName
    */
    public function create(){
        
        return new \Altumo\Utils\PersonName();
        
    }


    /**
    * Setter for the first_name field on this PersonName.
    * 
    * @param string $first_name
    * @return \Altumo\Utils\PersonName
    */
    public function setFirstName( $first_name ){
    
        $this->first_name = $first_name;
        
        return $this;
        
    }


    /**
    * Getter for the first_name field on this PersonName.
    * 
    * @return string
    */
    public function getFirstName(){
    
        return $this->first_name;
        
    }


    /**
    * Setter for the last_name field on this PersonName.
    * 
    * @param string $last_name
    * @return \Altumo\Utils\PersonName
    */
    public function setLastName( $last_name ){
    
        $this->last_name = $last_name;
        
        return $this;
        
    }


    /**
    * Getter for the last_name field on this PersonName.
    * 
    * @return string
    */
    public function getLastName(){
    
        return $this->last_name;
        
    }


    /**
    * Set Full Name. This will attempt to parse the full name into first 
    * and last name.
    * 
    * @param string $full_name
    * @return \Altumo\Utils\PersonName
    */
    public function setFullName( $full_name ){

        if( is_null( $full_name ) ){
            $this->setFirstName( null );
            $this->setLastName( null );
        }

        $parsed_full_name = self::parsePersonFullName( $full_name );
        
        if( strlen( $parsed_full_name['first_name'] ) > 0 ){
            
            $this->setFirstName( $parsed_full_name['first_name'] . ( empty( $parsed_full_name['middle_name'] ) ? '' : ' ' ) . $parsed_full_name['middle_name'] );
            
        }
        
        if( strlen( $parsed_full_name['last_name'] ) > 0 ){
            
            $this->setLastName( $parsed_full_name['last_name'] );
            
        }
        
        return $this;
        
    }

    
    /**
    * Parses the Person's name and attempts to extract First, Middle and/or 
    * Last name.
    * 
    * @param string $full_name 
    *   // the full name to parse
    * 
    * @param string $get_part 
    *   // (first_name|middle_name|last_name|null) if null, an array of parts 
    *       will be returned.
    * 
    * @throws \Exception                    
    *   // if $get_part is invalid
    * 
    * 
    * @return string|array
    */
    protected static function parsePersonFullName( $full_name, $get_part = null ){

        // Validate Input
            if( !is_null($get_part) ){
                $valid_parts = array( 'first_name', 'middle_name', 'last_name' );
                if( !in_array($get_part, $valid_parts) ){
                    throw new \Exception( 'parsePersonFullName expects get_part to be one of: ' . implode( ', ', $valid_parts)  );
                }
            }

        // Split full name on spaces
            $name_parts = explode( ' ', trim( $full_name ) );


        // Parse name parts
            switch( count( $name_parts ) ){

                case 0:
                    return null;
                    break;

                case 1:
                    $parsed_name = array(
                        'first_name' => $name_parts[0],
                        'middle_name' => '',
                        'last_name' => ''
                    );
                    break;

                case 2:
                    $parsed_name = array(
                        'first_name' => $name_parts[0],
                        'middle_name' => '',
                        'last_name' => $name_parts[1]
                    );
                    break;

                case 3:
                    $parsed_name = array(
                        'first_name' => $name_parts[0],
                        'middle_name' => $name_parts[1],
                        'last_name' => $name_parts[2]
                    );
                    break;

                default:
                    $parsed_name = array(
                        'first_name' => $name_parts[0],
                        'middle_name' => $name_parts[1],
                        'last_name' => implode( ' ', array_slice( $name_parts, 2 ) )
                    );
            }
        
        if( !is_null( $get_part ) ){
            return $parsed_name[$get_part];
        } else {
            return $parsed_name;
        }
        
    }
    
}