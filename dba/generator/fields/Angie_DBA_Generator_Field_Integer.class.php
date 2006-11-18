<?php

  /**
  * Generator integer field
  *
  * @package Angie.DBA
  * @subpackage generator.fields
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Field_Integer extends Angie_DBA_Generator_Field {
    
    /**
    * Unsignt is used with numeric types to mark fields that can have 0 and positive values
    *
    * @var boolean
    */
    private $is_unsigned = false;
    
    /**
    * If value of auto increment field is not set on insert it is set to the next value in queue to ensure uniqueness. 
    * Usually used for ID fields
    *
    * @var boolan
    */
    private $is_auto_increment = false;
  
    /**
    * Constructor
    *
    * Constructs the integer field. Same rules apply like for ordinary field except that type is always set to INTEGER. 
    * Properties that are most important for integer fields are set to be arguments of fields constructor
    * 
    * @param string $name
    * @param boolean $is_unsigned
    * @param boolean $is_auto_increment
    * @param string $size
    * @param mixed $properties
    * @return Angie_DBA_Generator_Field_Integer
    */
    function __construct($name, $is_unsigned = false, $is_auto_increment = false, $size = null, $properties = null) {
      parent::__construct($name, $properties);
      
      $this->setType(Angie_DBA_Generator::TYPE_INTEGER);
      $this->setCastFunction('intval');
      
      $this->setIsUnsigned($is_unsigned);
      $this->setIsAutoIncrement($is_auto_increment);
      if(!is_null($size)) {
        $this->setTypeSize($size);
      } // if
    } // __construct
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get is_unsigned
    *
    * @param null
    * @return boolean
    */
    function getIsUnsigned() {
      return $this->is_unsigned;
    } // getIsUnsigned
    
    /**
    * Set is_unsigned value
    *
    * @param boolean $value
    * @return null
    */
    function setIsUnsigned($value) {
      $this->is_unsigned = $value;
    } // setIsUnsigned
    
    /**
    * Get is_auto_increment
    *
    * @param null
    * @return boolean
    */
    function getIsAutoIncrement() {
      return $this->is_auto_increment;
    } // getIsAutoIncrement
    
    /**
    * Set is_auto_increment value
    *
    * @param boolean $value
    * @return null
    */
    function setIsAutoIncrement($value) {
      $this->is_auto_increment = $value;
    } // setIsAutoIncrement
  
  } // Angie_DBA_Generator_Field_Integer

?>