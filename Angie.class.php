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
  
  } // Angie

?>