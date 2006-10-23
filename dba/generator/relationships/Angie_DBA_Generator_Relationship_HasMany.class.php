<?php

  /**
  * Has many relationship
  *
  * @package Angie.DBA
  * @subpackage generator.relationships
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DBA_Generator_Relationship_HasMany extends Angie_DBA_Generator_Relationship {
  
    /**
    * Target class name
    *
    * @var string
    */
    private $class_name;
    
    /**
    * Query conditions
    *
    * @var string
    */
    private $conditions;
    
    /**
    * Query order
    *
    * @var string
    */
    private $order;
    
    // ---------------------------------------------------
    //  Abstract
    // ---------------------------------------------------
    
    /**
    * Return additional fields introduced by this relationship
    *
    * @param void
    * @return array
    */
    function getFields() {
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
  
  } // Angie_DBA_Generator_Relationship_HasMany

?>