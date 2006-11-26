<?php

  /**
  * Controller generator
  * 
  * Generate a controller in a specific application. This generator will generate controller class, empty layout and a 
  * folder for views
  *
  * @package Angie.project
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Command_Controller extends Angie_Console_ExecutableCommand {
  
    /**
    * Execute the command
    * 
    * Trigger this function to exeucte the command based on the input arguments
    *
    * @param Angie_Output $output
    * @return null
    */
    function execute(Angie_Output $output) {
      $controller_name = trim($this->getArgument(0));
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
      
      $controller_class_name = Angie::engine()->getControllerClass($controller_name);
      $controller_file_path = Angie::engine()->getControllerPath($controller_class_name, true, $application_name);
      $layout_file_path = Angie::engine()->getLayoutPath($controller_name, $application_name);
      $views_folder_path = Angie::engine()->getViewsFolderPath($controller_name, $application_name);
      
      if(is_dir($views_folder_path)) {
        if(!$quiet) {
          $output->printMessage("Folder '" . substr($views_folder_path, strlen(ROOT_PATH)) . "' already exists. Skip.");
        } // if
      } else {
        if(mkdir($views_folder_path)) {
          if(!$quiet) {
            $output->printMessage("Folder '" . substr($views_folder_path, strlen(ROOT_PATH)) . "' created.");
          } // if
        } else {
          $output->printMessage("Failed to create '" . substr($views_folder_path, strlen(ROOT_PATH)) . "' folder.");
          return;
        } // if
      } // if
      
      $template_engine = new Angie_TemplateEngine_Php();
      $template_engine->assignToView('controller_name', $controller_name);
      $template_engine->assignToView('controller_class_name', $controller_class_name);
      $template_engine->assignToView('application_name', $application_name);
      $template_engine->assignToView('project_name', Angie::getConfig('project.name'));
      
      // Create a controller file
      if(file_exists($controller_file_path)) {
        if($force) {
          file_put_contents($controller_file_path, $template_engine->fetchView(ANGIE_PATH . '/project/controller_templates/controller.php'));
          if(!$quiet) {
            $output->printMessage("File '" . substr($controller_file_path, strlen(ROOT_PATH)) . "' already exist. Overwrite.");
          } // if
        } else {
          if(!$quiet) {
            $output->printMessage("File '" . substr($controller_file_path, strlen(ROOT_PATH)) . "' already exist. Skip.");
          } // if
        } // if
      } else {
        file_put_contents($controller_file_path, $template_engine->fetchView(ANGIE_PATH . '/project/controller_templates/controller.php'));
        if(!$quiet) {
          $output->printMessage("File '" . substr($controller_file_path, strlen(ROOT_PATH)) . "' created.");
        } // if
      } // if
      
      // Create a layout file
      if(file_exists($layout_file_path)) {
        if($force) {
          file_put_contents($layout_file_path, $template_engine->fetchView(ANGIE_PATH . '/project/controller_templates/layout.php'));
          if(!$quiet) {
            $output->printMessage("File '" . substr($layout_file_path, strlen(ROOT_PATH)) . "' already exist. Overwrite.");
          } // if
        } else {
          if(!$quiet) {
            $output->printMessage("File '" . substr($layout_file_path, strlen(ROOT_PATH)) . "' already exist. Skip.");
          } // if
        } // if
      } else {
        file_put_contents($layout_file_path, $template_engine->fetchView(ANGIE_PATH . '/project/controller_templates/layout.php'));
        if(!$quiet) {
          $output->printMessage("File '" . substr($layout_file_path, strlen(ROOT_PATH)) . "' created.");
        } // if
      } // if
      
      if(!$quiet) {
        $output->printMessage("Controller '$controller_name' created");
      } // if
    } // execute
    
    /**
    * Return options definition array
    * 
    * Single element in options definition array consists of three elements. First element is a short option (one letter 
    * plus optional colon saying that this option requires an argument), long option name with option colon and help
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
      return 'Create a specific controller and related files and folder in a specified application';
    } // defineDescription
  
  } // Angie_Command_Controller

?>