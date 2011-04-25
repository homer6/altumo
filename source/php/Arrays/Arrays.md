
#\Altumo\Arrays\Arrays

The \Altumo\Arrays\Arrays class is a collection of static methods for manipulating arrays.


## \Altumo\Arrays\Arrays::mergeArraysRecursivelyAsLists

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
    static public function mergeArraysRecursivelyAsLists();
    
    
### Usage

    $array_one = array(
        'name' => 'Ben',
        'age' => array(
            'green' => 'apple',
            'information' => 'underload',
            'mister' => array(
                array(
                    4234,
                    423423,
                    23423
                ),
                array(
                    34234,
                    23423,
                    23423
                )
            )
        ),
        'occupation' => 'Rancher',
        'height' => '165cm'
    );


    $array_two = array(
        'name' => 'Frank',
        'age' => array(
            'information' => 'overload',
            123 => 4324234,
            'mister' => array(
                array( 'safsdkfjsdkfljds', 12321, 12312 )
            )   
        ),
        'occupation' => 'Cowboy'
    );


    $array_three = array(
        'name' => 'Yummy',
        'dessert' => 'Jumper'
    );

    $result = \Altumo\Arrays\Arrays::mergeArraysRecursivelyAsLists( $array_one, $array_two, $array_three );

    \Altumo\Utils\Debug::dump($result);

### Output

    array(5) {
      ["name"]=>
      string(5) "Yummy"
      ["age"]=>
      array(4) {
        ["green"]=>
        string(5) "apple"
        ["information"]=>
        string(8) "overload"
        ["mister"]=>
        array(3) {
          [0]=>
          array(3) {
            [0]=>
            int(4234)
            [1]=>
            int(423423)
            [2]=>
            int(23423)
          }
          [1]=>
          array(3) {
            [0]=>
            int(34234)
            [1]=>
            int(23423)
            [2]=>
            int(23423)
          }
          [2]=>
          array(3) {
            [0]=>
            string(16) "safsdkfjsdkfljds"
            [1]=>
            int(12321)
            [2]=>
            int(12312)
          }
        }
        [0]=>
        int(4324234)
      }
      ["occupation"]=>
      string(6) "Cowboy"
      ["height"]=>
      string(5) "165cm"
      ["dessert"]=>
      string(6) "Jumper"
    }




## \Altumo\Arrays\Arrays::mergeArraysRecursivelyAsHashes

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
    static public function mergeArraysRecursivelyAsHashes();
    
    
### Usage


    $array_one = array(
        'name' => 'Ben',
        'age' => array(
            'green' => 'apple',
            'information' => 'underload',
            'mister' => array(
                array(
                    4234,
                    423423,
                    23423
                ),
                array(
                    34234,
                    23423,
                    23423
                )
            )
        ),
        'occupation' => 'Rancher',
        'height' => '165cm'
    );


    $array_two = array(
        'name' => 'Frank',
        'age' => array(
            'information' => 'overload',
            123 => 4324234,
            'mister' => array(
                array( 'safsdkfjsdkfljds', 12321, 12312 )
            )   
        ),
        'occupation' => 'Cowboy'
    );


    $array_three = array(
        'name' => 'Yummy',
        'dessert' => 'Jumper'
    );

    $result = \Altumo\Arrays\Arrays::mergeArraysRecursivelyAsHashes( $array_one, $array_two, $array_three );

    \Altumo\Utils\Debug::dump($result);

### Output

    array(5) {
      ["name"]=>
      string(5) "Yummy"
      ["age"]=>
      array(4) {
        ["green"]=>
        string(5) "apple"
        ["information"]=>
        string(8) "overload"
        ["mister"]=>
        array(2) {
          [0]=>
          array(3) {
            [0]=>
            string(16) "safsdkfjsdkfljds"
            [1]=>
            int(12321)
            [2]=>
            int(12312)
          }
          [1]=>
          array(3) {
            [0]=>
            int(34234)
            [1]=>
            int(23423)
            [2]=>
            int(23423)
          }
        }
        [123]=>
        int(4324234)
      }
      ["occupation"]=>
      string(6) "Cowboy"
      ["height"]=>
      string(5) "165cm"
      ["dessert"]=>
      string(6) "Jumper"
    }



## \Altumo\Arrays\Arrays::removeNullValuesRecursively

    /**
    * Recursively removes all of the array keys that have the value of null.
    * 
    * @param array $array
    * @throws \Exception //if $array was not an array
    * @return array
    */
    static public function removeNullValuesRecursively( $array );
    
    
### Usage 1

    $array_one = array(
        'name' => null,
        'dessert' => 'Jumper'
    );

    $result = \Altumo\Arrays\Arrays::removeNullValuesRecursively( $array_one );

    \Altumo\Utils\Debug::dump($result);

### Output 1

    array(1) {
      ["dessert"]=>
      string(6) "Jumper"
    }

    
    
### Usage 2

    $array_one = array(
        'name' => 'Ben',
        'hello2' => null,
        'age' => array(
            'green' => 'apple',
            'information' => 'underload',
            'miss' => null,
            'mister' => array(
                array(
                    4234,
                    423423,
                    23423
                ),
                array(
                    34234,
                    23423,
                    23423
                ),
                null
            )
        ),
        'occupation' => 'Rancher',
        'height' => '165cm',
        'hello' => null
    );

    $result = \Altumo\Arrays\Arrays::removeNullValuesRecursively( $array_one );

    \Altumo\Utils\Debug::dump($result);

### Output 2

    array(4) {
      ["name"]=>
      string(3) "Ben"
      ["age"]=>
      array(3) {
        ["green"]=>
        string(5) "apple"
        ["information"]=>
        string(9) "underload"
        ["mister"]=>
        array(2) {
          [0]=>
          array(3) {
            [0]=>
            int(4234)
            [1]=>
            int(423423)
            [2]=>
            int(23423)
          }
          [1]=>
          array(3) {
            [0]=>
            int(34234)
            [1]=>
            int(23423)
            [2]=>
            int(23423)
          }
        }
      }
      ["occupation"]=>
      string(7) "Rancher"
      ["height"]=>
      string(5) "165cm"
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 94a5fe8bee03d59e48cf238805e079315ac9d616



## \Altumo\Arrays\Arrays::getValueIfSet

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
    static public function getValueIfSet( $array, $key, $default = null );
    
    
### Usage 1

    $array_one = array(
        'name' => 'James Smith'
    );

    $person_name = \Altumo\Arrays\Arrays::getValueIfSet( $array_one, 'name' );
    
    $person_email = \Altumo\Arrays\Arrays::getValueIfSet( $array_one, 'email' );


    \Altumo\Utils\Debug::dump( $person_name, $person_email );

### Output 1

    array(2){
        [0] => 'James Smith',
        [1] => null
    }



## \Altumo\Arrays\Arrays::callIfKeyExists


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
    static public function callIfKeyExists( $array, $key, $callback );
    
    
### Usage 1

    $array_one = array(
        'name' => 'James Smith'
    );

    $person = new Person();

    $callback = array( $person, 'setName' );
    
    $result = \Altumo\Arrays\Arrays::callIfKeyExists( $array_one, 'name', $callback );

    \Altumo\Utils\Debug::dump($result);

### Output 1

    null
<<<<<<< HEAD
=======
>>>>>>> d52f8f9307186d67fb73ab0347d774f0c116efc0
=======
>>>>>>> 94a5fe8bee03d59e48cf238805e079315ac9d616
