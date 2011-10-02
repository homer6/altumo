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





namespace Altumo\Form; 


/**
* This class represents an uploaded file that hasn't been moved.
* It's useful for performing common operations on uploaded files.
* 
* This class depends on the "fileinfo" php extension to detect the
* mimetype.
* 
* Usage:
* 
*    $uploaded_files = \Altumo\Form\UploadedFile::loadFiles();
*    eg. $uploaded_files value:
*      array(
*           'myfile1' => object(Altumo\Form\UploadedFile)
*           'nested_form_name' = array(
*               'nested_file_1' => object(Altumo\Form\UploadedFile)
*               'nested_file_2' => object(Altumo\Form\UploadedFile)
*           )
*      )
* 
*    These values are consistent with a form like this:
*           <input type="file" name="myfile1" />
*           <input type="file" name="nested_form_name[nested_file_1]" />
*           <input type="file" name="nested_form_name[nested_file_2]" />
* 
* Note: 
*    
*     This class currently only supports one level of nesting.  This
*     will throw an \Exception if two levels are attempted.  
*       eg. nested_form_name[nested_1][my_file2]
* 
* @author Steve Sperandeo <steve.sperandeo@altumo.com>
* 
*/
class UploadedFile{

    protected $filename = null;
    protected $mime_type = null;
    protected $error = null;
    protected $filesize = null;
    protected $temp_location = null;


    /**
    * Constructor for this UploadedFile.
    * 
    * @return UploadedFile
    */
    public function __construct(){    
    
    }        
    
    
    /**
    * Setter for the filename field on this UploadedFile.
    * 
    * @param string $filename
    */
    public function setFilename( $filename ){
    
        $this->filename = $filename;
        
    }
    
    
    /**
    * Getter for the filename field on this UploadedFile.
    * 
    * @return string
    */
    public function getFilename(){
    
        return $this->filename;
        
    }
        
    
    /**
    * Setter for the mime_type field on this UploadedFile.
    * 
    * @param string $mime_type
    */
    public function setMimeType( $mime_type ){
    
        $this->mime_type = $mime_type;
        
    }
    
    
    /**
    * Getter for the mime_type field on this UploadedFile.
    * Tries to detect the filetype if the other process failed.
    * 
    * 
    * @throws \Exception if "fileinfo" php module is not loaded
    * @throws \Exception if could not detect the mimetype with "fileinfo"
    * @return string
    */
    public function getMimeType(){
    
        if( !empty($this->mime_type) ){
            return $this->mime_type;
        }
        
        if( !extension_loaded('fileinfo') ){
            throw new \Exception('Fileinfo module is required.');
        }
        
        $fileinfo = new \finfo(FILEINFO_MIME, '/usr/share/file/magic.mgc');
        $mimetype = $fileinfo->file($this->getTempLocation());
        
        if( empty($mimetype) ){
            throw new \Exception('Could not detect file mime type with backup mechanism.');
        }
                
        $this->setMimeType( $mimetype );
        
        return $this->mime_type;
                
    }
        
    
    /**
    * Setter for the error field on this UploadedFile.
    * 
    * @param integer $error
    */
    public function setError( $error ){
    
        $this->error = $error;
        
    }
    
    
    /**
    * Getter for the error field on this UploadedFile.
    * 
    * @return integer
    */
    public function getError(){
    
        return $this->error;
        
    }
        
    
    /**
    * Setter for the filesize field on this UploadedFile.
    * 
    * @param integer $filesize
    */
    public function setFilesize( $filesize ){
    
        $this->filesize = $filesize;
        
    }
    
    
    /**
    * Getter for the filesize field on this UploadedFile.
    * 
    * @return integer
    */
    public function getFilesize(){
    
        return $this->filesize;
        
    }
        
    
    /**
    * Setter for the temp_location field on this UploadedFile.
    * 
    * @param string $temp_location
    */
    public function setTempLocation( $temp_location ){
    
        $this->temp_location = $temp_location;
        
    }
    
    
    /**
    * Getter for the temp_location field on this UploadedFile.
    * 
    * @return string
    */
    public function getTempLocation(){
    
        return $this->temp_location;
        
    }
    

