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
    * Default cache lifetime
    * 
    * Number of seconds that cache entrie lives. This value is used by default, if no per-entry expiration time is 
    * provided. Default cache lifetime is 1 hour (3600 seconds)
    *
    * @var integer
    */
    static private $default_cache_lifetime = 3600;
    
    /**
    * Add value to the cache
    * 
    * This function is used to add value to the cache. After it is added it can be accessed through get() method by
    * $name. $expiration_time is time when this cache item will expire. If value is NULL default lifetime will be used. 
    * It can be also a Angie_DateTime object descirbing exact time when cache item will expire or number of seconds that 
    * this item is given.
    * 
    * $tags is an array of item tags. It can be a single tag or an array of tags.
    * 
    * $attributes is associative array of value attributes and their values. Three special attributes are always added:
    * 
    * - expiration_timestamp - time when this item needs to expire
    * - tags - array of item tags
    * - updated_on - time of the last update
    *
    * @param string $name
    * @param mixed $value
    * @param array $attributes
    * @return null
    */
    static function set($name, $value, $expiration_time = null, $tags = null, $attributes = null) {
      $current_time = time();
      if(is_null($expiration_time)) {
        $set_expiration_time = $current_time + Angie_Cache::getDefaultCacheLifetime();
      } elseif(is_object($expiration_time) && ($expiration_time instanceof Angie_DateTime)) {
        $set_expiration_time = $expiration_time->getTimestamp();
      } else {
        $set_expiration_time = $current_time + (integer) $expiration_time;
      } // if
      
      if($set_expiration_time <= $current_time) {
        self::remove($name);
        return; // entry already expired
      } // if
      
      if(is_array($tags)) {
        $set_tags = $tags;
      } else {
        $set_tags = trim($tags) ? array($tags) : array();
      } // if
      
      if(!is_array($attributes)) {
        $attributes = array();
      } // if
      $attributes['updated_on'] = $current_time;
      $attributes['expiration_time'] = $set_expiration_time;
      $attributes['tags'] = $set_tags;
      
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
    * Remove entry from cache
    * 
    * If entry $name exists in cache it will be removed
    *
    * @param string $name
    * @return null
    */
    static function remove($name) {
      if(isset(self::$data[$name])) {
        unset(self::$data[$name]);
      } // if
    } // remove
    
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
    
    /**
    * Return entries by attribute value
    * 
    * This function will return set of entries that match input criteria - compared with $comparison operator with 
    * $value they need to return true to be listed in the result
    *
    * @param string $attribute
    * @param mixed $value
    * @param string $comparison
    * @return array
    */
    static function getByAttribute($attribute, $value, $comparison = COMPARE_EQ) {
      $result = array();
      foreach(self::$data as $name => $entry) {
        $attribute_value = $entry->getAttribute($attribute);
        if(is_null($attribute_value)) {
          continue;
        } // if
        
        if(is_true_statement($attribute_value, $comparison, $value)) {
          $result[$name] = $entry;
        } // if
      } // foreach
      return count($result) ? $result : null;
    } // getByAttribute
    
    /**
    * Drop entries by attribute value
    * 
    * This function will walk through cache, match attribute value with $value based on $comparison and if it returns 
    * true that entry will be dropped. Result of this function is number of dropped items.
    *
    * @param string $attribute
    * @param mixed $value
    * @param string $comparison
    * @return integer
    */
    static function dropByAttribute($attribute, $value, $comparison = COMPARE_EQ) {
      $counter = 0;
      foreach(self::$data as $name => $entry) {
        $attribute_value = $entry->getAttribute($attribute);
        if(is_null($attribute_value)) {
          continue;
        } // if
        
        if(is_true_statement($attribute_value, $comparison, $value)) {
          unset(self::$data[$name]);
          $counter++;
        } // if
      } // foreach
      return $counter;
    } // dropByAttribute
    
    /**
    * Return cache entries by tag(s)
    * 
    * This function can be used to extract set of entries that have specific tag or tags. Entry will need to match ALL 
    * input tags in order to be included in result.
    * 
    * Examples:
    * <pre>
    * return Angie_Cache::getByTag('fruit');
    * return Angie_Cache::getByTag('fruit', 'round', 'pretty');
    * </pre>
    * 
    * @param void
    * @return array
    */
    static function getByTag() {
      $tags = func_get_args();
      if(!is_array($tags)) {
        return null;
      } // if
      
      $result = array();
      foreach(self::$data as $name => $entry) {
        $entry_tags = $entry->getTags();
        if(!is_array($entry_tags)) {
          continue;
        } // if
        
        $match_all = true;
        foreach($tags as $tag) {
          if(!in_array($tag, $entry_tags)) {
            $match_all = false;
            break;
          } // if
        } // foreach
        
        if($match_all) {
          $result[$name] = $entry;
        } // if
      } // foreach
      
      return count($result) ? $result : null;
    } // getByTag
    
    /**
    * Drop content of cache that match tag(s)
    * 
    * This function will match entries to tags set provided as argument and if all tags match that entry will be 
    * removed. This function returns total number of removed items.
    * 
    * Example:
    * <pre>
    * return Angie_Cache::dropByTag('fruit');
    * return Angie_Cache::dropByTag('fruit', 'round', 'pretty');
    * </pre>
    *
    * @param void
    * @return integer
    */
    static function dropByTag() {
      $tags = func_get_args();
      if(!is_array($tags)) {
        return 0;
      } // if
      
      $counter = 0;
      foreach(self::$data as $name => $entry) {
        $entry_tags = $entry->getTags();
        if(!is_array($entry_tags)) {
          continue;
        } // if
        
        $match_all = true;
        foreach($tags as $tag) {
          if(!in_array($tag, $entry_tags)) {
            $match_all = false;
            break;
          } // if
        } // foreach
        
        if($match_all) {
          unset(self::$data[$name]);
          $counter++;
        } // if
      } // foreach
      
      return $counter;
    } // dropByTag
    
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
      self::dropByAttribute('expiration_time', time(), COMPARE_LT); // drop expired content
    } // setBackend
    
    /**
    * Get default_cache_lifetime
    *
    * @param null
    * @return integer
    */
    static function getDefaultCacheLifetime() {
      return self::$default_cache_lifetime;
    } // getDefaultCacheLifetime
    
    /**
    * Set default_cache_lifetime value
    *
    * @param integer $value
    * @return null
    */
    static function setDefaultCacheLifetime($value) {
      $set_value = (integer) $value;
      if($new_value <= 0) {
        throw new Angie_Core_Error_InvalidParamValue('value', $value, 'New lifetime value need to be greater than 0');
      } // if
      self::$default_cache_lifetime = $new_value;
    } // setDefaultCacheLifetime
  
  } // Angie_Cache

?>