<?php

  /**
  * Controller generator
  * 
  * Generate a controller in a specific application. This generator will 
  * generate controller class, empty layout and a folder for views
  *
  * @package Angie.project
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Command_Controller extends Angie_Console_GeneratorCommand {
  
    /**
    * Execute the command
    * 
    * Trigger this function to exeucte the command based on the input arguments
    *
    * @param Angie_Output $output
    * @return null
    */
    function execute(Angie_Output $output) {
      $controller_name = trim(strtolower($this->getArgument(0)));
      if($controller_name == '') {
        $output->printMessage('Please insert controller name');
        return;
      } // if
      
      $application_name = trim($this->getArgument(1));
      if($application_name == '') {
        $output->printMessage('Please insert application name');
        return;
      } // if
      
      if(!Angie::engine()->applicationExists($application_name)) {
        $output->printMessage("Application '$application_name' does not exist");
        return;
      } // if
      
      $quiet = $this->getOption('q', 'quiet');
      $force = $this->getOption('force');
      
      $app_controller_class  = Angie::engine()->getApplicationControllerClass($application_name);
      $controller_class_name = Angie::engine()->getControllerClass($controller_name);
      $controller_file_path  = Angie::engine()->getControllerPath($controller_class_name, true, $application_name);
      $helper_file_path      = Angie::engine()->getHelperPath($controller_name, $application_name);
      $layout_file_path      = Angie::engine()->getLayoutPath($controller_name, $application_name);
      $views_folder_path     = Angie::engine()->getViewsFolderPath($controller_name, $application_name);
      $view_file_path        = Angie::engine()->getViewPath('index', $controller_name, $application_name);
      
      if(!$this->createDir($views_folder_path, $output)) {
        return false;
      } // if
      
      $this->assignToView('app_controller_class', $app_controller_class);
      $this->assignToView('controller_name', $controller_name);
      $this->assignToView('controller_class_name', $controller_class_name);
      $this->assignToView('application_name', $application_name);
      $this->assignToView('project_name', Angie::getConfig('project.name'));
      
      if(!$this->createFile($controller_file_path, $this->fetchContent('controller_templates', 'controller'), $output, $force)) {
        return;
      } // if
      if(!$this->createFile($helper_file_path, $this->fetchContent('controller_templates', 'helper'), $output, $force)) {
        return false;
      } // if
      if(!$this->createFile($layout_file_path, $this->fetchContent('controller_templates', 'layout'), $output, $force)) {
        return false;
      } // if
      if(!$this->createFile($view_file_path, $this->fetchContent('controller_templates', 'view'), $output, $force)) {
        return false;
      } // if
      
      $output->printMessage("Controller '$controller_name' created");
    } // execute
    
    /**
    * Return options definition array
    * 
    * Single element in options definition array consists of three elements. 
    * First element is a short option (one letter plus optional colon saying 
    * that this option requires an argument), long option name with option colon 
    * and help
    *
    * @param void
    * @return array
    */
    function defineOptions() {
      return array(
        array('', 'force', 'Force the creation of controller; rewrite all existing files'),
        array('q', 'quiet', 'Don\'t print progress messages to the console'),
        array('h', 'help', 'Show help')
      ); // array
    } // defineOptions
    
    /**
    * Return command description
    *
    * @param void
    * @return string
    */
    function defineDescription() {
      return 'Create a controller and related files and folder';
    } // defineDescription
  
  } // Angie_Command_Controller

?>