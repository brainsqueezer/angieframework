<?php

  /**
  * Page controller
  * 
  * Angie_Controller is simple class that lets you execute an action from a controller
  * and protect certain system methods so they can't be executed as actions. In some
  * cases that behaviour is enough, but if we want rapid development we need to add
  * more "magic".
  * 
  * Page controller has some extra toy implemented that automate most of the tasks and
  * make your actions much cleaner.
  * 
  * <b>Automaticly map layouts and views</b>
  * 
  * First method to keep you happy is to automatically map layouts and views based on used 
  * controller and action if you didn't provide any exit in action (for instance, you 
  * forwarded to other action or redirected to some URL).
  * 
  * There is a simple convention that is used to determine where layout and view
  * files are:
  * <pre>
  * layout: /application/layouts/{controller_name}.php
  * view: /application/views/{controller_name}/{action_name}.php
  * </pre>
  * 
  * Example action and controller:
  * <pre>
  * controller: task
  * action: view
  * </pre>
  * 
  * will map to:
  * 
  * <pre>
  * layout: /application/layouts/task.php
  * view: /application/views/task/view.php
  * </pre>
  * 
  * <b>Rendering methods</b>
  * 
  * The most important method for rendering is <b>render()</b> method. All of its params are optional.
  * Easies way to describe behaviour of render() method is to provide examples:
  * <pre>
  * // Assumptions:
  * // Controller name: task
  * // Action name: action
  * 
  * // It this case default controller and action name will be used based on convention described
  * // above. Result:
  * //
  * // layout used: /application/layouts/task.php
  * // view used: /application/views/task/action.php
  * function action() {
  *   $this->render();
  * }
  * 
  * // Controller will use convention, but instead of controller and action name it will use view
  * // and layout name provided as params. Result:
  * //
  * // layout used: /application/layouts/other_layout.php
  * // view used: /application/views/task/other_view.php
  * function action() {
  *   $this->render('other_view', 'other_layout');
  * }
  * 
  * // Controller will use convention, but instead of controller and action name it will use view
  * // and layout name provided as class properties. Result:
  * //
  * // layout used: /application/layouts/other_layout.php
  * // view used: /application/views/task/other_view.php
  * function action() {
  *   $this->setView('other_view');
  *   $this->setLayout('other_layout');
  *   $this->render();
  * }
  * 
  * // Controller will use absolute paths of views and layouts. Result:
  * //
  * // layout used: /path/to/layout
  * // view used: /path/to/view
  * function action() {
  *   $this->render('/path/to/view', '/path/to/layout');
  * }
  * 
  * // Controller will use absolute paths of views and layouts provided as class properties. Result:
  * //
  * // layout used: /path/to/layout
  * // view used: /path/to/view
  * function action() {
  *   $this->setView('/path/to/view');
  *   $this->setLayout('/path/to/layout');
  *   $this->render();
  * }
  * </pre>
  * 
  * In most cases you will let Angie_PageController to automaticly render view if you don't provide any 
  * exit in your action and you leave $auto_render property set to TRUE (TRUE by default). You can let 
  * the controller class automaticly map view based on action name and layout based on controller name 
  * or you can use setLayout() and setView() setters to set specific values.
  * 
  * Implementation is pretty simple: if no exit is proved in action controller will automaticly call 
  * render() method without arguments (see examples above).
  * 
  * If you wish to render simple text you can use <b>renderText()</b> method:
  * <pre>
  * $this->renderText('This is text', true, true);
  * </pre>
  * 
  * Use additional params to say if you want to use template and die when template is rendered.
  * 
  * <b>Template engine interface implementation</b>
  * 
  * Page controller has template engine interface implemented that will automaticly use template
  * engine provided by the framework. You just need to use assignToView(), fetchView() and 
  * displayView() methods. Example:
  * <pre>
  * function action() {
  *   $this->assignToView('variable', $this->fetchView('/path/to/sidebar'));
  *   $this->displayView('/path/to/page');
  * }
  * </pre>
  * 
  * <b>Redirecting</b>
  * 
  * Page controller has some nice methods that lets you redirect user. This methos are:
  * 
  * 1. redirectTo() - this will use default engine implementation to generate URL based on function arguments
  *    and it will redirect user to that URL.
  * 2. redirectToUrl() - this function will use URL that is provided as an argument and redirect user to it
  * 3. redirectToReferer() - this function will try to get referer and redirect user to it. If referer is not
  *    found function will use alternative URL that is proveded as function argument
  *
  * @package Angie.controller
  * @subpackage controllers
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Controller_Page extends Angie_Controller implements Angie_TemplateEngine {
  
    /**
    * Name of the view
    * 
    * There are four supported values:
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
    * Layout name
    * 
    * There are three possible values:
    * 
    * 1. Name is empty - value that will be used is controller name
    * 2. Layout name - value will be attached to /application/layouts/ path
    * 3. Full path to layout
    *
    * @var string
    */
    private $layout;
    
    /**
    * Array of loaded helpers
    * 
    * Whenever we add a project template to the controller its name is added to this list. Built-in 
    * helpers are automaticly included by the controller and they are not listed in this list
    *
    * @var array
    */
    private $helpers = array();
    
    /**
    * Automaticly render view / layout if action does not provide an exit
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
      
      // Use system set of templates
      $this->addHelper('form', 'format', 'html', 'page', 'pagination');
      
      // Use controller template if exists
      if(Angie::engine()->helperExists($this->getControllerName())) {
        $this->addHelper($this->getControllerName());
      } // if
    } // __construct
    
    /**
    * Execute action
    * 
    * This methods extends default controller behaviour by providing auto render functionality to the 
    * controller - it is able to map layout / template pair based on controller and action name and 
    * automaticly render them
    *
    * @param string $action
    * @return boolean
    */
    function execute($action) {
      try {
        $execution = parent::execute($action);
        if($this->getAutoRender()) {
          if(is_array($execution)) {
            $this->assignToView($execution);
          } // if
          $this->render(); // Auto render?
        } // if
      } catch(Angie_Controller_Error_ActionDnx $e) {
        $view_name = $action;
        $layout_name = $this->getControllerName();
        
        if($this->getAutoRender() && Angie::engine()->viewExists($view_name, $layout_name)) {
          $this->setView($view_name);
          $this->setLayout($layout_name);
          
          $this->render();
        } else {
          throw $e; // rethrow
        } // if
      } catch(Exception $e) {
        throw $e;
      } // try
    } // execute
    
    // ---------------------------------------------------
    //  Template related methods
    // ---------------------------------------------------
    
    /**
    * Assign variable value to the view
    * 
    * $variable_name can be a string with variable name value or an associative array that is assigned as 
    * set of params where key is variable name and value is variable value. If $variable_name is an array
    * $variable_value is ignored.
    *
    * @param mixed $variable_name
    * @param mixed $variable_value
    * @return null
    */
    function assignToView($variable_name, $variable_value = null) {
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
    * Render content of specific view / layout combination
    * 
    * $view can have four possible values:
    * 
    * 1. NULL or empty string. Controller name is the name of this controller and name of the view
    *    is action that is executed
    * 2. View is the name of view file (without the extension). Controller that is used in that 
    *    case is this controller
    * 3. Absolute path of view file
    * 4. Array where fist param is controller name and second is the name the action
    * 
    * If $die is true script will die when rendering is finished. True by default
    *
    * @param mixed $view
    * @param string $layout
    * @param boolean $die Die when rendering is done, true by default
    * @return null
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
    } // render
    
    /**
    * Assign content and render layout
    *
    * @param string $layout_path
    * @param string $content
    * @return boolean
    * @throws FileDnxError if layout does not exists
    */
    function renderLayout($layout_path, $content = null) {
      $this->assignToView('content_for_layout', $content);
      return $this->displayView($layout_path);
    } // renderLayout
    
    /**
    * Shortcut method for printing text and setting auto_render option
    * 
    * When this method is called $text will be rendered and auto render will be turned to off. If
    * $render_layout is TRUE $text will be rendered inside of a layout (layout path is based on
    * conventions that are applied to all other methods).
    * 
    * If $die is true script will die when rendering is done. True by default.
    *
    * @param string $text
    * @param boolean $render_layout
    * @param boolean $die
    * @return null
    */
    function renderText($text, $render_layout = true, $die = true) {
      $this->setAutoRender(false); // Turn off auto render because we will render whole thing now...
      
      if($render_layout) {
        $this->renderLayout($this->getLayoutPath(), $text);
      } else {
        print $text;
      } // if
      
      if($die) {
        die();
      } // if
    } // renderText
    
    // ---------------------------------------------------
    //  Redirection related methods
    // ---------------------------------------------------
    
    /**
    * Generate project level URL and redirect user to generated URL
    * 
    * This function uses getUrlFromArguments() from project engine so its implementation may be different
    * in different projects. By default it converts set of params:
    * 
    * 0 -> controller
    * 1 -> action
    * 2 -> array of params
    * 3 -> anchor
    * 
    * Into:
    * 
    * PROJECT_URL/controller/action/param_name-param_value/param_name-param_value/#anchor
    * 
    * All elements can are optional. If controller and action values are not present default values will 
    * be used. If there is no params and anchor they will be excluded.
    *
    * @param string $controller
    * @param string $action
    * @param array $params
    * @param string $anchor
    * @return null
    */
    function redirectTo($controller = DEFAULT_CONTROLLER, $action = DEFAULT_ACTION, $params = null, $anchor = null) {
      redirect_to(Angie::engine()->getUrlFromArguments(func_get_args()));
    } // redirectTo
    
    /**
    * Redirect to URL
    * 
    * Redirect user to $url
    *
    * @param string $url
    * @return null
    */
    function redirectToUrl($url) {
      redirect_to($url);
    } // redirectToUrl
    
    /**
    * Redirect to referer
    * 
    * This function will try to get the referer URL and redirect user to it. If referer is no valid this 
    * function will use $alternative URL provided as first argument
    *
    * @param string $alternative
    * @return null
    */
    function redirectToReferer($alternative) {
      redirect_to_referer($alternative);
    } // redirectToReferer
    
    // ---------------------------------------------------
    //  Utils
    // ---------------------------------------------------
    
    /**
    * Return view path based on the view property
    * 
    * This function will use conventions to generate path of the view file based on $view and $layout 
    * properties
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
    * Return path of the layout file
    * 
    * This function will try to generate and return layout path based on the conventions and value
    * of $layout property
    *
    * @param void
    * @return string
    */
    function getLayoutPath() {
      $layout_name = trim($this->getLayout()) == '' ? $this->getControllerName() : $this->getLayout();
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
    * Add and include one or many helpers
    * 
    * Use this function to add one or many helpers to this controller. Usage:
    * <pre>
    * // Only one
    * $this->addHelper('widgets');
    * 
    * // Many
    * $this->addHelper('widgets', 'js', 'css', 'global');
    * </pre>
    * 
    * To list all included helpers use getHelpers() method
    *
    * @param void
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