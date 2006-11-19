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
      $application_name = trim($this->getArgument(0));
      if($application_name == '') {
        $output->printMessage('Please insert application name');
        return;
      } // if
      
      $quiet = $this->getOption('q', 'quiet');
      
      $application_path        = with_slash(Angie::engine()->getApplicationPath($application_name));
      $public_application_path = with_slash(Angie::engine()->getPublicApplicationPath($application_name));
      
      $folders = array(
        $application_path, 
        $application_path . 'controllers',
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
      
      if(!$quiet) {
        $output->printMessage("Application '$application_name' has been created");
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
      return 'Use model description from /dev/model.php and rebuild model classes';
    } // defineDescription
  
  } // Angie_Command_Controller

?>