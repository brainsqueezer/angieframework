<?php

  /**
  * Generator entity
  *
  * This class is used to describe single model entity - its attributes, relationships and some additional settings 
  * (field protection, auto-setters etc)
  * 
  * @package Angie.DBA
  * @subpackage generator
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Entity {
    
    /**
    * Entity name
    *
    * @var string
    */
    private $name;
    
    /**
    * Array of entity attributes
    *
    * @var array
    */
    private $attributes = array();
    
    /**
    * Array of entity relations
    *
    * @var array
    */
    private $relations = array();
    
    /**
    * Array of entity auto setters
    *
    * @var array
    */
    private $auto_setters = array();
  
    /**
    * Constructor
    *
    * @param void
    * @return Angie_DBA_Generator_Entity
    */
    function __construct($name) {
      $this->setName($name);
    } // __construct
    
    // ---------------------------------------------------
    //  Helper methods / Attributes
    // ---------------------------------------------------
    
    /**
    * Add ID attribute to this entity
    *
    * @param string $name
    * @param string $size
    * @param boolean $is_auto_increment
    * @return Angie_DBA_Generator_IdAttribute
    */
    function addIdAttribute($name, $size = null, $is_auto_increment = true) {
      return $this->addAttribute(new Angie_DBA_Generator_IdAttribute($name, $size, $is_auto_increment));
    } // addIdAttribute
    
    /**
    * Add integer attribute to this entity
    *
    * @param string $name
    * @param string $size
    * @param integer $lenght
    * @param boolean $is_unsigned
    * @return Angie_DBA_Generator_IntegerAttribute
    */
    function addIntAttribute($name, $size = null, $lenght = null, $is_unsigned = false) {
      return $this->addAttribute(new Angie_DBA_Generator_IntegerAttribute($name, $size, $lenght, $is_unsigned, false));
    } // addIntAttribute
    
    /**
    * Add string attribute (varchar) to this entity
    *
    * @param string $name
    * @param integer $lenght
    * @return Angie_DBA_Generator_StringAttribute
    */
    function addStringAttribute($name, $lenght) {
      return $this->addAttribute(new Angie_DBA_Generator_StringAttribute($name, $lenght));
    } // addStringAttribute
    
    /**
    * Add text (multiline, long string) to this entity
    *
    * @param string $name
    * @param string $size
    * @return Angie_DBA_Generator_TextAttribute
    */
    function addTextAttribute($name, $size) {
      return $this->addAttribute(new Angie_DBA_Generator_TextAttribute($name, $size));
    } // addTextAttribute
    
    /**
    * Add date time attribute to this entity
    *
    * @param string $name
    * @return Angie_DBA_Generator_DateTimeAttribute
    */
    function addDateTimeAttribute($name) {
      return $this->addAttribute(new Angie_DBA_Generator_DateTimeAttribute($name));
    } // addDateTimeAttribute
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get name
    *
    * @param null
    * @return string
    */
    function getName() {
      return $this->name;
    } // getName
    
    /**
    * Set name value
    *
    * @param string $value
    * @return null
    */
    function setName($value) {
      $this->name = $value;
    } // setName
    
    /**
    * Return all entity attributes
    *
    * @param void
    * @return array
    */
    function getAttributes() {
      return $this->attributes;
    } // getAttributes
    
    /**
    * Return single specific attribute, by name
    *
    * @param string $name
    * @return Angie_DBA_Generator_Attribute
    */
    function getAttribute($name) {
      return array_var($this->attributes, $name);
    } // getAttribute
    
    /**
    * Add attribute to the entity
    *
    * @param Angie_DBA_Generator_Attribute $attribute
    * @return Angie_DBA_Generator_Attribute
    */
    function addAttribute(Angie_DBA_Generator_Attribute $attribute) {
      $this->attributes[$attribute->getName()] = $attribute;
      return $attribute;
    } // addAttribute
  
  } // Angie_DBA_Generator_Entity

?>