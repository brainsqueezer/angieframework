<?php

  /**
  * String attribute
  * 
  * Attribute that is represented by a single varchar field
  *
  * @package Angie.DBA
  * @subpackage generator.attributes
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Attribute_String extends Angie_DBA_Generator_Attribute {
    
    /**
    * Native (PHP) type. This value is used in generated docs for accessors
    *
    * @var string
    */
    protected $native_type = 'string';
    
    /**
    * Field lenght
    *
    * @var integer
    */
    private $lenght;
  
    /**
    * Constructor
    *
    * @param Angie_DBA_Generator_Entity $entity
    * @param string $name
    * @param integer $lenght
    * @return Angie_DBA_Generator_Attribute_String
    */
    function __construct(Angie_DBA_Generator_Entity $owner_entity, $name, $default_value = null, $required = false, $lenght = 100) {
      parent::__construct($owner_entity, $name, $default_value, $required);
      $this->setLenght($lenght);
    } // __construct
    
    /**
    * Return fields that represent this attribute
    *
    * @param void
    * @return Angie_DB_Field_String
    */
    function getFields() {
      $string_field = new Angie_DB_Field_String($this->getName(), $this->getDefaultValue(), $this->getRequired());
      $string_field->setLenght($this->getLenght());
      
      return $string_field;
    } // getFields
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
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
  
  } // Angie_DBA_Generator_Attribute_String

?>