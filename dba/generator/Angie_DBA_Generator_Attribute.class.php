<?php

  /**
  * Entity attribute
  *
  * @package Angie.DBA
  * @subpackage generator
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_DBA_Generator_Attribute extends Angie_DBA_Generator_Block {
    
    /**
    * Attribute name
    *
    * @var string
    */
    private $name;
    
    /**
    * Default attribute value
    *
    * @var mixed
    */
    private $default_value = null;
    
    /**
    * Value is required
    *
    * @var boolean
    */
    private $requred = false;
  
    /**
    * Constructor
    *
    * @param Angie_DBA_Generator_Entity $owner_entity
    * @param string $name
    * @param mixed $default_value
    * @param boolean $required
    * @return Angie_DBA_Generator_Attribute
    */
    function __construct(Angie_DBA_Generator_Entity $owner_entity, $name, $default_value = null, $required = false) {
      parent::__construct($owner_entity);
      
      $this->setName($name);
      $this->setDefaultValue($default_value);
      $this->setRequired($required);
      
//      $fields = $this->getFields();
//      if(is_array($fields)) {
//        foreach($fields as $field) {
//          $this->getOwnerEntity()->addField($field, $this);
//        } // foreach
//      } elseif($fields instanceof Angie_DBA_Generator_Field) {
//        $this->getOwnerEntity()->addField($fields, $this);
//      } // if
    } // __construct
    
    // ---------------------------------------------------
    //  Abstract
    // ---------------------------------------------------
    
    /**
    * Return array or single attribute field
    *
    * @param void
    * @return mixed
    */
    abstract function getFields();
    
    // ---------------------------------------------------
    //  Renderer
    // ---------------------------------------------------
    
    /**
    * Render object class properties and methods
    *
    * @param void
    * @return null
    */
//    function renderObjectMembers() {
//      return;
//    } // renderObjectMembers
    
    /**
    * Render manager class fields and methods
    *
    * @param void
    * @return null
    */
//    function renderManagerMembers() {
//      return;
//    } // renderManagerMembers
    
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
    * Get default_value
    *
    * @param null
    * @return mixed
    */
    function getDefaultValue() {
      return $this->default_value;
    } // getDefaultValue
    
    /**
    * Set default_value value
    *
    * @param mixed $value
    * @return null
    */
    function setDefaultValue($value) {
      $this->default_value = $value;
    } // setDefaultValue
    
    /**
    * Get required
    *
    * @param null
    * @return boolean
    */
    function getRequired() {
      return $this->required;
    } // getRequired
    
    /**
    * Set required value
    *
    * @param boolean $value
    * @return null
    */
    function setRequired($value) {
      $this->required = $value;
    } // setRequired
  
  } // Angie_DBA_Generator_Attribute

?>