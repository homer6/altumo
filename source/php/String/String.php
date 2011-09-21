<?php

/*
* This file is part of the Altumo library.
*
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
* (c) Juan Jaramillo <juan.jaramillo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/




namespace Altumo\String;


/**
* This class contains a number of string helper functions.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class String{

    
    /**
    * Inserts one string ($addition) into another ($destination) at a given 
    * string $offset.
    * 
    * @param string $addition //new string to add into $destination
    * @param string $destination //existing string that will contain $addition
    * @param integer $offset //offset in $destination that we will place $addition
    * 
    * @see http://forums.digitalpoint.com/showthread.php?t=182666#post1785645
    * 
    * @return string
    */
    static public function insert( $addition, $destination, $offset ){    
        $left = substr( $destination, 0, $offset );
        $right = substr( $destination, $offset );
        return $left . $addition . $right;
    }
    
    
    /**
    * Makes an underscored string camel case
    * eg.
    *   how_are_you
    * becomes
    *   HowAreYou
    * 
    * 
    * @param string $string
    * @return string
    */
    static public function formatCamelCase( $string ) {
        
        $output = "";
        $string_parts = explode( '_', $string );
        
        foreach( $string_parts as $string_part ){
            $string_part = strtolower($string_part);
            $output .= strtoupper(substr( $string_part, 0, 1 )) . substr( $string_part, 1 ) ;
        }
        
        return $output;
        
    }
    
    
    /**
    * Makes a $string title cased
    * eg.
    *   how are you
    * becomes
    *   How Are You
    * 
    * @see http://blogs.sitepoint.com/title-case-in-php/
    * 
    * @param string $string
    * @return string
    */
    static public function formatTitleCase( $string ) {
        
        // Converts $string to Title Case, and returns the result.
        // Our array of 'small words' which shouldn't be capitalised if 
        // they aren't the first word. Add your own words to taste.
            $small_words_array = array( 'of','a','the','and','an','or','nor','but','is','if','then','else','when', 'at','from','by','on','off','for','in','out','over','to','into','with' ); 
            
            // Split the string into separate words 
            $words = explode(' ', $string); 
            foreach( $words as $key => $word ){ 
                // If this word is the first or it's not one of our small words, capitalise it
                if( $key == 0 || !in_array($word, $small_words_array) ){
                    $words[$key] = ucwords($word);
                }        
            } // Join the words back into a string 
            $string = implode(' ', $words);
        
        return $string;
        
    }
    
    
    /**
    * Makes a camel case string underscored 
    * eg.
    *   HowAreYou
    * becomes
    *   how_are_you
    * 
    * 
    * @param string $string
    * @return string
    */
    static public function formatUnderscored( $string ) {
        
        $output = "";
        //put underscores before the capitals and lower case all characters
        $output = strtolower( preg_replace('/([A-Z])/', '_\\1', $string) );
        
        //remove the first "_", if there is one
        $output = preg_replace('/^_(.*?)$/m', '\\1', $output);
        
        return $output;
        
    }
    
    
    /**
    * Replaces spaces with hyphens and converts to lowercase
    * eg.
    *   How Are You
    * becomes
    *   how-are-you
    * 
    * @param string $string
    * @return string
    */
    public static function getLowercaseHypenatedString( $string, $hyphen_char = "-" ){
        
        return strtolower( str_replace(" ", $hyphen_char, $string) );
        
    }

    
    /**
    * Replaces any multiple consecutive spaces with a single space.
    * eg.
    *   How    Are    You
    * becomes
    *   How Are You
    * 
    * @param string $string
    * @return string
    */
    public static function getSingleSpacedString( $string ){
        
        return preg_replace('/\\s+/', ' ', $string);
        
    }
        
    
    /**
    * Generates a string $number_of_chars long with the $character_pool as potential characters.
    * 
    * @param integer $number_of_chars
    * @param string $number_of_chars
    */
    static public function generateRandomString( $number_of_chars, $character_pool = '0123456789abcdefghijklmnopqrstuvwxyz' ){
        
        if( !is_integer($number_of_chars) || $number_of_chars < 1 ){
            throw new \Exception('Number of chars must be a positive integer.');
        }
        $output = '';
        $pool_count = strlen($character_pool);
        for( $x = 0; $x < $number_of_chars; $x++ ){
            $index = rand(0,$pool_count-1);
            $output .= $character_pool[$index];
        }        
        return $output;
        
    }
    
    
    /**
    * Generates a url parameter string from the supplied array.
    * Adds the ? to the beginning.
    * Returns an empty string if $parameters is empty.
    * This method will url_encode the values, but not the keys.
    * 
    * @param array $parameters
    * @return string
    */    
    static public function generateUrlParameterString( $parameters = array() ){
        
        if( empty($parameters) ) return '';
        
        //combine and encode the parameters
            $combined_parameters = array();            
            foreach( $parameters as $key => $parameter ){
                $combined_parameters[] = $key . '=' . urlencode($parameter);
            }
        
        //build the request url
            $parameter_string = '';
            if( !empty($parameters) ){
                $parameter_string .= '?' . implode( '&', $combined_parameters );
            }
        
        return $parameter_string;
        
    }
    
    
    /**
    * Generates a url slug string from the supplied string.
    * eg.
    *   _How Are You_
    * becomes
    *   how-are-you
    * 
    * @param string $string
    * @return string
    */    
    static public function generateUrlSlug( $string ){
        
        $string = self::cleanString( $string, '-_a-zA-Z0-9' );
        
        $string = preg_replace( '/^[-_ ]*(.*?)[-_ ]*$/', '\\1', $string );

        return self::getLowercaseHypenatedString(
                    self::getSingleSpacedString(
                        self::getAlphaNumSpaceString( $string ) 
                    )
               );
        
    }
    
    
    /**
    * Formats a number into a human readable string.
    * 
    * eg. 1000
    *   1KB
    * 
    * @param integer $bytes
    * @param integer $precision
    * @return string
    */
    static public function formatBytesToHuman( $bytes, $precision = 2 ){
        
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
      
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
      
        $bytes /= pow(1024, $pow);
      
        return round($bytes, $precision) . ' ' . $units[$pow]; 
        
    }
    
    
    /**
    * Removes all non-alphabetic characters from the given string.
    * 
    * @throws \Exception                    //if string is not a string
    * @param string $string
    */
    static public function stripNonAlphaCharacters( $string ){
        
        \Altumo\Validation\Strings::assertString($string);
        return preg_replace('/[^a-zA-Z]/', '', $string);
        
    }
    
    
    /**
    * Removes anything that is not a letter, number or space from the given
    * string.
    * 
    * 
    * @param string $string
    * @return string
    */
    public static function getAlphaNumSpaceString( $string  ){
        
        return self::cleanString( $string, 'a-zA-Z0-9\\s' );
        
    }
    
    
    /**
    * @desc Removes (from a string) anything that doesn't match the (regex-compatible) character classes given
    * @param string $string string
    * @param string $regex_match_classes preg_replace compatible character class e.g. a-zA-Z0-9 will remove anything that is NOT a letter or a number.
    */
    public static function cleanString( $string, $regex_match_classes = 'a-zA-Z0-9' ){
        
        return preg_replace("/([^{$regex_match_classes}])/", '', $string);
        
    }
        
    
    /**
    * Truncates a string and appends $ellipsis to the truncated result.
    * 
    * It does not truncate individual words, and it ensures the total length
    * of the resulting text (including $ellipsis) does not exceed $max_length
    * 
    * 
    * @param string $text
    *   // A string to truncate
    * 
    * @param int $max_length
    *   // The resulting text cannot exceed this length
    * 
    * @param string $ellipsis
    *   // The character that will be appended to the resulting text.
    *  
    * @param bool $truncate_words
    *   // If true, words can be truncated to accommodate $max_length
    *   // If false, the text will only be truncated in spaces and not mid-word
    * 
    *
    * @returns string
    *   // Tuncated text with $ellipsis appended
    */
    static public function getTruncatedText( $text, $max_length, $ellipsis = '...', $truncate_words = false ){

        $parts = explode( ' ', $text );
        
        $output = '';
        
        // If $text has no spaces or if words can be truncated, simply truncate
        // the string to max_length
            if( (count($parts) == 1) || $truncate_words ){
                
                $output = substr( $text, 0, $max_length - strlen($ellipsis) );
            
        // Avoid truncating words   
            } else {
                
                foreach( $parts as $part ){
                    if( strlen( $output . ' ' . $part . $ellipsis ) > $max_length ){
                        break;
                    } else {
                        $output .= ' ' . $part;
                    }
                }
                
            }
        
        
        if( strlen( $text ) > $max_length ){
            $output .= $ellipsis;
        }
        
        return $output;
        
    }
    
    
}