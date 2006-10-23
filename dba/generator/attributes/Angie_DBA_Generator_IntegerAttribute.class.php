<?php

  /**
  * Integer attribute
  * 
  * Integer attribute is entity attribute that is represented by single integer field. Constructor artguments are 
  * selected to best fit integer type
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_IntegerAttribute extends Angie_DBA_Generator_Attribute {
    
    /**
    * Size of the field
    *
    * @var string
    */
    private $size = null;
    
    /**
    * Field lenght
    *
    * @var integer
    */
    private $lenght = null;
    
    /**
    * Is this field unisnged
    *
    * @var boolean
    */
    private $is_unsigned = false;
    
    /**
    * Is this ID auto increment
    *
    * @var integer
    */
    private $is_auto_increment = false;
  
    /**
    * Constructor
    * 
    * Construct the integer attribute. Default value for attributes:
    * 
    * $size - normal
    * $lenght - auto for given size
    * $is_unsigned - false
    * $is_auto_increment - false
    *
    * @param Angie_DBA_Generator_Entity $entity
    * @param string $name
    * @param string $size
    * @param integer $lenght
    * @param boolean $is_unsigned
    * @param boolean $is_auto_increment
    * @return Angie_DBA_Generator_IntegerAttribute
    */
    function __construct(Angie_DBA_Generator_Entity $entity, $name, $size = null, $lenght = null, $is_unsigned = false, $is_auto_increment = false) {
      parent::__construct($entity, $name);
      
      $this->setSize($size);
      $this->setLenght($lenght);
      $this->setIsUnsigned($this->getIsUnsigned());
      $this->setIsAutoIncrement($is_auto_increment);
    } // __construct
    
    /**
    * Return field that describes this attribute
    *
    * @param void
    * @return Angie_DBA_Generator_IntegerField
    */
    function getFields() {
      return new Angie_DBA_Generator_IntegerField(
        $this->getName(), 
        $this->getIsUnsigned(), 
        $this->getIsAutoIncrement(), 
        $this->getSize(), 
        array('lenght' => $this->getLenght())
      ); // Angie_DBA_Generator_IntegerField
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
    * Get lenght
    *
    * @param null
    * @return integer
    */
    function getLenght() {
      return $this->lenght;
    } // getLenght
    
    /**
    * Set lenght value
    *
    * @param integer $value
    * @return null
    */
    function setLenght($value) {
      $this->lenght = $value;
    } // setLenght
    
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
  
  } // Angie_DBA_Generator_IntegerAttribute

?>