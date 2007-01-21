<?php

  /**
  * Doctrine record changed to play well with Angie
  *
  * @package Angie.toys
  * @subpackage doctrine
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Doctrine_Record extends Doctrine_Record {
    
    /**
    * Array of fields that cannot be set using mass set methods
    *
    * @var array
    */
    private $protected_fields = array();
    
    /**
    * Array of fields that can be set using mass set methods
    *
    * @var array
    */
    private $allowed_fields = array();
    
    /**
    * Set field values using data from the array
    * 
    * If protected fields are defined only fields not in that list will be 
    * available to be set... Other fields will be skipped.
    * 
    * If allowed fields are defined only fields on that list will be set using 
    * this method. If protecteed fields property is set allowed fields are 
    * ignored.
    * 
    * Any field is considered settable if protected and allowed field lists are 
    * empty (there is no protected or allowed fields)
    *
    * @param array $input
    * @return null
    */
    function setFromArray($input) {
      if(is_foreachable($input)) {
        foreach($input as $field_name => $value) {
          if(count($this->protected_fields)) {
            if(in_array($this->protected_fields, $field_name)) {
              continue; // protected field... skip...
            } // if
          } elseif(count($this->allowed_fields)) {
            if(!in_array($field_name, $this->allowed_fields)) {
              continue; // not allowed field... skip...
            } // if
          } // if
          $this->set($field_name, $value);
        } // if
      } // if
    } // setFromArray
    
    /**
    * Returns true if we have new, unsaved record
    * 
    * This function will return true if this object is new and haven't been 
    * saved into database
    *
    * @param void
    * @return boolean
    */
    function isNew() {
      $state = $this->getState();
      return $state == self::STATE_TCLEAN || $state == self::STATE_TDIRTY;
    } // isNew
    
    /**
    * Returns true if this object is loaded from database
    *
    * @param void
    * @return boolean
    */
    function isLoaded() {
      return !$this->isNew();
    } // isLoaded
    
    /**
    * Set array of protected fields
    *
    * @param void
    * @return null
    */
    function setProtectedFields() {
      $args = func_get_args();
      if(is_foreachable($args)) {
        foreach($args as $arg) {
          if(!in_array($arg, $this->protected_fields)) {
            $this->protected_fields[] = $arg;
          } // if
        } // foreach
      } // if
    } // setProtectedFields
    
    /**
    * Set allowed fields
    *
    * @param void
    * @return null
    */
    function setAllowedFields() {
      $args = func_get_args();
      if(is_foreachable($args)) {
        foreach($args as $arg) {
          if(!in_array($this->allowed_fields)) {
            $this->allowed_fields[] = $arg;
          } // if
        } // foreach
      } // if
    } // setAllowedFields
  
  } // Angie_Doctrine_Record

?>