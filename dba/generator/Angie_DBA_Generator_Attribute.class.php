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
    * Native (PHP) type. This value is used in generated docs for accessors
    *
    * @var string
    */
    protected $native_type = 'mixed';
  
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
    
    /**
    * Render object class properties and methods
    *
    * @param void
    * @return null
    */
    function renderObjectMembers() {
      Angie_DBA_Generator::assignToView('attribute', $this);
      Angie_DBA_Generator::displayView('attribute_object_members');
    } // renderObjectMembers
    
    /**
    * Render manager class fields and methods
    *
    * @param void
    * @return null
    */
    function renderManagerMembers() {
      return;
    } // renderManagerMembers
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Return getter name
    *
    * @param void
    * @return string
    */
    function getGetterName() {
      return 'get' . Angie_Inflector::camelize($this->getName());
    } // getGetterName
    
    /**
    * Return setter name
    *
    * @param void
    * @return string
    */
    function getSetterName() {
      return 'set' . Angie_Inflector::camelize($this->getName());
    } // getSetterName
    
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
    
    /**
    * Return name of the native type
    *
    * @param void
    * @return string
    */
    function getNativeType() {
      return $this->native_type;
    } // getNativeType
  
  } // Angie_DBA_Generator_Attribute

?>