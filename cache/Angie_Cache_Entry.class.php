<?php

  /**
  * Single cache entry
  * 
  * Object of this class represent single cache entries. Properties of this class hold entry attributes and its value
  *
  * @package Angie.cache
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Cache_Entry {
  
    /**
    * Array of entry attributes
    *
    * @var array
    */
    private $attributes = null;
    
    /**
    * Actual value that is cached
    *
    * @var mixed
    */
    private $value = null;
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Return all entry attributes
    *
    * @param void
    * @return array
    */
    function getAttributes() {
      return $this->attributes;
    } // getAttributes
    
    /**
    * Return value of specific entry attribute
    * 
    * This function will look for a specific attribute. If it is not found $default is returned (NULL by default)
    *
    * @param string $name
    * @param mixed $default
    * @return mixed
    */
    function getAttribute($name, $default = null) {
      return array_var($this->attributes, $name, $default);
    } // getAttribute
    
    /**
    * Set single attribute
    * 
    * Attribute is a simple name => value pair. For instance: 'signature' => 'ProjectMessage|12'
    *
    * @param string $name
    * @return null
    */
    function setAttribute($name, $value) {
      $this->attributes[$name] = $value;
    } // setAttribute
    
    /**
    * Return entry tags
    *
    * @param void
    * @return array
    */
    function getTags() {
      return $this->getAttribute('tags');
    } // getTags
    
    /**
    * Set array of attributes
    *
    * @param array $attributes
    * @return null
    */
    function setAttributes($attributes) {
      $this->attributes = array(); // reset
      if(is_array($attributes)) {
        foreach($attributes as $k => $v) {
          $this->attributes[$k] = $v;
        } // foreach
      } // if
    } // setAttributes
    
    /**
    * Get value
    *
    * @param null
    * @return mixed
    */
    function getValue() {
      return $this->value;
    } // getValue
    
    /**
    * Set value value
    *
    * @param mixed $value
    * @return null
    */
    function setValue($value) {
      $this->value = $value;
    } // setValue
  
  } // Angie_Cache_Entry

?>