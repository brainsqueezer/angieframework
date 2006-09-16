<?php

  /**
  * Template engine interface that all engies need to implement
  *
  * @package Angie.template
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  interface Angie_TemplateEngine {
    
    /**
    * Assign variable value to the view
    * 
    * Use this function to assign variable values to the view
    *
    * @param string $variable_name
    * @param mixed $variable_value
    * @return null
    */
    function assignToView($variable_name, $variable_value = null);
    
    /**
    * This function will render view and return it as a string
    *
    * @param string $view_path
    * @return string
    */
    function fetchView($view_path);
    
    /**
    * This function will render view to the output buffer (it can be flushed to the borwser, cached by 
    * the other function etc)
    *
    * @param string $view_path
    * @return boolean
    */
    function displayView($view_path);
    
  } // Angie_TemplateEngine

?>