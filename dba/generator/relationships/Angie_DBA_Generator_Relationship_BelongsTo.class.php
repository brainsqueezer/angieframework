<?php

  /**
  * Belongs to relationship
  *
  * @package Angie.DBA
  * @subpackage generator.relationships
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Relationship_BelongsTo extends Angie_DBA_Generator_Relationship {
  
    /**
    * Name of the relation class
    *
    * @var string
    */
    private $class_name;
    
    /**
    * Additional conditions that will be used for reading related objects
    *
    * @var string
    */
    private $conditions;
    
    /**
    * Order part of the query used when querying related objects
    *
    * @var string
    */
    private $order;
    
    /**
    * Name of the relationship field
    *
    * @var string
    */
    private $field_name;
    
    // ---------------------------------------------------
    //  Fields implementation
    // ---------------------------------------------------
    
    /**
    * Return fields that describe this relationship
    *
    * @param void
    * @return null
    */
    function getFields() {
      $owner_entity = $this->getEntity();
      if($owner_entity instanceof Angie_DBA_Generator_Entity) {
        $field_name = $this->getFieldName();
        
        if(!$owner_entity->fieldExists($field_name)) {
          return new Angie_DBA_Generator_IntegerField($field_name, true);
        } // if
      } // if
      return null;
    } // getFields
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get class_name
    *
    * @param null
    * @return string
    */
    function getClassName() {
      return $this->class_name;
    } // getClassName
    
    /**
    * Set class_name value
    *
    * @param string $value
    * @return null
    */
    function setClassName($value) {
      $this->class_name = $value;
    } // setClassName
    
    /**
    * Get conditions
    *
    * @param null
    * @return string
    */
    function getConditions() {
      return $this->conditions;
    } // getConditions
    
    /**
    * Set conditions value
    *
    * @param string $value
    * @return null
    */
    function setConditions($value) {
      $this->conditions = $value;
    } // setConditions
    
    /**
    * Get order
    *
    * @param null
    * @return string
    */
    function getOrder() {
      return $this->order;
    } // getOrder
    
    /**
    * Set order value
    *
    * @param string $value
    * @return null
    */
    function setOrder($value) {
      $this->order = $value;
    } // setOrder
    
    /**
    * Get field_name
    *
    * @param null
    * @return string
    */
    function getFieldName() {
      if(is_null($this->field_name)) {
        return $this->getTargetEntityName() . '_id';
      } else {
        return $this->field_name;
      } // if 
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
  
  } // Angie_DBA_Generator_Relationship_BelongsTo

?>