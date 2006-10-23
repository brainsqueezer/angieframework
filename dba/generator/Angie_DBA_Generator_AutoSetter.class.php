<?php

  class Angie_DBA_Generator_AutoSetter {
    
    /**
    * Owner entity object
    *
    * @var Angie_DBA_Generator_Entity
    */
    private $entity;
    
    /**
    * Field that is set by this auto setter
    *
    * @var Angie_DBA_Generator_Field
    */
    private $field;
    
    /**
    * Name of the field that will be set by this auto setter
    *
    * @var string
    */
    private $field_name;
    
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
    * Constructor
    *
    * @param mixed $field
    * @param string $callback
    * @param string $call_on
    * @return Angie_DBA_Generator_AutoSetter
    */
    function __construct($field, $callback, $call_on) {
      if($field instanceof Angie_DBA_Generator_Field) {
        $this->setField($field);
      } else {
        $this->setFieldName($field);
      } // if
      $this->setCallback($callback);
      $this->setCallOn($call_on);
    } // __construct
    
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
    * Get field
    *
    * @param null
    * @return Angie_DBA_Generator_Field
    */
    function getField() {
      if(is_null($this->field)) {
        $entity = $this->getEntity();
        if($entity instanceof Angie_DBA_Generator_Entity) {
          $field = $entity->getField($this->getFieldName());
          if($field instanceof Angie_DBA_Generator_Field) {
            $this->field = $field;
          } // if
        } // if
      } // if
      return $this->field;
    } // getField
    
    /**
    * Set field value
    *
    * @param Angie_DBA_Generator_Field $value
    * @return null
    */
    function setField($value) {
      $this->field = $value;
    } // setField
    
    /**
    * Get field_name
    *
    * @param null
    * @return string
    */
    function getFieldName() {
      return $this->field_name;
    } // getFieldName
    
    /**
    * Set field_name value
    *
    * @param string $value
    * @return null
    */
    function setFieldName($value) {
      $this->field_name = $value;
    } // setFieldName
    
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
  
  } // Angie_DBA_Generator_AutoSetter

?>