<?php

  /**
  * Cache file system backend
  * 
  * This backend uses simple file to save serialized cache data - it will save attributes and value at the same file. 
  * Whole cache content is loaded on load() method
  *
  * @package Angie.cache
  * @subpackage backend
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Cache_Backend_FS implements Angie_Cache_Backend {
    
    /**
    * Cache directory
    *
    * @var string
    */
    private $cache_dir;
  
    /**
    * Constructor
    * 
    * Init file system backend and set direcotory where cache files are stored. Directory need to be writable by the 
    * PHP (you'll get and exception if it is not).
    *
    * @param string $cache_dir
    * @return Angie_Cache_Backend_FS
    * @throws Angie_FileSystem_Error_DirDnx If $cache_dir does not exist
    * @throws Angie_FileSystem_Error_DirNotWritable If $cache_dir is not writable
    */
    function __construct($cache_dir) {
      $this->setCacheDir($cache_dir);
    } // __construct
    
    // ---------------------------------------------------
    //  Cache backend interface implementation
    // ---------------------------------------------------
    
    /**
    * Load data
    * 
    * This function is used to load all informations about the cache - name of cached values and their attributes. To 
    * retreive actual values you should use fetchValue() method.
    * 
    * Return value is array of cache entries without values
    *
    * @param void
    * @return array
    */
    function load() {
      $data_file = $this->getDataFile();
      if(is_file($data_file)) {
        $content = file_get_contents($data_file);
        if($content) {
          $result = unserialize($content);
          return is_array($result) ? $result : array();
        } // if
      } // if
      return array();
    } // load
    
    /**
    * Save cache data
    * 
    * This function will save array of cache entries into a cache storage
    *
    * @param array $data
    * @return null
    */
    function save($data) {
      $data_file = $this->getDataFile();
      if(!file_put_contents($data_file, serialize($data))) {
        throw new Angie_FileSystem_Error_FileNotWritable($data_file);
      } // if
    } // save
    
    /**
    * Clean up cache content
    * 
    * This function is used to clean up the cache. It will remove everything - names, attributes and values
    *
    * @param void
    * @return null
    */
    function cleanUp() {
      $data_file = $this->getDataFile();
      if(is_file($data_file)) {
        @unlink($data_file);
      } // if
    } // cleanUp
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * This function will return data file path
    *
    * @param void
    * @return string
    */
    protected function getDataFile() {
      return $this->getCacheDir() . '/.' . 'data';
    } // getDataFile
    
    /**
    * Return path of value file
    *
    * @param string $name
    * @return string
    */
    protected function getValueFile($name) {
      return $this->getCacheDir() . '/.' . md5($name);
    } // getValueFile
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get cache_dir
    *
    * @param null
    * @return string
    */
    function getCacheDir() {
      return $this->cache_dir;
    } // getCacheDir
    
    /**
    * Set cache_dir value
    *
    * @param string $value
    * @return null
    * @throws Angie_FileSystem_Error_DirDnx If $cache_dir does not exist
    * @throws Angie_FileSystem_Error_DirNotWritable If $cache_dir is not writable
    */
    protected function setCacheDir($value) {
      if(!is_dir($value)) {
        throw new Angie_FileSystem_Error_DirDnx($value);
      } // if
      if(!folder_is_writable($value)) {
        throw new Angie_FileSystem_Error_DirNotWritable($value);
      } // if
      $this->cache_dir = $value;
    } // setCacheDir
  
  } // Angie_Cache_Backend_FS

?>