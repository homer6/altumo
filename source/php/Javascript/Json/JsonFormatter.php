<?php

/**
* This file is part of the Altumo library.
* 
* (c) Steve Sperandeo <steve.sperandeo@altumo.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/




namespace Altumo\Javascript\Json;
 
 
/**
* This class formats JSON strings into pretty JSON strings.
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
*/
class JsonFormatter{
     
     
     /**
     * Echos a string to the screen in a nice display of hex values and exits.
     * 
     * 
     * @param string $string
     */
     static public function hexDump( $string ){
         
         echo '<pre>';
         for( $position = 0; $position < strlen($string); $position++ ){
                                       
             $character = $string[$position];             
             if( $position != 0 ){
                 echo " ";
                 if( $position % 4 == 0 ){
                     echo " ";
                 }
                 if( $position % 16 == 0 ){
                     echo "\n";
                 }
             }
             echo dechex(ord($character));
         }
         exit();
         
     }
     
     
     /**
     * Formats a JSON string into a pretty JSON string.
     * 
     * @todo Fix so text-qualified characters eg. ",{[" don't break formatting.
     * 
     * @param string $json
     * @return string
     */
     static public function format( $json ){
         
         $indent_character = '    ';
         $indent_count = 0;         
         $json_length = strlen($json);
         
         $position = 0;
                  
         $insert_newline_after = function( $offset ) use ( &$json, &$indent_character, &$indent_count, &$position ){
            $indent = str_repeat($indent_character, $indent_count);
            $json = \Altumo\String\String::insert( "\n" . $indent, $json, $offset + 1 );
            $position += strlen($indent) + 1;
         };
         
         for( ; $position < $json_length; $position++ ){
                                       
             $character = $json[$position];             
             switch( $character ){
                 
                case ':':
                        //insert a space after colons
                        $json = \Altumo\String\String::insert( " ", $json, $position + 1 );
                        $position++;
                    break;
                 
                case ',':
                        $insert_newline_after($position);
                    break;
                    
                case '[':
                case '{':
                        $indent_count++;
                        $insert_newline_after($position);
                    break;
                    
                case ']':
                case '}':
                        $indent_count--;
                        $insert_newline_after($position - 1);

                    break;
                    
                default:
                                                     
            };
            $json_length = strlen($json);

         }
         
         return $json;
         
     }
     
     
     /**
     * Converts an XML string to a JSON string.
     * 
     * Adapted from http://pear.php.net/package/Services_JSON
     * 
     * 
     * @param string $xml 
     * @throws \Exception if $xml is not valid XML
     * @return string
     */
     static public function convertXmlToJson( $xml ){
                  
         $xml_element = new \Altumo\Xml\XmlElement($xml);
         return $xml_element->getAsJsonString();
                  
     }
     
     
 }
 