<?php

  /**
  * Generator console command
  *
  * @package Angie.toys
  * @subpackage console
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Console_GeneratorCommand extends Angie_Console_ExecutableCommand implements Angie_TemplateEngine {
    
    /**
    * Template engine instance
    *
    * @var Angie_TemplateEngine
    */
    private $template_engine;
    
    /**
    * Construct command object
    *
    * @param void
    * @return Angie_Console_GeneratorCommand
    */
    function __construct() {
      $this->template_engine = new Angie_TemplateEngine_Php();
    } // __construct
  
    /**
    * Generate a file
    * 
    * This function will try to generate file at $target_path and put $content 
    * in it. If file already exists content will be written only if $force is 
    * set to true.
    * 
    * Progress is written to the output object. If you don't want it printed use 
    * silent output
    *
    * @param string $target_path
    * @param string $content
    * @param Angie_Output $output
    * @param boolean $force
    * @return boolean
    */
    function createFile($target_path, $content, Angie_Output $output, $force = false) {
      if(file_exists($target_path)) {
        if($force) {
          if(file_put_contents($target_path, $content)) {
            $output->printMessage("File '" . substr($target_path, strlen(ROOT_PATH)) . "' already exist. Overwrite.");
            return true;
          } // if
        } else {
          $output->printMessage("File '" . substr($target_path, strlen(ROOT_PATH)) . "' already exist. Skip.");
        } // if
      } else {
        if(file_put_contents($target_path, $content)) {
          $output->printMessage("File '" . substr($target_path, strlen(ROOT_PATH)) . "' created.");
          return true;
        } // if
      } // if
      return false;
    } // createFile
    
    /**
    * Create directory in a $target_path
    *
    * @param string $target_path
    * @param Angie_Output $output
    * @return boolean
    */
    function createDir($target_path, Angie_Output $output) {
      if(is_dir($target_path)) {
        $output->printMessage('Directory "' . substr($target_path, strlen(ROOT_PATH)) . '" already exist. Skip.');
        return true;
      } else {
        if(mkdir($target_path)) {
          $output->printMessage('Directory "' . substr($target_path, strlen(ROOT_PATH)) . '" created.');
          return true;
        } else {
          $output->printMessage('Failed to create "' . substr($target_path, strlen(ROOT_PATH)) . '" directory.');
          return false;
        } // if
      } // if
    } // createDir
    
    /**
    * Return path of a specific view
    * 
    * This function assumes that view are located inside of angie/project 
    * folder
    *
    * @param string $view_set
    * @param string $view_name
    * @return string
    */
    function getViewPath($view_set, $view_name) {
      return ANGIE_PATH . "/project/$view_set/$view_name.php";
    } // getViewPath
    
    /**
    * Fetch specific template and return its content as a string
    *
    * @param string $view_set
    * @param string $view_name
    * @return string
    */
    function fetchContent($view_set, $view_name) {
      return $this->fetchView($this->getViewPath($view_set, $view_name));
    } // fetchContent
    
    // ---------------------------------------------------
    //  Template engine implementation
    // ---------------------------------------------------
    
    /**
    * Assign variable value to the view
    * 
    * Use this function to assign variable values to the view
    *
    * @param string $variable_name
    * @param mixed $variable_value
    * @return null
    */
    function assignToView($variable_name, $variable_value = null) {
      return $this->template_engine->assignToView($variable_name, $variable_value);
    } // assignToView
    
    /**
    * This function will render view and return it as a string
    *
    * @param string $view_path
    * @return string
    */
    function fetchView($view_path) {
      return $this->template_engine->fetchView($view_path);
    } // fetchView
    
    /**
    * This function will render view to the output buffer (it can be flushed to the borwser, cached by 
    * the other function etc)
    *
    * @param string $view_path
    * @return boolean
    */
    function displayView($view_path) {
      return $this->template_engine->displayView($view_path);
    } // displayView
  
  } // Angie_Console_GeneratorCommand

?>