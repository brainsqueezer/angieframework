<?php

  /**
  * ID attribute
  * 
  * This class is used to describe ID attribute that is consistent of one field. It is represented by an unsigned 
  * integer that is auto_increment is most cases (optional)
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_IdAttribute extends Angie_DBA_Generator_Attribute {
    
    /**
    * Size of the field
    *
    * @var string
    */
    private $size = null;
    
    /**
    * Is this ID auto increment
    *
    * @var integer
    */
    private $is_auto_increment = null;
  
    /**
    * Constructor
    *
    * @param Angie_DBA_Generator_Entity $owner_entity
    * @param string $name
    * @param integer $size
    * @param boolean $is_auto_increment
    * @return Angie_DBA_Generator_IdAttribute
    */
    function __construct(Angie_DBA_Generator_Entity $owner_entity, $name, $size = null, $is_auto_increment = false) {
      $this->setSize($size);
      $this->setIsAutoIncrement($is_auto_increment);
      
      parent::__construct($owner_entity, $name);
    } // __construct
    
    /**
    * Return field that describes this attribute
    *
    * @param void
    * @return Angie_DBA_Generator_IntegerField
    */
    function getFields() {
      return new Angie_DBA_Generator_IntegerField($this->getName(), true, $this->getIsAutoIncrement(), $this->getSize());
    } // getFields
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get size
    *
    * @param null
    * @return string
    */
    function getSize() {
      return $this->size;
    } // getSize
    
    /**
    * Set size value
    *
    * @param string $value
    * @return null
    */
    function setSize($value) {
      $this->size = $value;
    } // setSize
    
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
  
  } // Angie_DBA_Generator_IdAttribute

?>