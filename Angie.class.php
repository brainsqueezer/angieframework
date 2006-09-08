<?php

  /**
  * Angie class provides interface to engine instance - object that ties the whole 
  * project in one system. Angie is able to have multiple engines running at the 
  * same time
  *
  * @package Angie
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie {
    
    /** Default controller and action value **/
    const DEFAULT_CONTROLLER_NAME = 'default';
    const DEFAULT_ACTION_NAME = 'index';
  
    /**
    * Default engine, it is used when $engine_name is not provided to engine() method
    *
    * @var Angie_Engine
    */
    static $default_engine = null;
    
    /**
    * Array of additional engines that can be accessed by name
    *
    * @var array
    */
    static $additional_engines = array();
    
    /**
    * Template engine instance used by the application
    *
    * @var Angie_TempalteEngine
    */
    static $template_engine;
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * Load specific environment configuration from specific folder (usuably /config)
    *
    * @param string $where_is_it Where to look for configuration file
    * @param string $environment Name of the environment that we need to load
    * @return null
    */
    static function loadConfiguration($where_is_it, $environment) {
      $configuration_file = with_slash($where_is_it) . $environment . '.php';
      if(!is_file($configuration_file)) {
        throw new Angie_FileSystem_Error_FileDnx($configuration_file);
      } // if
      require $configuration_file;
    } // loadConfiguration
    
    /**
    * This function will include project engine, construct it and set it under $engine_name
    *
    * @param string $where_is_it Directory where engine class is
    * @param string $class_name Name of the engine class
    * @param string $engine_name Save engine under this name. If NULL engine will be set as
    *   default engine
    * @return null
    * @throws Angie_FileSystem_Error_FileDnx If engine file is not found
    * @throws Angie_Core_Error_InvalidInstance If $class_name is not valid engine class
    */
    static function setProjectEngine($where_is_it, $class_name, $engine_name = null) {
      $class_file = with_slash($where_is_it) . $class_name . '.class.php';
      if(!is_file($class_file)) {
        throw new Angie_FileSystem_Error_FileDnx($class_file);
      } // if
      
      require $class_file;
      
      $engine = new $class_name();
      if(!($engine instanceof Angie_Engine)) {
        throw new Angie_Core_Error_InvalidInstance('engine', $engine, 'Angie_Engine');
      } // if
      
      self::setEngine($engine, $engine_name);
    } // setProjectEngine
    
    /**
    * Load, construct and set template engine by class name ($template_engine_class)
    *
    * @param string $template_engine_name
    * @return null
    */
    static function useTemplateEngine($template_engine_class) {
      $template_engine_file = ANGIE_PATH . '/template/engine/' . $template_engine_class . '.class.php';
      if(!is_file($template_engine_file)) {
        throw new Angie_FileSystem_Error_FileDnx($template_engine_file);
      } // if
      
      require $template_engine_file;
      
      $template_engine = new $template_engine_class();
      if(!($template_engine instanceof Angie_TemplateEngine)) {
        throw new Angie_Core_Error_InvalidInstance('template_engine', $template_engine, 'Angie_TemplateEngine');
      } // if
      
      self::setTemplateEngine($template_engine);
    } // useTemplateEngine
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Return engine; if $engine_name is NULL default engine is returned, else engine 
    * will be return from $additional_engines array if available
    *
    * @param string $engine_name
    * @return Angie_Engine
    */
    static function engine($engine_name = null) {
      if(is_null($engine_name)) {
        return self::$default_engine;
      } else {
        return array_var(self::$additional_engines, $engine_name);
      } // if
    } // engine
    
    /**
    * Set specific engine instance. If engine name is provided script will set additional 
    * engine. If not default engine will be set
    *
    * @param Angie_Engine $engine
    * @param string $engine_name If NULL default engine will be set
    * @return null
    */
    static function setEngine(Angie_Engine $engine, $engine_name = null) {
      $name = trim($engine_name);
      if(trim($name) == '') {
        self::$default_engine = $engine;
      } else {
        self::$additional_engines[$name] = $engine;
      } // if
    } // setEngine
    
    /**
    * Get template_engine
    *
    * @param null
    * @return Angie_TemplateEngine
    */
    static function getTemplateEngine() {
      return self::$template_engine;
    } // getTemplateEngine
    
    /**
    * Set template_engine value
    *
    * @param Angie_TemplateEngine $value
    * @return null
    */
    static function setTemplateEngine(Angie_TemplateEngine $value) {
      self::$template_engine = $value;
    } // setTemplateEngine
  
  } // Angie

?>