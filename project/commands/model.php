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
  class Angie_Command_Model extends Angie_Console_GeneratorCommand {
  
    /**
    * Execute the command
    * 
    * Trigger this function to exeucte the command based on the input arguments
    *
    * @param Angie_Output $output
    * @return null
    */
    function execute(Angie_Output $output) {
      $model_name = trim(strtolower($this->getArgument(0)));
      if($model_name == '') {
        $output->printMessage('Model name is required');
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
      
      $force = $this->getOption('force');
      
      $model_dir         = Angie::engine()->getProjectPath("models/$model_name");
      $record_class_name = Angie_Inflector::camelize($model_name);
      $record_class_file = "$model_dir/$record_class_name.class.php";
      $table_name        = Angie_Inflector::pluralize($model_name);
      $table_class_name  = Angie_Inflector::camelize($table_name);
      $table_class_file  = "$model_dir/$table_class_name.class.php";
      
      $this->assignToView('application_name', $application_name);
      $this->assignToView('model_name', $model_name);
      $this->assignToView('record_class', $record_class_name);
      $this->assignToView('table_name', $table_name);
      $this->assignToView('table_class', $table_class_name);
      
      if(!$this->createDir($model_dir, $output)) {
        return;
      } // if
      if(!$this->createFile($record_class_file, $this->fetchContent('model_templates', 'record'), $output, $force)) {
        return;
      } // if
      if(!$this->createFile($table_class_file, $this->fetchContent('model_templates', 'table'), $output, $force)) {
        return;
      } // if
      
      $output->printMessage("Model $model_name created");
      return;
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
        array('', 'force', 'Force the creation of model files; rewrite all existing files'),
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
      return 'Create model classes';
    } // defineDescription
    
  } // Angie_Command_Model

?>