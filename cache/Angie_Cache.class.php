<?php

  final class Angie_Cache {
  
    /**
    * Internal data array
    *
    * @var array
    */
    static private $data = array();
    
    /**
    * Cache backend
    * 
    * This object (instance of Angie_Cache_Backend) is used for saving and loading cache data into various sources (file 
    * system, database, memory etc)
    *
    * @var Angie_Cache_Backend
    */
    static private $backend;
    
    /**
    * Add value to the cache
    * 
    * This function is used to add value to the cache. After it is added it can be accessed through get() method by
    * $name. $attributes is associative array of value attributes and their values. 
    * 
    * Special attribute is always added (if missing) - created_on. That attribute contains timestamp when this value is 
    * added to the cache
    *
    * @param string $name
    * @param mixed $value
    * @param array $attributes
    * @return null
    */
    static function set($name, $value, $attributes = null) {
      $entry = isset(self::$data[$name]) ? self::$data[$name] : null;
      if(!($entry instanceof Angie_Cache_Entry)) {
        $entry = new Angie_Cache_Entry();
      } // if
      
      $entry->setValue($value);
      $entry->setAttributes($attributes);
      
      self::$data[$name] = $entry;
    } // set
    
    /**
    * Get value from cache
    * 
    * This function will return named value from the cache if it exists. If value is not found $default value is 
    * returned (default is NULL).
    *
    * @param string $name
    * @param mixed $default
    * @return mixed
    */
    static function get($name, $default = null) {
      $entry = isset(self::$data[$name]) ? self::$data[$name] : null;
      return $entry instanceof Angie_Cache_Entry ? $entry->getValue() : $default;
    } // get
    
    /**
    * Save cache data into a storage using $backend instance
    *
    * @param void
    * @return null
    */
    static function save() {
      if(self::$backend instanceof Angie_Cache_Backend) {
        self::$backend->save(self::$data);
      } // if
    } // save
    
    /**
    * Clean up the chache
    *
    * @param void
    * @return null
    */
    static function cleanUp() {
      if(self::$backend instanceof Angie_Cache_Backend) {
        self::$backend->cleanUp();
      } // if
    } // cleanUp
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get backend
    *
    * @param null
    * @return Angie_Cache_Backend
    */
    static function getBackend() {
      return self::$backend;
    } // getBackend
    
    /**
    * Set backend value
    *
    * @param Angie_Cache_Backend $value
    * @return null
    */
    static function setBackend(Angie_Cache_Backend $value) {
      self::$backend = $value;
      self::$data = $value->load();
    } // setBackend
  
  } // Angie_Cache

?>