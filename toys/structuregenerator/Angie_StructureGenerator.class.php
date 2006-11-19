<?php

  /**
  * Structure generator toy
  *
  * @package Angie.toys
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_StructureGenerator implements Angie_TemplateEngine {
    
    /**
    * Template engine used for file generation
    *
    * @var Angie_TemplateEngine_Php
    */
    private $template_engine;
  
    /**
    * Constructor
    *
    * @param string $template_dir
    * @param string $target_dir
    * @return Angie_StructureGenerator
    */
    function __construct() {
      $this->template_engine = new Angie_TemplateEngine_Php();
    } // __construct
    
    /**
    * Copy structure from template to target
    *
    * @param string $template_dir
    * @param string $target_dir
    * @param Angie_Output $output
    * @param string $output_paths_relative_to
    * @return boolean
    * @throws Angie_FileSystem_Error_DirDnx
    * @throws Angie_FileSystem_Error_DirNotWritable
    */
    function copyStructure($template_dir, $target_dir, Angie_Output $output, $output_paths_relative_to = '') {
      if(!is_dir($template_dir)) {
        throw new Angie_FileSystem_Error_DirDnx($template_dir);
      } // if
      
      if(!is_dir($target_dir)) {
        throw new Angie_FileSystem_Error_DirDnx($target_dir);
      } // if
      
      if(!folder_is_writable($target_dir)) {
        throw new Angie_FileSystem_Error_DirNotWritable($target_dir);
      } // if
      
      $d = dir($template_dir);
      
      while(($entry = $d->read()) !== false) {
        if(str_starts_with($entry, '.') && ($entry <> '.htaccess')) {
          continue;
        } // if
        
        $template_path = with_slash($template_dir) . $entry;
        $target_path = with_slash($target_dir) . $entry;
        
        if(is_file($target_path) || is_dir($target_path)) {
          $output->printMessage("'" . substr($target_path, strlen($output_paths_relative_to)) . "' already exists. Skip.");
          if(is_dir($target_path)) {
            $this->copyStructure($template_path, $target_path, $output, $output_paths_relative_to);
          } // if
          continue;
        } // if
        
        if(is_file($template_path)) {
          file_put_contents($target_path, $this->fetchView($template_path));
          $output->printMessage("File '" . substr($target_path, strlen($output_paths_relative_to)) . "' created.");
        } elseif(is_dir($template_path)) {
          mkdir($target_path);
          $output->printMessage("Directory '" . substr($target_path, strlen($output_paths_relative_to)) . "' created.");
          $this->copyStructure($template_path, $target_path, $output, $output_paths_relative_to);
        } // if
      } // while
      
      $d->close();
    } // copyStructure
    
    // ---------------------------------------------------
    //  Template engine interface implementation
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
    * Render content of the template to the output buffer
    * 
    * This function will render view to the output buffer (it can be flushed to the borwser, cached by 
    * the other function etc)
    *
    * @param string $view_path
    * @return boolean
    */
    function displayView($view_path) {
      return $this->template_engine->displayView($view_path);
    } // displayView
  
  } // Angie_StructureGenerator

?>