    /**
    * Gets this model's member variable values as an array of strings for insertion into a database.
    * The array keys are the table field names.  Table field names should observere the convention.
    * 
    * @return array
    */
    public function getAsArray(){
    
        return array(
            'filename' => $this->getFilename(),
            'mime_type' => $this->getMimeType(),
            'error' => $this->getError(),
            'filesize' => $this->getFilesize(),
            'temp_location' => $this->getTempLocation()
        );
    
    }    
    
    
    /**
    * Determines if this file has errors or was uploaded properly.
    * Return true if there was a problem with the uploaded file.
    * Returns false if everything is hunky dorey
    * 
    * Returns false if no file was uploaded.  Use self::isEmpty() to 
    * detect when no file was uploaded.
    * 
    * @return boolean
    */
    public function hasErrors(){
        
        if( $this->isEmpty() ){
            return false;
        }
        
        if( $this->getError() !== UPLOAD_ERR_OK ){
            return true;
        }
        
        if( !is_uploaded_file($this->getTempLocation()) ){
            return true;
        }
        
        return false;        
        
    }
    
    
    /**
    * Determines if this is an empty file. 
    * ie.  No file was uploaded.
    * 
    * @return boolean
    */
    public function isEmpty(){
        
        $error = $this->getError();
        return ( $error === UPLOAD_ERR_NO_FILE || $error === 5 );
        
    }
    
    
    /**
    * Gets a description of the error (as string)
    * 
    * @return string
    */    
    public function getErrorMessage(){
        
        
        switch( $this->getError() ){
            case UPLOAD_ERR_OK:
                $message = "There is no error, the file uploaded with success.";
                break;
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension.";
                break;
            case 5:
                $message = "Empty file.";
                break;
            default:
                $message = "Unknown upload error";
                break;
        }
        return $message;        
        
    }

    
    /**
    * Creates an array of UploadedFile objects from the $_FILES array.
    * 
    * @throws \Exception if one of the files was not an uploaded file
    * @return array  //of UploadedFile objects
    */
    static public function loadFiles(){
        
        $uploaded_files = array();
        
        if( empty($_FILES) ){
            return $uploaded_files;
        }
        
        $forms = array_keys( $_FILES );
        $first_form = reset($forms);
        
        
        foreach( $forms as $form ){
        
            if( is_array($_FILES[$form]['name']) ){
                //this is a nested array name, eg.  jobseekerform[resume_file]
                    
                    $files = array_keys($_FILES[$form]['name']);
                    
                    foreach( $files as $file ){
                        
                        if( !array_key_exists( 'tmp_name', $_FILES[$form] ) ){
                            throw new \Exception('GetFiles does not support third level form names.');
                        }                    
                        if( is_array($_FILES[$form]['tmp_name'][$file]) ){
                            throw new \Exception('GetFiles does not support third level form names.');
                        }
                        
                            
                        $uploaded_file = new \Altumo\Form\UploadedFile();
                        $uploaded_file->setFilename( $_FILES[$form]['name'][$file] );
                        $uploaded_file->setTempLocation( $_FILES[$form]['tmp_name'][$file] );
                        $uploaded_file->setError( $_FILES[$form]['error'][$file] );
                        $uploaded_file->setFilesize( $_FILES[$form]['size'][$file] );
                                                
                        $uploaded_files[$form][$file] = $uploaded_file;
                                                
                    }
                    
                
            }else{
                //this is not a nested array name, eg.  resume_file
                        
                    $uploaded_file = new \Altumo\Form\UploadedFile();
                    $uploaded_file->setFilename( $_FILES[$form]['name'] );
                    $uploaded_file->setTempLocation( $_FILES[$form]['tmp_name'] );
                    $uploaded_file->setError( $_FILES[$form]['error'] );
                    $uploaded_file->setFilesize( $_FILES[$form]['size'] );
                    
                    $uploaded_files[$form] = $uploaded_file;

            }
        
        }
        
        return $uploaded_files;
        
    }
    
    
}
