<?php

  class Angie_DBA_Generator_AutoSetter {
    
    /**
    * Owner entity object
    *
    * @var Angie_DBA_Generator_Entity
    */
    private $entity;
    
    /**
    * Owner attribute
    *
    * @var Angie_DBA_Generator_Attribute
    */
    private $attribute;
    
    /**
    * Name of the owner attribute that will be set by this auto setter
    *
    * @var string
    */
    private $attribute_name;
    
    /**
    * Name of the callback function
    *
    * @var string
    */
    private $callback_function;
    
    /**
    * This auto setter can be called on insert, on update or on save
    *
    * @var string
    */
    private $call_on;
    
    /**
    * Pass caller reference flag
    *
    * @var boolean
    */
    private $pass_caller;
  
    /**
    * Constructor
    *
    * @param mixed $field
    * @param string $callback
    * @param string $call_on
    * @param boolean $pass_caller
    * @return Angie_DBA_Generator_AutoSetter
    */
    function __construct($attribute, $callback, $call_on, $pass_caller = false) {
      if($attribute instanceof Angie_DBA_Generator_Attribute) {
        $this->setAttribute($attribute);
      } else {
        $this->setAttributeName($attribute);
      } // if
      $this->setCallback($callback);
      $this->setCallOn($call_on);
      $this->setPassCaller($pass_caller);
    } // __construct
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Return name of the field
    *
    * @param void
    * @return string
    */
    function getFieldName() {
      $attribute = $this->getAttribute();
      
      $fields = $attribute->getFields();
      if(is_array($fields)) {
        foreach($fields as $field) {
          return $field->getName();
        } // if
      } elseif($fields instanceof Angie_DB_Field) {
        return $fields->getName();
      } // if
      
      return '';
    } // getFieldName
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get entity
    *
    * @param null
    * @return Angie_DBA_Generator_Entity
    */
    function getEntity() {
      return $this->entity;
    } // getEntity
    
    /**
    * Set entity value
    *
    * @param Angie_DBA_Generator_Entity $value
    * @return null
    */
    function setEntity(Angie_DBA_Generator_Entity $value) {
      $this->entity = $value;
    } // setEntity
    
    /**
    * Get attribute
    *
    * @param null
    * @return Angie_DBA_Generator_Attribute
    */
    function getAttribute() {
      if(!($this->attribute instanceof Angie_DBA_Generator_Attribute)) {
        $entity = $this->getEntity();
        if($entity instanceof Angie_DBA_Generator_Entity) {
          $attribute = $entity->getAttribute($this->getAttributeName());
          if($attribute instanceof Angie_DBA_Generator_Attribute) {
            $this->attribute = $attribute;
          } // if
        } // if
      } // if
      return $this->attribute;
    } // getAttribute
    
    /**
    * Set attribute value
    *
    * @param Angie_DBA_Generator_Attribute $value
    * @return null
    */
    function setAttribute(Angie_DBA_Generator_Attribute $value) {
      $this->attribute = $value;
      $this->attribute_name = $value->getName();
    } // setAttribute
    
    /**
    * Get attribute_name
    *
    * @param null
    * @return string
    */
    function getAttributeName() {
      return $this->attribute_name;
    } // getAttributeName
    
    /**
    * Set attribute_name value
    *
    * @param string $value
    * @return null
    */
    function setAttributeName($value) {
      $this->attribute_name = $value;
    } // setAttributeName
    
    /**
    * Get callback
    *
    * @param null
    * @return string
    */
    function getCallback() {
      return $this->callback;
    } // getCallback
    
    /**
    * Set callback value
    *
    * @param string $value
    * @return null
    */
    function setCallback($value) {
      $this->callback = $value;
    } // setCallback
    
    /**
    * Get call_on
    *
    * @param null
    * @return string
    */
    function getCallOn() {
      return $this->call_on;
    } // getCallOn
    
    /**
    * Set call_on value
    *
    * @param string $value
    * @return null
    */
    function setCallOn($value) {
      $this->call_on = $value;
    } // setCallOn
    
    /**
    * Get pass_caller
    *
    * @param null
    * @return boolean
    */
    function getPassCaller() {
      return $this->pass_caller;
    } // getPassCaller
    
    /**
    * Set pass_caller value
    *
    * @param boolean $value
    * @return null
    */
    function setPassCaller($value) {
      $this->pass_caller = $value;
    } // setPassCaller
  
  } // Angie_DBA_Generator_AutoSetter

?>