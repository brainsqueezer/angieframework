<?php

  /**
  * Abstract controller class that implements basic controller logic: action
  * exectution, method protection etc (we don't people play with methods that we
  * are forced to make public - such as __construct, execute etc)
  *
  * @package Angie.controller
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Controller {
    
    /**
    * Name of this controller. It is underscored class-name without Controller sufix
    *
    * @var string
    */
    private $controller_name;
    
    /**
    * Name of the action that was (or need to be) executed
    *
    * @var string
    */
    private $action;
    
    /**
    * Specify this property to set what methods will be protected - they will be invisible to 
    * execute() method. For basic controllers that just inherit abstract controller things 
    * should work just fine because methods of Angie_Controller class are automaticly 
    * protected, but for any other complex controller type (like Angie_Controller_Page) more 
    * methods need to be protected - render(), renderText() etc
    *
    * @var string
    */
    private $protect_class_methods;
    
    /**
    * Contruct controller and set controller name
    * 
    * All methods of Angie_Controller class will be protected (will not be valid action names) unless 
    * controller that inherits it implement different behavior
    *
    * @param void
    * @return Angie_Controller
    */
    function __construct() {
      $this->setControllerName(Angie::engine()->getControllerName(get_class($this)));
      $this->setProtectClassMethods('Angie_Controller');
    } // __construct
    
    /**
    * Execute specific controller action
    * 
    * Action will be executed only if it is valid (action method exists in this controller and it
    * is not protected by the controller).
    *
    * @param string $action
    * @return boolean
    * @throws Angie_Controller_Error_ActionDnx if action name is not valid or true
    */
    function execute($action) {
      $action = trim(strtolower($action));
      
      if($this->isValidAction($action)) {
        $this->setAction($action);
        $this->$action();
        return true;
      } else {
        throw new Angie_Controller_Error_ActionDnx($this->getControllerName(), $action);
      } // if
    } // execute
    
    /**
    * Forward execution from action you are in to specific controller action
    * 
    * This function lets controller actions to forword to other action without need to redirect
    * user to that specific action using URL or new console request. $action_name is required
    * argument, but $controller_name can be left out. If $controller_name is empty or it is the
    * same name of the current controller $this will be used to execute $action_name
    * 
    * Examples:
    * <pre>class MyController extends PageController {
    * 
    *   // Forward to 'do_something' action of current controller
    *   function index() {
    *     $this->forward('do_something');
    *   }
    *   
    *   // Forward to 'do_other_thing' action of 'other' controller
    *   function index() {
    *     $this->forward('do_other_thing', 'other');
    *   }
    * 
    * }</pre>
    *
    * @param string $action_name
    * @param string $controller_name
    * @return null
    */
    function forward($action_name, $controller_name = null) {
      if((trim($controller_name) == '') || ($controller_name == $this->getControllerName())) {
        $this->execute($action_name);
      } else {
        Angie::engine()->executeAction($controller_name, $action_name);
      } // if
    } // forward
    
    // ---------------------------------------------------
    //  Utils
    // ---------------------------------------------------
    
    /**
    * Check if specific $action is valid controller action
    * 
    * Action is valid if $action method exists in this class and that method is not
    * protected by the controller
    *
    * @param string $action
    * @return boolean
    */
    function isValidAction($action) {
      if($this->isProtectedActionName($action)) {
        return false; // protected action
      } // if
      
      $methods = get_class_methods(get_class($this));
      if(!in_array($action, $methods)) {
        return false; // we don't have this action defined
      } // if
      
      return true;
    } // isValidAction
    
    // -------------------------------------------------------
    // Getters and setters
    // -------------------------------------------------------
    
    /**
    * Get controller_name
    *
    * @param null
    * @return string
    */
    function getControllerName() {
      return $this->controller_name;
    } // getControllerName
    
    /**
    * Set controller_name value
    *
    * @param string $value
    * @return null
    */
    function setControllerName($value) {
      $this->controller_name = $value;
    } // setControllerName
    
    /**
    * Get action
    *
    * @param null
    * @return string
    */
    function getAction() {
      return $this->action;
    } // getAction
    
    /**
    * Set action value
    *
    * @param string $value
    * @return null
    */
    function setAction($value) {
      $this->action = $value;
    } // setAction
    
    /**
    * Get protect_class_methods
    *
    * @param null
    * @return string
    */
    function getProtectClassMethods() {
      return $this->protect_class_methods;
    } // getProtectClassMethods
    
    /**
    * Set protect_class_methods value
    *
    * @param string $value
    * @return null
    * @throws InvalidParamError If $value class does not exist. $value class need to be include because
    *   this method will not use autoloader
    */
    function setProtectClassMethods($value) {
      if(class_exists($value, false)) {
        $this->protect_class_methods = $value;
      } else {
        throw new Angie_Core_Error_InvalidParamValue('value', $value, '$value need to be a valid class name');
      } // if
    } // setProtectClassMethods
    
    /**
    * Return reserved action names (methods of controller class)
    *
    * @param void
    * @return arrays
    * @throws Error if class that we need to protect does not exists
    */
    private function getProtectedActionNames() {
      $controller_class = $this->getProtectClassMethods();
      if(!class_exists($controller_class, false)) {
        throw new Angie_Controller_Error_ControllerDnx($controller_class);
      } // if
    
      $names = get_class_methods($controller_class);
      foreach($names as $k => $v) {
        $names[$k] = strtolower($v);
      } // foreach
      
      return $names;
    } // getProtectedActionNames
    
    /**
    * Check if $action_name is protected action name
    *
    * @param string $action_name
    * @return boolean
    */
    private function isProtectedActionName($action_name) {
      return in_array($action_name, $this->getProtectedActionNames());
    } // isProtectedActionName
  
  } // Controller

?>