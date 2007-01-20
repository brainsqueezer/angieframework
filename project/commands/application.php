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
  class Angie_Command_Application extends Angie_Console_GeneratorCommand {
  
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
      
      $force = $this->getOption('force');
      
      $application_path        = with_slash(Angie::engine()->getApplicationPath($application_name));
      $public_application_path = with_slash(Angie::engine()->getPublicApplicationPath($application_name));
      
      $folders = array(
        $application_path, 
        $application_path . 'controllers',
        $application_path . 'helpers',
        $application_path . 'layouts',
        $application_path . 'views',
        Angie::engine()->getViewsFolderPath($application_name, $application_name),
        $public_application_path,
        $public_application_path . 'images',
        $public_application_path . 'scripts',
        $public_application_path . 'stylesheets',
        $public_application_path . 'uploads',
      ); // array
      
      foreach($folders as $folder) {
        if(!$this->createDir($folder, $output)) {
          return;
        } // if
      } // foreach
      
      $app_controller_class     = Angie::engine()->getApplicationControllerClass($application_name);
      $app_controller_file_path = Angie::engine()->getControllerPath($app_controller_class, true, $application_name);
      $app_helper_file_path     = Angie::engine()->getHelperPath($application_name, $application_name);
      $app_layout_file_path     = Angie::engine()->getLayoutPath($application_name, $application_name);
      $app_view_file_path       = Angie::engine()->getViewPath('index', $application_name, $application_name);
      $init_file_path           = Angie::engine()->getApplicationInitfilePath($application_name);
      
      $this->assignToView('application_name', $application_name);
      $this->assignToView('project_name', Angie::getConfig('project.name'));
      $this->assignToView('controller_name', $application_name);
      $this->assignToView('controller_class_name', $app_controller_class);
      
      $this->createFile($init_file_path, $this->fetchContent('application_templates', 'init'), $output, $force);
      $this->createFile($app_controller_file_path, $this->fetchContent('controller_templates', 'controller'), $output, $force);
      $this->createFile($app_helper_file_path, $this->fetchContent('controller_templates', 'helper'), $output, $force);
      $this->createFile($app_layout_file_path, $this->fetchContent('controller_templates', 'layout'), $output, $force);
      $this->createFile($app_view_file_path, $this->fetchContent('controller_templates', 'view'), $output, $force);
      
//      $this->createFile($init_file_path, $template_engine->fetchView(ANGIE_PATH . '/project/application_templates/init.php'), $output, $quiet, $force);
//      $this->createFile($app_controller_file_path, $template_engine->fetchView(ANGIE_PATH . '/project/application_templates/application_conroller.php'), $output, $quiet, $force);
      
      $output->printMessage("Application '$application_name' has been created");
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