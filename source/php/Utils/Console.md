Description
-----------

Console facilitates easy writing to a log file on the web server for debugging 
purposes.

The file that it writes to defaults to a filename called "console.log" in the 
system temp directory (eg. /tmp/console.log).


Sample Usage
------------

Dump some variables to a console.


    $my_var1 = array(
        'hi',
        'I\'m',
        'an',
        'array'
    );

    $my_var2 = range( 1, 5 );

    \Altumo\Utils\Console::dump( $my_var1, $my_var2 );
    
    $my_string = "I'm a string.";
    
    \Altumo\Utils\Console::dump( $my_string, $my_string, $my_string, $my_string );
    

    //in the /tmp/console.log file:
    
    array(4) {
      [0]=>
      string(2) "hi"
      [1]=>
      string(3) "I'm"
      [2]=>
      string(2) "an"
      [3]=>
      string(5) "array"
    }
    array(5) {
      [0]=>
      int(1)
      [1]=>
      int(2)
      [2]=>
      int(3)
      [3]=>
      int(4)
      [4]=>
      int(5)
    }


    string(13) "I'm a string."
    string(13) "I'm a string."
    string(13) "I'm a string."
    string(13) "I'm a string."
