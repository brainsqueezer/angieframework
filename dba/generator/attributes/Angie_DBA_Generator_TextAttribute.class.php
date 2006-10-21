<?php

  /**
  * Text attribute
  * 
  * This class is used to set an attribute described by a long text field
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_TextAttribute extends Angie_DBA_Generator_Attribute {
    
    /**
    * Field size - tiny, small, medium, normal, big
    *
    * @var string
    */
    private $size;
  
    /**
    * Constructor
    *
    * @param void
    * @return Angie_DBA_Generator_TextAttribute
    */
    function __construct($name, $size = null) {
      parent::__construct($name);
      $this->setSize($size);
    } // __construct
    
    /**
    * Return fields that are used for storing this attribute
    *
    * @param void
    * @return Angie_DBA_Generator_TextField
    */
    function getFields() {
      return new Angie_DBA_Generator_TextField($this->getName(), $this->getSize());
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
  
  } // Angie_DBA_Generator_TextAttribute

?>