<?php

  /**
  * Template implementation that uses PHP as template engine (uses row PHP files encapsulated inside of a function call - template is 100% isolated of the global scope)
  *
  * @package Angie.template
  * @subpackage engines
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_TemplateEngine_Php implements Angie_TemplateEngine {
  
    /**
    * Array of template variables
    *
    * @var array
    */
    private $vars = array();
    
    /**
    * Assign variable value to the view
    *
    * @param string $variable_name
    * @param mixed $variable_value
    * @return null
    */
    function assignToView($variable_name, $variable_value) {
      if(($trimmed = trim($variable_name)) == '') {
        throw new Angie_Core_Error_InvalidParamValue('name', $variable_name, "Variable name can't be empty");
      } // if
      $this->vars[$trimmed] = $variable_value;
      return true;
    } // assign
    
    /**
    * This function will render view and return it as a string
    *
    * @param string $view_path
    * @return string
    */
    function fetchView($view_path) {
      ob_start();
      try {
        $this->includeTemplate($view_path);
      } catch(Exception $e) {
        ob_end_clean();
        throw $e;
      } // try
      return ob_get_clean();
    } // fetch
    
    /**
    * This function will render view to the output buffer (it can be flushed to the borwser, cached by 
    * the other function etc)
    *
    * @param string $view_path
    * @return boolean
    */
    function displayView($view_path) {
      return $this->includeTemplate($view_path);
    } // display
    
    /**
    * Include specific template
    *
    * @param string $template Template name or path relative to templates dir
    * @return null
    */
    function includeTemplate($template) {
      if(file_exists($template)) {
        extract($this->vars, EXTR_SKIP);
        include $template;
        return true;
      } else {
        throw new Angie_FileSystem_Error_FileDnx($template, "Template '$template' doesn't exists");
      } // if
    } // includeTemplate
  
  } // Angie_TemplateEngine_Php

?>