<?php

  /**
  * Application generator
  * 
  * Generate a construction for an application in a selected project
  *
  * @package Angie.project
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Command_Application extends Angie_Console_ExecutableCommand {
  
    /**
    * Execute the command
    * 
    * Trigger this function to exeucte the command based on the input arguments
    *
    * @param Angie_Output $output
    * @return null
    */
    function execute(Angie_Output $output) {
      $application_name = trim($this->getArgument(0));
      if($application_name == '') {
        $output->printMessage('Please insert application name');
        return;
      } // if
      
      $quiet = $this->getOption('q', 'quiet');
      $force = $this->getOption('force');
      
      $application_path        = with_slash(Angie::engine()->getApplicationPath($application_name));
      $public_application_path = with_slash(Angie::engine()->getPublicApplicationPath($application_name));
      
      $folders = array(
        $application_path, 
        $application_path . 'controllers',
        $application_path . 'helpers',
        $application_path . 'layouts',
        $application_path . 'views',
        $public_application_path,
        $public_application_path . 'images',
        $public_application_path . 'scripts',
        $public_application_path . 'stylesheets',
        $public_application_path . 'uploads',
      ); // array
      
      foreach($folders as $folder) {
        if(is_dir($folder)) {
          if(!$quiet) {
            $output->printMessage("Folder '" . substr($folder, strlen(ROOT_PATH)) . "' already exists. Skip.");
          } // if
        } else {
          if(mkdir($folder)) {
            if(!$quiet) {
              $output->printMessage("Folder '" . substr($folder, strlen(ROOT_PATH)) . "' has been created.");
            } // if
          } else {
            $output->printMessage("Failed to create '" . substr($folder, strlen(ROOT_PATH)) . "' folder.");
          } // if
        } // if
      } // foreach
      
      $app_controller_class     = Angie::engine()->getApplicationControllerClass($application_name);
      $app_controller_file_path = Angie::engine()->getControllerPath($app_controller_class, true, $application_name);
      $init_file_path           = Angie::engine()->getApplicationInitfilePath($application_name);
      
      $template_engine = new Angie_TemplateEngine_Php();
      $template_engine->assignToView('application_name', $application_name);
      $template_engine->assignToView('project_name', Angie::getConfig('project.name'));
      $template_engine->assignToView('app_controller_class', $app_controller_class);
      
      $this->createFile($init_file_path, $template_engine->fetchView(ANGIE_PATH . '/project/application_templates/init.php'), $output, $quiet, $force);
      $this->createFile($app_controller_file_path, $template_engine->fetchView(ANGIE_PATH . '/project/application_templates/application_conroller.php'), $output, $quiet, $force);
      
      if(!$quiet) {
        $output->printMessage("Application '$application_name' has been created");
      } // if
    } // execute
    
    /**
    * Create a specific file based on template
    *
    * @param string $target_path
    * @param string $content
    * @param Angie_Output $output
    * @param boolean $quiet
    * @param boolean $force
    * @return null
    */
    private function createFile($target_path, $content, Angie_Output $output, $quiet = false, $force = false) {
      if(file_exists($target_path)) {
        if($force) {
          file_put_contents($target_path, $content);
          if(!$quiet) {
            $output->printMessage("File '" . substr($target_path, strlen(ROOT_PATH)) . "' already exist. Overwrite.");
          } // if
        } else {
          if(!$quiet) {
            $output->printMessage("File '" . substr($target_path, strlen(ROOT_PATH)) . "' already exist. Skip.");
          } // if
        } // if
      } else {
        file_put_contents($target_path, $content);
        if(!$quiet) {
          $output->printMessage("File '" . substr($target_path, strlen(ROOT_PATH)) . "' created.");
        } // if
      } // if
    } // createFile
    
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
        array('',  'force', 'Force file creation (overwrite files if present)'),
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
      return 'Create a new application inside of a selected project';
    } // defineDescription
  
  } // Angie_Command_Application

?>