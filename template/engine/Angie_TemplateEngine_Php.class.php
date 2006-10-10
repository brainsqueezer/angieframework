<?php

  /**
  * Template implementation that uses PHP as template engine
  * 
  * This template negine uses row PHP files encapsulated inside of a function call - template is 100% isolated of the 
  * global scope. Full support for variable assignement, fetching and display is provided
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
    * Using this function you can assign one or many variables to the view. To assing one variable use:
    * <pre>
    * $template_engine->assignToView('site_url', 'http://www.google.com/');
    * </pre>
    * 
    * To assign many variables at once assign array of variables as first argument (second one is optional):
    * <pre>
    * $template_engine->assignToView(array(
    *   'variable_1' => $value_1,
    *   'variable_2' => $value_2,
    *   'variable_3' => $value_3,
    * ));
    * </pre>
    *
    * @param string $variable_name
    * @param mixed $variable_value
    * @return null
    */
    function assignToView($variable_name, $variable_value = null) {
      if(is_array($variable_name)) {
        foreach($variable_name as $k => $v) {
          $this->assignToView($variable_name, $variable_value);
        } // foreach
        return true;
      } else {
        if(($trimmed = trim($variable_name)) == '') {
          throw new Angie_Core_Error_InvalidParamValue('name', $variable_name, "Variable name can't be empty");
        } // if
        $this->vars[$trimmed] = $variable_value;
        return true;
      } // if
    } // assign
    
    /**
    * Render template and return as string
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
    * This function will directly print template content. Outputed content can be fetched in output buffer or flushed
    * to the browser
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