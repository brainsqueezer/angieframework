<?php

  /**
  * Integer field defintion
  * 
  * Integer field defintion introduces new field properties - unsigned and 
  * auto_increment flags specific for this type. Integer field is most common 
  * base for primary and freign keys
  *
  * @package Angie.DB
  * @subpackage fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DB_Field_Integer extends Angie_DB_Field {
    
    /**
    * Primitive field type
    *
    * @var string
    */
    protected  $type = Angie_DB::TYPE_INTEGER;
  
    /**
    * Unsigned flag
    *
    * @var boolean
    */
    private $unsigned = false;
    
    /**
    * Auto increment flag
    *
    * @var boolean
    */
    private $auto_increment = false;
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get unsigned
    *
    * @param null
    * @return boolean
    */
    function getUnsigned() {
      return $this->unsigned;
    } // getUnsigned
    
    /**
    * Set unsigned value
    *
    * @param boolean $value
    * @return null
    */
    function setUnsigned($value) {
      $this->unsigned = $value;
    } // setUnsigned
    
    /**
    * Get auto_increment
    *
    * @param null
    * @return boolean
    */
    function getAutoIncrement() {
      return $this->auto_increment;
    } // getAutoIncrement
    
    /**
    * Set auto_increment value
    *
    * @param boolean $value
    * @return null
    */
    function setAutoIncrement($value) {
      $this->auto_increment = $value;
    } // setAutoIncrement
  
  } // Angie_DB_Field_Integer

?>