<?php

  /**
  * Angie class provides interface to engine instance - object that ties the whole 
  * project in one system. Angie is able to have multiple engines running at the 
  * same time
  *
  * @package Angie
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  final class Angie {
    
    /** Default controller and action value **/
    const DEFAULT_CONTROLLER_NAME = 'default';
    const DEFAULT_ACTION_NAME = 'index';
  
    /**
    * Default engine
    * 
    * Used when $engine_name is not provided to engine() method
    *
    * @var Angie_Engine
    */
    static private $default_engine = null;
    
    /**
    * Array of additional engines that can be accessed by name
    *
    * @var array
    */
    static private $additional_engines = array();
    
    /**
    * Template engine instance used by the application
    *
    * @var Angie_TempalteEngine
    */
    static private $template_engine;
    
    /**
    * Cached array of available console commands
    *
    * @var array
    */
    static private $available_commands;
    
    /**
    * Array of key -> value configuration pairs
    *
    * @var array
    */
    static private $config_data = array();
    
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
    * Load project routes
    *
    * @param string $where_is_it
    * @return null
    */
    static function loadRoutes($where_is_it) {
      $routes_file = rtrim($where_is_it, '/') . '/routes.php';
      if(!is_file($routes_file)) {
        throw new Angie_FileSystem_Error_FileDnx($routes_file);
      } // if
      require $routes_file;
    } // loadRoutes
    
    /**
    * This function will include project engine, construct it and set it under $engine_name
    * 
    * $class_name is the name of the engine class. It is usually camelized project name with Engine sufix but it can be 
    * changed by user. When engine is constructed it will be set as default engine and returned
    *
    * @param string $where_is_it
    * @param string $engine_class
    * @return Angie_Engine
    * @throws Angie_FileSystem_Error_FileDnx If engine file is not found
    * @throws Angie_Core_Error_InvalidInstance If $class_name is not valid engine class
    */
    static function setProjectEngine($where_is_it, $engine_class) {
      require_once rtrim($where_is_it, '/') . '/engine.php';
      
      $engine = new $engine_class();
      if(!($engine instanceof Angie_Engine)) {
        throw new Angie_Core_Error_InvalidInstance('engine', $engine, 'Angie_Engine');
      } // if
      
      self::setEngine($engine);
      return $engine;
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
    
    /**
    * Return array of available commands
    * 
    * This function will return an array of available commands by reading content of angies and projects commands folders. 
    * Project commands are are read later on so they can override any commands available in framework. Result is an array 
    * where key is command name and value is file where command handler is defined.
    *
    * @param void
    * @return array
    */
    static function getAvailableCommands() {
      if(is_array(self::$available_commands)) {
        return self::$available_commands;
      } // if
      
      self::$available_commands = array();
    
      $search_in = array(ANGIE_PATH . '/project/commands/');
      if(defined('DEVELOPMENT_PATH') && is_dir(DEVELOPMENT_PATH . '/scripts/commands/')) {
        $search_in[] = DEVELOPMENT_PATH . '/scripts/commands/';
      } // if
      
      foreach($search_in as $path) {
        $path = with_slash($path);
        $files = get_files($path, 'php');
        
        if(is_foreachable($files)) {
          foreach($files as $file) {
            if(str_ends_with($file, '.php')) {
              $command = substr($file, strlen($path), strlen($file) - strlen($path) - 4);
              self::$available_commands[$command] = $file;
            } // if
          } // foreach
        } // if
      } // foreach
      
      return self::$available_commands;
    } // getAvailableCommands
    
    /**
    * Get command handler class based on command name and construct it
    * 
    * Conver command name to expected command class and, if class is loaded, construct the handler object. If class is 
    * not loaded this function will throw an exception. While detecting if class exists this function will use autoload 
    * function.
    *
    * @param string $command
    * @return Angie_Console_Command
    * @throws Angie_Core_Error_InvalidParamValue
    * @throws Angie_Core_Error_InvalidInstance
    */
    static function constructCommandHandler($command) {
      $handler_class_name = 'Angie_Command_' . Angie_Inflector::camelize($command);
      if(!class_exists($handler_class_name, true)) {
        throw new Angie_Core_Error_InvalidParamValue('command', $command, "Command handler for '$command' does not exist");
      } // if
      
      $command_handler = new $handler_class_name();
      if(!($command_handler instanceof Angie_Console_Command)) {
        throw new Angie_Core_Error_InvalidInstance('command_handler', $command_handler, "Command class '$handler_class_name' does not inherit Angie_Console_Command");
      } // if
      
      return $command_handler;
    } // constructCommandHandler
    
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
    
    /**
    * Return configuration value
    *
    * @param string $name
    * @return mixed
    */
    static function getConfig($name, $default = null) {
      return array_var(self::$config_data, $name, $default);
    } // getConfig
    
    /**
    * Set value of specific configuration option
    *
    * @param string $name
    * @param mixed $value
    * @return null
    */
    static function setConfig($name, $value) {
      $trimmed = trim($name);
      if($trimmed) {
        self::$config_data[$trimmed] = $value;
      } else {
        throw new Angie_Core_Error_InvalidParamValue('name', $name, 'Name value must be present');
      } // if
    } // setConfig
  
  } // Angie

?>