<?php

  /**
  * Cache backend interface
  * 
  * This interface need to be implemented by all cache backends. It provides a method that are used for storing and
  * retreiving data from cache storage.
  *
  * @package Angie.cache
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  interface Angie_Cache_Backend {
    
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
    function load();
    
    /**
    * Save cache data
    * 
    * This function will save array of cache entries into a cache storage
    *
    * @param array $data
    * @return null
    */
    function save($data);
    
    /**
    * Clean up cache content
    * 
    * This function is used to clean up the cache. It will remove everything - names, attributes and values
    *
    * @param void
    * @return null
    */
    function cleanUp();
    
  } // Angie_Cache_Backend
  
?>