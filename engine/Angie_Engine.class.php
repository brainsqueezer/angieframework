<?php

  /**
  * Abstract engine - this class provides stub function and partial implementation 
  * of default behaviour. Purpose of engine is to tie rest of the system together - 
  * to know how to access controllers, how to build models, how to init application 
  * etc. Every Angie project can override default behaviour and implement things 
  * specific for that project without hacking the rest of the system
  *
  * @package Angie.engines
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Engine {
    
    /**
    * Construct the engine. This function will register close() method that will be 
    * executed on script shutdown
    *
    * @param void
    * @return Angie_Engine
    */
    function __construct() {
      register_shutdown_function(array($this, 'close'));
    } // __construct
    
    // ---------------------------------------------------
    //  Abstract functions
    // ---------------------------------------------------
  
    /**
    * Init the system - this function is called after the engine is contructed to init 
    * all the resources requred by the engine and prepare the environment
    *
    * @param void
    * @return null
    */
    abstract function init();
    
    /**
    * Execute request. Request is prepared outside of engine class and forwareded to 
    * the engine
    *
    * @param Angie_Request
    * @return null
    */
    abstract function execute(Angie_Request $request);
    
    /**
    * Clean up function - this one is called on script shutdown (works in multiengine 
    * environment too). Use it save logs, send status emails, update status or whatever
    * need to be done on end of page execution
    *
    * @param void
    * @return null
    */
    abstract function close();
    
    // ---------------------------------------------------
    //  Util methods
    // ---------------------------------------------------
    
    /**
  * Return controller name based on controller class; name will be converted to underscore 
  * and 'Controller' sufix will be removed
  *
  * @param string $controller_class
  * @return string
  */
  function getControllerName($controller_class) {
    return Inflector::underscore(substr($controller_class, 0, strlen($controller_class) - 10));
  } // getControllerName
  
  /**
  * Return controller class based on controller name; controller name will be 
  * camelized and Controller will be added as sufix
  *
  * @param string $controller_name
  * @return string
  */
  function getControllerClass($controller_name) {
    return Inflector::camelize($controller_name) . 'Controller';
  } // getControllerClass
    
  } // Angie_Engine

?>