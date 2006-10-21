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
    
    // Types
    const TYPE_INTEGER  = 'INT';
    const TYPE_FLOAT    = 'FLOAT';
    const TYPE_CHAR     = 'CHAR';
    const TYPE_VARCHAR  = 'VARCHAR';
    const TYPE_TEXT     = 'TEXT';
    const TYPE_DATETIME = 'DATETIME';
    const TYPE_DATE     = 'DATE';
    const TYPE_TIME     = 'TIME';
    const TYPE_BLOB     = 'BLOB';
    
    // Sizes
    const SIZE_NORMAL = '';
    const SIZE_TINY   = 'TINY';
    const SIZE_SMALL  = 'SMALL';
    const SIZE_MEDIUM = 'MEDIUM';
    const SIZE_BIG    = 'BIG';
  
    /**
    * Array of entities that need to be generated
    *
    * @var array
    */
    private static $entities = array();
    
    /**
    * Return all entities
    *
    * @param void
    * @return array
    */
    function getEntities() {
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
    function getEntity($name) {
      return array_var(self::$entities, $name);
    } // getEntity
    
    /**
    * Add entity
    * 
    * $entity can be valid Angie_DBA_Generator_Entity or entity name (new entity will be created).
    *
    * @param mixed $entity
    * @return Angie_DBA_Generator_Entity
    */
    static function addEntity($entity) {
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
      return self::$entities[$entity_name];
    } // addEntity
    
    /**
    * Remove entity by name
    *
    * @param string $name
    * @return null
    */
    function removeEntity($name) {
      if(isset(self::$entities[$name])) {
        unset(self::$entities[$name]);
      } // if
    } // removeEntity
  
  } // Angie_DBA_Generator

?>