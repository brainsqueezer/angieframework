<?php

  /**
  * This exception is thrown when we want to report that file we are looking for is missing
  *
  * @package Angie.errors
  * @subpackage filesystem
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Error_FileSystem_FileDnx extends Angie_Error {
  
    /**
    * Path of the requested file
    *
    * @var string
    */
    private $file_path;
    
    /**
    * Construct the FileDnxError
    *
    * @param void
    * @return FileDnxError
    */
    function __construct($file_path, $message = null) {
      if(is_null($message)) {
        $message = "File '$file_path' doesn't exists";
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
  
  } // Angie_Error_FileSystem_FileDnx

?>