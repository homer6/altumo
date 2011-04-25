<?php

/*
 * This file is part of the Altumo library.
 *
 * (c) Steve Sperandeo <steve.sperandeo@altumo.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace Altumo\Arrays;
 
   


 /**
 * This class contains a number of array helper functions.
 * 
 * @author Steve Sperandeo <steve.sperandeo@altumo.com>
 */
class Arrays{

    /**
    * Recursively merges many arrays into a single complex array.
    * This is a polyvariadic method (it takes an infinite number of parameters).
    * $array3 will overwrite(merge) $array2, then $array2 will overwrite(merge)
    * $array1, which will then be returned.
    * 
    * Numeric keys are lost.  This treats arrays with numeric values as lists,
    * pushing the new values onto the list, instead of taking array index 0 and 
    * overwriting array index 0.  For the opposite behavior:
    * @see \Altumo\Arrays\Arrays::mergeArraysRecursivelyAsHashes
    * 
    * @signature array mergeArraysRecursivelyAsLists ( array $array1 [, array $array2 [, array $... ]] )
    * 
    * @param array $array1 
    * @param array $array2 
    * @param array $array3 
    * ...
    * 
    * This method is similar to the php function "array_merge_recursive" except
    * that function a few quirks that I didn't like.
    * @see http://ca3.php.net/manual/en/function.array-merge-recursive.php
    * 
    * @throws \Exception //if not all parameters were array
    * @throws \Exception //if there were no parameters passed
    * @return array
    */
    static public function mergeArraysRecursivelyAsLists(){
        
        //get all of the arrays passed
            $arrays = func_get_args();
        
        //validate that each of the parameters passed are arrays
            foreach( $arrays as $array ){
                if( !is_array($array) ){
                    throw new \Exception('Not all parameters passed were arrays.');
                }
            }
        
        $array_count = count($arrays);
            
        
        //if there aren't any arrays passed, throw exception
            if( $array_count == 0  ){
                throw new \Exception('This function requires at least two arrays as parameters.');            
            }
            
        //if there's only one, return it
            if( $array_count == 1 ){
                return $arrays[0];
            }
        
        /**
        * start at the last two arrays passed and compare them, passing the
        * array values down to the first array.
        */    
        for( $x = $array_count - 2; $x >= 0; $x-- ){
            
            //iterate over each of the keys
            foreach( $arrays[$x+1] as $key => $value ){
                
                //if there is an intersecting key, that is an array, merge them recursively
                if( \Altumo\Validation\Numerics::isInteger($key) ){                
                    $arrays[$x][] = $arrays[$x+1][$key];
                }else if( array_key_exists($key, $arrays[$x]) ){
                    if( is_array($arrays[$x][$key]) && is_array($arrays[$x+1][$key]) ){
                        $arrays[$x][$key] = self::mergeArraysRecursivelyAsLists( $arrays[$x][$key], $arrays[$x+1][$key] );
                    }else{
                        $arrays[$x][$key] = $arrays[$x+1][$key];
                    }
                }else{
                    $arrays[$x][$key] = $arrays[$x+1][$key];
                }
                
            }
        }
        
        return $arrays[0];
        
    }
    
    
    /**
    * Recursively merges many arrays into a single complex array.
    * This is a polyvariadic method (it takes an infinite number of parameters).
    * 
    * This method is identical to mergeArraysRecursivelyAsLists except that this 
    * method overwrites the numeric keys, instead of pushing them onto the list.
    * 
    * Numeric keys are retained, but overwritten.  For example, array index 0 
    * will overwrite array index 0.
    * 
    * @signature array mergeArraysRecursivelyAsHashes ( array $array1 [, array $array2 [, array $... ]] )
    * 
    * @param array $array1 
    * @param array $array2 
    * @param array $array3 
    * ...
    * 
    * This method is similar to the php function "array_merge_recursive" except
    * that function a few quirks that I didn't like.
    * @see http://ca3.php.net/manual/en/function.array-merge-recursive.php
    * 
    * @throws \Exception //if not all parameters were array
    * @throws \Exception //if there were no parameters passed
    * @return array
    */
    static public function mergeArraysRecursivelyAsHashes(){
        
        //get all of the arrays passed
            $arrays = func_get_args();
        
        //validate that each of the parameters passed are arrays
            foreach( $arrays as $array ){
                if( !is_array($array) ){
                    throw new \Exception('Not all parameters passed were arrays.');
                }
            }
        
        $array_count = count($arrays);
            
        
        //if there aren't any arrays passed, throw exception
            if( $array_count == 0  ){
                throw new \Exception('This function requires at least two arrays as parameters.');            
            }
            
        //if there's only one, return it
            if( $array_count == 1 ){
                return $arrays[0];
            }
        
        /**
        * start at the last two arrays passed and compare them, passing the
        * array values down to the first array.
        */    
        for( $x = $array_count - 2; $x >= 0; $x-- ){
            
            //iterate over each of the keys
            foreach( $arrays[$x+1] as $key => $value ){
                
                //if there is an intersecting key, that is an array, merge them recursively
                if( array_key_exists($key, $arrays[$x]) ){
                    if( is_array($arrays[$x][$key]) && is_array($arrays[$x+1][$key]) ){
                        $arrays[$x][$key] = self::mergeArraysRecursivelyAsHashes( $arrays[$x][$key], $arrays[$x+1][$key] );
                    }else{
                        $arrays[$x][$key] = $arrays[$x+1][$key];
                    }
                }else{
                    $arrays[$x][$key] = $arrays[$x+1][$key];
                }
                
            }
        }
        
        return $arrays[0];
        
    }
    
    
    /**
    * Recursively removes all of the array keys that have the value of null.
    * 
    * @param array $array
    * @throws \Exception //if $array was not an array
    * @return array
    */
    static public function removeNullValuesRecursively( $array ){
        
        //validate
            if( !is_array($array) ){
                throw new \Exception('Not all parameters passed were arrays.');
            }
            
        //remove all nulls
            foreach( $array as $key => $value ){
                if( is_null($value) ){
                    unset($array[$key]);
                }else if( is_array($array[$key]) ){
                    $array[$key] = self::removeNullValuesRecursively($array[$key]);
                }
            }
            
        return $array;
        
    }
    
    
    /**
    * Retrieve a value from $array by $key if $array[$key] is set, or return null otherwise
    * 
    * @author Juan Jaramillo <juan.jaramillo@altumo.com>
    * 
    * @param array $array
    * @param string $key    // array key
    * @param mixed $default // default value to return if $array[$key] is not set
    * @throws \Exception    // if $array was not an array or if $key is not a non-empty string
    * @return mixed         // value of $array[$key] if it is set, or $default
    */
    static public function getValueIfSet( $array, $key, $default = null ){

        //validate
            if( !is_array($array) ){
                throw new \Exception('The first parameter must be an array.');
            }            

            try {
                \Altumo\Validation\Strings::assertNonEmptyString( $key );
            } catch ( \Exception $e ){
                throw new \Exception( 'The second parameter must be a non-empty string.' );
            }


        // Retrieve Value   
            if( isset($array[$key]) ){
                return $array[$key];
            }


        return $default;
        
    }
    
    
    
    /**
    * If the $key specified exists in $array, then
    * $callback will be invoked with $array[$key] as a parameter. 
    * 
    * @param array $array 
    * @param string $key        // Non-empty name of a key to find in $array
    * @param array $callback    // A php "callable" to use as a callback
    * 
    * @throws \Exception        //if $array is not an array, $key is not a string or $callback is not callable
    * @return mixed             // whatever the response of $callback was (if invoked) or false otherwise.
    */
    static public function callIfKeyExists( $array, $key, $callback ){
        
        //validate
            if( !is_array($array) ){
                throw new \Exception( 'The first parameter must be an array.' );
            }            
            
            try {
                \Altumo\Validation\Strings::assertNonEmptyString( $key );
            } catch ( \Exception $e ){
                throw new \Exception( 'The second parameter must be a non-empty string.' );
            }
            
            if( !is_callable( $callback ) ){
                throw new \Exception( 'The third parameter must be a callable' );
            }
            
            
            
        // If key is set, call apply function
            if( isset( $array[$key] ) ){
                return call_user_func( $callback, $array[$key] );
            }
        
        return false;

    }
}