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
    const SIZE_NORMAL   = '';
    const SIZE_TINY     = 'TINY';
    const SIZE_SMALL    = 'SMALL';
    const SIZE_MEDIUM   = 'MEDIUM';
    const SIZE_BIG      = 'BIG';
    
    // Call callback function on
    const ON_INSERT     = 'insert';
    const ON_UPDATE     = 'update';
    const ON_SAVE       = 'save';
  
    /**
    * Array of entities that need to be generated
    *
    * @var array
    */
    private static $entities = array();
    
    /**
    * Output directory
    *
    * @var string
    */
    private static $output_dir;
    
    /**
    * Template engine used in generation process
    *
    * @var Angie_TemplateEngine
    */
    private static $template_engine;
    
    // ---------------------------------------------------
    //  Util methos
    // ---------------------------------------------------
    
    /**
    * Generate classes based on description
    *
    * @param Angie_Output $output
    * @param array $options
    * @return null
    */
    static function generate(Angie_Output $output, $options = null) {
      $output->printMessage("DBA generator started\n=====================");
      
      // Check output directory
      if(!is_dir(self::$output_dir)) {
        throw new Angie_FileSystem_Error_DirDnx(self::$output_dir);
      } // if
      
      if(!folder_is_writable(self::$output_dir)) {
        throw new Angie_FileSystem_Error_DirNotWritable(self::$output_dir);
      } // if
      
      $output->printMessage('Output directory exists and is writable', 'ok');
      
      // Prepare
      self::prepare();
      $output->printMessage('Model description prepared', 'ok');
      
      // Loop through entities
      if(is_foreachable(self::$entities)) {
        foreach(self::$entities as $entity) {
          $entity_output_dir = with_slash(self::$output_dir) . $entity->getOutputDir();
          
          if(is_dir($entity_output_dir)) {
            $output->printMessage("Directory '" . self::relativeToOutput($entity_output_dir) . "' exists", 'skip');
          } else {
            if(mkdir($entity_output_dir)) {
              $output->printMessage("Directory '" . self::relativeToOutput($entity_output_dir) . "' created", '+');
            } else {
              throw new Angie_FileSystem_Error_DirNotWritable(self::$output_dir);
            } // if
          } // if
          
          $entity->generate($output, $entity_output_dir, $options);
        } // foreach
      } // if
      
      $output->printMessage("=====================\nJob done\n");
    } // generate
    
    /**
    * Prepare before generation
    * 
    * Last call for entities to prepare whatever they need to prepare before we start to build 
    * classes. This is protected method and it is called from within generate() method
    *
    * @param void
    * @return null
    */
    protected static function prepare() {
      if(is_foreachable(self::$entities)) {
        foreach(self::$entities as $entity) {
          $entity->prepare();
        } // foreach
      } // if
    } // prepare
    
    /**
    * Clean up the generator data
    *
    * @param void
    * @return null
    */
    static function cleanUp() {
      self::$entities = array();
    } // cleanUp
    
    /**
    * This will return only part of the path relative to $output_dir
    *
    * @param string $path
    * @return string
    */
    static function relativeToOutput($path) {
      return substr($path, strlen(self::$output_dir));
    } // relativeToOutput
    
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
    static function fetchView($view_name) {
      return self::getTemplateEngine()->fetchView(self::getTemplatePath($view_name));
    } // fetchView
    
    /**
    * Render view directly to the output buffer
    *
    * @param string $view_name
    * @return boolean
    */
    static function displayView($view_name) {
      return self::getTemplateEngine()->displayView(self::getTemplatePath($view_name));
    } // displayView
    
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
    static function removeEntity($name) {
      if(isset(self::$entities[$name])) {
        unset(self::$entities[$name]);
      } // if
    } // removeEntity
    
    /**
    * Get output_dir
    *
    * @param null
    * @return string
    */
    static function getOutputDir() {
      return self::$output_dir;
    } // getOutputDir
    
    /**
    * Set output_dir value
    *
    * @param string $value
    * @return null
    */
    static function setOutputDir($value) {
      self::$output_dir = $value;
    } // setOutputDir
  
  } // Angie_DBA_Generator

?>