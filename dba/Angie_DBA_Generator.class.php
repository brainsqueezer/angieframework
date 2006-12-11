<?php

  /**
  * Generator
  * 
  * DBA generator is used to build (generate) classes based on description provided by user. Generator support entities 
  * that can be saved in some persistent storage (in most cases database), generats complete access methods, relations, 
  * supports caching etc.
  *
  * @package Angie.DBA
  * @subpackage generator
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  final class Angie_DBA_Generator {
    
    // Call callback function on
    const ON_INSERT     = 'insert';
    const ON_UPDATE     = 'update';
    const ON_SAVE       = 'save';
    
    // Values of on_delete option for has_one and has_many relationships
    const ON_DELETE_CASCADE = 'cascade';
    const ON_DELETE_DELETE = 'delete';
    const ON_DELETE_NULLIFY = 'nullify';
    const ON_DELETE_DO_NOTHING = 'do_nothing';
  
    /**
    * Array of entities that need to be generated
    *
    * @var array
    */
    private static $entities = array();
    
    /**
    * Template engine used in generation process
    *
    * @var Angie_TemplateEngine
    */
    private static $template_engine;
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Return array of database tables produced by this model
    * 
    * This function will walk through entities array and return tables based on them. It will also construct tables for 
    * has_and_belongs_to_many relationships if they does not exists.
    * 
    * Return array is indexed with table name
    *
    * @param Angie_DB_Connection $connection
    * @return array
    */
    static function getTables(Angie_DB_Connection $connection, $prefix = '') {
      $tables = array();
      if(is_foreachable(self::getEntities())) {
        foreach(self::getEntities() as $entity) {
          $table = $connection->produceTable(
            $entity->getTableName(), 
            $entity->getFields(), 
            $entity->getPrimaryKeyFieldNames()
          ); // new table
          $table->setPrefix($prefix);
          
          $tables[$entity->getTableName()] = $table;
          
          // For has and belongs to many
          $relationships = $entity->getRelationships();
          if(is_foreachable($relationships)) {
            foreach($relationships as $relationship) {
              if(($relationship instanceof Angie_DBA_Generator_Relationship_HasAndBelongsToMany) && !isset($tables[$relationship->getJoinTable()])) {
                $owner_key_field = new Angie_DB_Field_Integer($relationship->getOwnerKey(), null, true);
                $owner_key_field->setUnsigned(true);
                
                $target_key_field = new Angie_DB_Field_Integer($relationship->getTargetKey(), null, true);
                $target_key_field->setUnsigned(true);
                
                $table = $connection->produceTable(
                  $relationship->getJoinTable(), 
                  array($owner_key_field, $target_key_field), 
                  array($relationship->getOwnerKey(), $relationship->getTargetKey())
                ); // produceTable
                $table->setPrefix($prefix);
                
                $tables[$relationship->getJoinTable()] = $table;
              } // if
            } // if
          } // if
          
        } // foreach
      } // if
      return $tables;
    } // getTables
    
    /**
    * Clean up the generator data
    *
    * @param void
    * @return null
    */
    static function cleanUp() {
      self::$entities = array();
    } // cleanUp
    
    // ---------------------------------------------------
    //  Template interface
    // ---------------------------------------------------
    
    /**
    * Return template engine instance
    *
    * @param void
    * @return Angie_TemplateEngine_Php
    */
    static function getTemplateEngine() {
      if(!(self::$template_engine instanceof Angie_TemplateEngine_Php)) {
        self::$template_engine = new Angie_TemplateEngine_Php();
      } // if
      return self::$template_engine;
    } // getTemplateEngine
    
    /**
    * Return generator template path
    *
    * @param string $template_name
    * @return string
    */
    static function getTemplatePath($template_name) {
      static $templates_dir;
      
      if(is_null($templates_dir)) {
        $templates_dir = dirname(__FILE__) . '/generator/templates/';
      } // if
      
      return $templates_dir . $template_name . '.php';;
    } // getTemplatePath
    
    /**
    * Assign variable to view
    *
    * @param string $variable_name
    * @param mixed $variable_value
    * @return boolean
    */
    static function assignToView($variable_name, $variable_value = null) {
      return self::getTemplateEngine()->assignToView($variable_name, $variable_value);
    } // assignToView
    
    /**
    * Render view and return the output as a string
    *
    * @param string $view_name
    * @return string
    */
    static function fetchView($view_name, $external = false) {
      $view_path = $external ? $view_name : self::getTemplatePath($view_name);
      return self::getTemplateEngine()->fetchView($view_path);
    } // fetchView
    
    /**
    * Render view directly to the output buffer
    *
    * @param string $view_name
    * @return boolean
    */
    static function displayView($view_name, $external = false) {
      $view_path = $external ? $view_name : self::getTemplatePath($view_name);
      return self::getTemplateEngine()->displayView($view_path);
    } // displayView
    
    // ---------------------------------------------------
    //  Helper methods
    // ---------------------------------------------------
    
    /**
    * Return true if $value is a valid on_delete option value
    *
    * @param string $value
    * @return boolean
    */
    static function isValidOnDeleteValue($value) {
      return in_array($value, array(
        self::ON_DELETE_CASCADE,
        self::ON_DELETE_DELETE,
        self::ON_DELETE_NULLIFY,
        self::ON_DELETE_DO_NOTHING,
      )); // in_array
    } // isValidOnDeleteValue
    
    /**
    * Return has and belongs to many relation join table name based on owner and target entity
    *
    * @param Angie_DBA_Generator_Entity $owner_entity
    * @param Angie_DBA_Generator_Entity $target_entity
    * @return string
    */
    static function getHABTMJoinTableName(Angie_DBA_Generator_Entity $owner_entity, Angie_DBA_Generator_Entity $target_entity) {
      $mixer = array(
        Angie_Inflector::pluralize($owner_entity->getName()),
        Angie_Inflector::pluralize($target_entity->getName()),
      ); // array
      
      sort($mixer);
      return implode('_', $mixer);
    } // getHABTMJoinTableName
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Return all entities
    *
    * @param void
    * @return array
    */
    static function getEntities() {
      return self::$entities;
    } // getEntities
    
    /**
    * Return entity
    * 
    * Return entity by $name. If entity does not exists NULL is returned
    *
    * @param void
    * @return Angie_DBA_Generator_Entity
    */
    static function getEntity($name) {
      return array_var(self::$entities, $name);
    } // getEntity
    
    /**
    * Add entity
    * 
    * $entity can be valid Angie_DBA_Generator_Entity or entity name (new entity 
    * will be created). If $id parameter is present it will be used a name for 
    * unsgined, auto increment integer attribute named with that value. This is 
    * a simple shortcut for defining most common type of ID field
    *
    * @param mixed $entity
    * @param string $id
    * @return Angie_DBA_Generator_Entity
    */
    static function addEntity($entity, $id = null) {
      if($entity instanceof Angie_DBA_Generator_Entity) {
        $entity_name = $entity->getName();
        self::$entities[$entity_name] = $entity;
      } else {
        $entity_name = trim($entity);
        if($entity_name) {
          self::$entities[$entity_name] = new Angie_DBA_Generator_Entity($entity_name);
        } else {
          throw new Angie_Core_Error_InvalidParamValue('entity', $entity, '$entity should be an entity name or valid entity object');
        } // if
      } // if
      
      if($id) {
        self::$entities[$entity_name]->addIdAttribute($id, true);
      } // if
      
      return self::$entities[$entity_name];
    } // addEntity
    
    /**
    * Remove entity by name
    *
    * @param string $name
    * @return null
    */
    static function removeEntity($name) {
      if(isset(self::$entities[$name])) {
        unset(self::$entities[$name]);
      } // if
    } // removeEntity
  
  } // Angie_DBA_Generator

?>