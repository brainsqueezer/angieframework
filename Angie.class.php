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
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
    * This function will include project engine, construct it and set it under $engine_name
    *
    * @param string $where_is_it Directory where engine class is
    * @param string $class_name Name of the engine class
    * @param string $engine_name Save engine under this name. If NULL engine will be set as
    *   default engine
    * @return null
    * @throws Angie_Error_FileSystem_FileDnx If engine file is not found
    * @throws Angie_Error_Core_InvalidInstance If $class_name is not valid engine class
    */
    static function setProjectEngine($where_is_it, $class_name, $engine_name = null) {
      $class_file = with_slash($where_is_it) . $class_name . '.class.php';
      if(!is_file($class_file)) {
        throw new Angie_Error_FileSystem_FileDnx($class_file);
      } // if
      
      require $class_file;
      
      $engine = new $class_name();
      if(!($engine instanceof Angie_Engine)) {
        throw new Angie_Error_Core_InvalidInstance('engine', $engine, 'Angie_Engine');
      } // if
      
      self::setEngine($engine, $engine_name);
    } // setProjectEngine
    
    /**
    * This function will prepare request type, construct it with $request_string and execute 
    * it with $engine_name engine
    *
    * @param string $request_type Class of request type (Get, Routed, Console etc)
    * @param string $request_string Request string
    * @param string $engine_name Execute request using this engine. If NULL default engine will
    *   be used
    * @return null
    * @throws Angie_Error_FileSystem_FileDnx If $request_type is not found
    * @throws Angie_Error_Core_InvalidInstance If $request_type is not valid request type
    */
    static function prepareRequestAndExecute($request_type, $request_string, $engine_name = null) {
      $request_class_file = ANGIE_PATH . "/controller/request/$request_type.class.php";
      if(!is_file($request_class_file)) {
        throw new Angie_Error_FileSystem_FileDnx($request_class_file);
      } // if
      
      require $request_class_file;
      
      $request = new $request_type($request_string);
      if(!($request instanceof Angie_Request)) {
        throw new Angie_Error_Core_InvalidInstance('request', $request, 'Angie_Request');
      } // if
      
      self::engine($engine_name)->execute($request);
    } // prepareRequestAndExecute
  
  } // Angie

?>