<?php

  /**
  * Page controller is special controller that is able to map controller name 
  * and actions name with layout and template and automaticly display them. 
  * This behaviour is present only when action has not provided any exit by 
  * itself (redirect to another page, render template and die etc)
  *
  * @package Angie.controller
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Controller_Page extends Angie_Controller implements Angie_TemplateEngine {
  
    /**
    * Name of the view. There are four supported values:
    * 
    * 1. Name is empty. Controller name is the name of this controller and name of the view
    *    is action that is executed
    * 2. View is the name of view file (without the extension). Controller that is used in 
    *    that case is this controller
    * 3. Absolute path of view file
    * 4. Array where fist param is controller name and second is the name the action
    *
    * @var mixed
    */
    private $view;
    
    /**
    * Layout name. If it is empty this controller will use its own name
    *
    * @var string
    */
    private $layout;
    
    /**
    * Array of loaded helpers. Built-in helpers are automaticly included by the controller and they are
    * not listed in this list
    *
    * @var array
    */
    private $helpers = array();
    
    /**
    * Automaticly render view / layout if action ends without exit
    *
    * @var boolean
    */
    private $auto_render = true;
    
    /**
    * Construct controller
    *
    * @param void
    * @return null
    */
    function __construct() {
      parent::__construct();
      $this->setProtectClassMethods('Angie_Controller_Page');;
      
      if(Angie::engine()->helperExists($this->getControllerName())) {
        $this->addHelper($this->getControllerName());
      } // if
    } // __construct
    
    /**
    * Execute action. This methods extends default controller behaviour by providing auto render
    * functionality to the controller - it is able to map layout / template pair based on 
    * controller and action name and automaticly render them
    *
    * @param string $action
    * @return null
    */
    function execute($action) {
      parent::execute($action);
      if($this->getAutoRender()) {
        $render = $this->render(); // Auto render?
      } // if
      return true;
    } // execute
    
    // ---------------------------------------------------
    //  Template related methods
    // ---------------------------------------------------
    
    /**
    * Assign variable value to the view. $variable_name can also be a associative array that
    * is assigned as set of params where key is variable name and value is variable value
    *
    * @param string $variable_name
    * @param mixed $variable_value
    * @return null
    */
    function assignToView($variable_name, $variable_value) {
      $template_engine = Angie::getTemplateEngine();
      if(is_array($variable_name)) {
        foreach($variable_name as $k => $v) {
          $template_engine->assignToView($k, $v);
        } // foreach
      } else {
        $template_engine->assignToView($variable_name, $variable_value);
      } // if
    } // assignToView
    
    /**
    * This function will render view and return it as a string
    *
    * @param string $view_path
    * @return string
    */
    function fetchView($view_path) {
      return Angie::getTemplateEngine()->fetchView($view_path);
    } // fetchView
    
    /**
    * This function will render view to the output buffer (it can be flushed to the borwser, cached by 
    * the other function etc)
    *
    * @param string $view_path
    * @return boolean
    */
    function displayView($view_path) {
      return Angie::getTemplateEngine()->displayView($view_path);
    } // displayView
    
    // ---------------------------------------------------
    //  Rendering related methods
    // ---------------------------------------------------
    
    /**
    * Render content of specific view / layout combination. $view can have four possible values:
    * 
    * 1. NULL or empty string. Controller name is the name of this controller and name of the view
    *    is action that is executed
    * 2. View is the name of view file (without the extension). Controller that is used in that 
    *    case is this controller
    * 3. Absolute path of view file
    * 4. Array where fist param is controller name and second is the name the action
    *
    * @param mixed $view
    * @param string $layout
    * @param boolean $die Die when rendering is done, true by default
    * @return boolean
    */
    function render($view = null, $layout = null, $die = true) {
      if(!is_null($view)) {
        $this->setView($view);
      } // if
      if(!is_null($layout)) {
        $this->setLayout($layout);
      } // if
      
      $this->renderLayout(
        $this->getLayoutPath(), // layout path
        $this->fetchView($this->getViewPath()) // content
      ); // renderLayout
      
      if($die) {
        die();
      } // if
      
      return true;
    } // render
    
    /**
    * Assign content and render layout
    *
    * @param string $layout_path Path to the layout file
    * @param string $content Value that will be assigned to the $content_for_layout
    *   variable
    * @return boolean
    * @throws FileDnxError
    */
    function renderLayout($layout_path, $content = null) {
      $this->assignToView('content_for_layout', $content);
      return $this->displayView($layout_path);
    } // renderLayout
    
    /**
    * Shortcut method for printing text and setting auto_render option
    *
    * @param string $text Text that need to be rendered
    * @param boolean $render_layout Render controller layout. Default is false for
    *   simple and fast text rendering
    * @return null
    */
    function renderText($text, $render_layout = false) {
      $this->setAutoRender(false); // Turn off auto render because we will render whole thing now...
      
      if($render_layout) {
        $this->renderLayout($this->getLayoutPath(), $text);
      } else {
        print $text;
      } // if
    } // renderText
    
    // ---------------------------------------------------
    //  Redirection related methods
    // ---------------------------------------------------
    
    /**
    * Redirect. Params are same as get_url function
    *
    * @param string $controller
    * @param string $action
    * @param array $params
    * @param string $anchor
    * @return null
    */
    function redirectTo($controller = DEFAULT_CONTROLLER, $action = DEFAULT_ACTION, $params = null, $anchor = null) {
      redirect_to(get_url($controller, $action, $params, $anchor));
    } // redirectTo
    
    /**
    * Redirect to URL
    *
    * @param string $url
    * @return null
    */
    function redirectToUrl($url) {
      redirect_to($url);
    } // redirectToUrl
    
    /**
    * Redirect to referer. If referer is no valid this function will use $alternative URL
    *
    * @param string $alternative Alternative URL
    * @return null
    */
    function redirectToReferer($alternative) {
      redirect_to_referer($alternative);
    } // redirectToReferer
    
    // ---------------------------------------------------
    //  Utils
    // ---------------------------------------------------
    
    /**
    * Return path of the template. If template dnx throw exception
    *
    * @param void
    * @return string
    */
    function getViewPath() {
      $view_value = $this->getView();
      if(is_array($view_value)) {
        $controller_name = array_var($view_value, 0, Angie::engine()->getDefaultControllerName());
        $view_name = array_var($view_value, 1, Angie::engine()->getDefaultActionName());
      } else {
        $controller_name = $this->getControllerName();
        $view_name = trim($view_value) == '' ? Angie::engine()->getDefaultActionName() : $view_value;
      } // if
      
      return Angie::engine()->getViewPath($view_name, $controller_name);
    } // getTemplatePath
    
    /**
    * Return path of the layout file. File dnx throw exception
    *
    * @param void
    * @return string
    */
    function getLayoutPath() {
      $layout_name = trim($this->getLayout()) == '' ? 
        $this->getControllerName() : 
        $this->getLayout();
      
      return Angie::engine()->getLayoutPath($layout_name);
    } // getLayoutPath
    
    // -------------------------------------------------------
    // Getters and setters
    // -------------------------------------------------------
    
    /**
    * Get view
    *
    * @param null
    * @return string
    */
    function getView() {
      return $this->view;
    } // getView
    
    /**
    * Set view value
    *
    * @param string $value
    * @return null
    */
    function setView($value) {
      $this->view = $value;
    } // setView
    
    /**
    * Get layout
    *
    * @param null
    * @return string
    */
    function getLayout() {
      return $this->layout;
    } // getLayout
    
    /**
    * Set layout value
    *
    * @param string $value
    * @return null
    */
    function setLayout($value) {
      $this->layout = $value;
    } // setLayout
    
    /**
    * Return helper / helpers array
    *
    * @param null
    * @return array
    */
    function getHelpers() {
      return is_array($this->helpers) ? $this->helpers : array($this->helpers);
    } // getHelpers
    
    /**
    * Add one or many helpers
    *
    * @param array of helper names
    * @return boolean
    */
    function addHelper() {
      $args = func_get_args();
      if(!is_array($args)) {
        return false;
      } // if
      
      foreach($args as $helper_name) {
        if(trim($helper_name) == '') {
          continue;
        } // if
        
        if(!in_array($helper_name, $this->helpers) && Angie::engine()->useHelper($helper_name)) {
          $this->helpers[] = $helper_name;
        } // if
      } // foreach
      
      return true;
    } // addAutoLoadHelper
    
    /**
    * Get auto_render
    *
    * @param null
    * @return boolean
    */
    function getAutoRender() {
      return $this->auto_render;
    } // getAutoRender
    
    /**
    * Set auto_render value
    *
    * @param boolean $value
    * @return null
    */
    function setAutoRender($value) {
      $this->auto_render = (boolean) $value;
    } // setAutoRender
  
  } // Angie_Controller_Page

?>