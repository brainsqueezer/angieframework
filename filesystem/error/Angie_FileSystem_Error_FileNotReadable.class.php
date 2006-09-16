<?php

  /**
  * File not readable error
  * 
  * This error is thrown when we want to read from a file but it is not readable
  *
  * @package Angie.filesystem
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_FileSystem_Error_FileNotReadable extends Angie_Error {
  
    /**
    * Path of the requested file
    *
    * @var string
    */
    private $file_path;
    
    /**
    * Construct the Angie_FileSystem_Error_FileNotReadable
    *
    * @param void
    * @return Angie_FileSystem_Error_FileNotReadable
    */
    function __construct($file_path, $message = null) {
      if(is_null($message)) {
        $message = "File '$file_path' is not readable";
      } // if
      parent::__construct($message);
      $this->setFilePath($file_path);
    } // __construct
    
    /**
    * Return errors specific params...
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'file path' => $this->getFilePath()
      ); // array
    } // getAdditionalParams
    
    // -------------------------------------------------------
    // Getters and setters
    // -------------------------------------------------------
    
    /**
    * Get file_path
    *
    * @param null
    * @return string
    */
    function getFilePath() {
      return $this->file_path;
    } // getFilePath
    
    /**
    * Set file_path value
    *
    * @param string $value
    * @return null
    */
    function setFilePath($value) {
      $this->file_path = $value;
    } // setFilePath
  
  } // Angie_FileSystem_Error_FileNotReadable

?>