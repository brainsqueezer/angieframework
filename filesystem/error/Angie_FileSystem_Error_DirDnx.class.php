<?php

  /**
  * Directory does not exists error
  * 
  * This exception is thrown when we want to report that directory we are looking for is missing
  *
  * @package Angie.filesystem
  * @subpackage errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_FileSystem_Error_DirDnx extends Angie_Error {
    
    /**
    * Path of a dir that caused us all this trouble
    *
    * @var string
    */
    private $dir_path;
    
    /**
    * Construct the Angie_FileSystem_Error_DirDnx
    *
    * @param string $dir_path
    * @param string $message
    * @return Angie_FileSystem_Error_DirDnx
    */
    function __construct($dir_path, $message = null) {
      if(is_null($message)) {
        $message = "Directory '$dir_path' doesn't exists";
      } // if
      parent::__construct($message);
      $this->setDirPath($dir_path);
    } // __construct
    
    /**
    * Return errors specific params...
    *
    * @param void
    * @return array
    */
    function getAdditionalParams() {
      return array(
        'dir path' => $this->getDirPath()
      ); // array
    } // getAdditionalParams
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get dir_path
    *
    * @param null
    * @return string
    */
    function getDirPath() {
      return $this->dir_path;
    } // getDirPath
    
    /**
    * Set dir_path value
    *
    * @param string $value
    * @return null
    */
    function setDirPath($value) {
      $this->dir_path = $value;
    } // setDirPath
  
  } // Angie_FileSystem_Error_DirDnx

?>