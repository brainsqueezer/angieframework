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
      $input = $this->getArgument(0);
      if(is_array($input)) {
        if(count($input) == 0) {
          $output->printMessage('Model name is required');
          return;
        } // if
      } elseif(trim($input) == '') {
        $output->printMessage('Model name is required');
        return;
      } // if
      
      $force = $this->getOption('force');
      
      $model_names = is_array($input) ? $input : array($input);
      foreach($model_names as $model_name) {
        $model_dir              = Angie::engine()->getProjectPath("models/$model_name");
        $record_class_name      = Angie_Inflector::camelize($model_name);
        $record_class_file      = "$model_dir/$record_class_name.class.php";
        $table_name             = Angie_Inflector::pluralize($model_name);
        $table_class_name       = $record_class_name . 'Table';
        $table_class_file       = "$model_dir/$table_class_name.class.php";
        $fixtures_file          = Angie::engine()->getDevelopmentPath("tests/fixtures/$model_name.ini");
        $unit_test_class_name   = 'Test' . $record_class_name;
        $unit_test_file         = Angie::engine()->getDevelopmentPath("tests/unit/$unit_test_class_name.class.php");
        
        $this->assignToView('project_name', Angie::getConfig('project.name'));
        $this->assignToView('model_name', $model_name);
        $this->assignToView('record_class', $record_class_name);
        $this->assignToView('table_name', $table_name);
        $this->assignToView('table_class', $table_class_name);
        $this->assignToView('unit_test_class', $unit_test_class_name);
        
        if(!$this->createDir($model_dir, $output)) {
          return;
        } // if
        $this->createFile($record_class_file, $this->fetchContent('model_templates', 'record'), $output, $force);
        $this->createFile($table_class_file, $this->fetchContent('model_templates', 'table'), $output, $force);
        $this->createFile($fixtures_file, $this->fetchContent('model_templates', 'fixtures'), $output, $force);
        $this->createFile($unit_test_file, $this->fetchContent('model_templates', 'test'), $output, $force);
        
        $output->printMessage("Model $model_name created");
      } // if
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