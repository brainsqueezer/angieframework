<?php

  /**
  * Handler that is used for building model classes based on model description
  *
  * @package Angie.project
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Command_BuildModel extends Angie_Console_ExecutableCommand {
  
    /**
    * Execute the command
    * 
    * Trigger this function to exeucte the command based on the input arguments
    *
    * @param Angie_Output $output
    * @return null
    */
    function execute(Angie_Output $output) {
      Angie_DBA_Generator::cleanUp();
      Angie_DBA_Generator::assignToView('project_name', Angie::getConfig('project.name'));
      
      require Angie::engine()->getDevelopmentPath('model.php');
      
      $output_dir = Angie::engine()->getProjectPath('models');
      $force = (boolean) $this->getOption('force');
      
      // Check output directory
      if(!is_dir($output_dir)) {
        throw new Angie_FileSystem_Error_DirDnx($output_dir);
      } // if
      
      if(!folder_is_writable($output_dir)) {
        throw new Angie_FileSystem_Error_DirNotWritable($output_dir);
      } // if
      
      $output->printMessage('Output directory exists and is writable', 'ok');
      
      // Loop through entities
      if(is_foreachable(Angie_DBA_Generator::getEntities())) {
        foreach(Angie_DBA_Generator::getEntities() as $entity) {
          Angie_DBA_Generator::assignToView('entity', $entity);
          
          $entity_output_dir = with_slash($output_dir) . $entity->getOutputDir();
          
          if(is_dir($entity_output_dir)) {
            $output->printMessage("Directory '" . get_path_relative_to($entity_output_dir, ROOT_PATH) . "' exists. Continue.");
          } else {
            if(mkdir($entity_output_dir)) {
              $output->printMessage("Directory '" . get_path_relative_to($entity_output_dir, ROOT_PATH) . "' created");
            } else {
              throw new Angie_FileSystem_Error_DirNotWritable($output_dir);
            } // if
          } // if
          
          $base_entity_output_dir = with_slash($entity_output_dir) . 'base';
          if(is_dir($base_entity_output_dir)) {
            $output->printMessage("Directory '" . get_path_relative_to($base_entity_output_dir, ROOT_PATH) . "' exists. Continue.");
          } else {
            if(mkdir($base_entity_output_dir)) {
              $output->printMessage("Directory '" . get_path_relative_to($base_entity_output_dir, ROOT_PATH) . "' created");
            } else {
              throw new Angie_FileSystem_Error_DirNotWritable($base_entity_output_dir);
            } // if
          } // if
          
          $base_object_file  = $entity_output_dir . '/base/' . $entity->getBaseObjectClassName() . '.class.php';
          $base_manager_file = $entity_output_dir . '/base/' . $entity->getBaseManagerClassName() . '.class.php';
          $object_file       = $entity_output_dir . '/' . $entity->getObjectClassName() . '.class.php';
          $manager_file      = $entity_output_dir . '/' .$entity->getManagerClassName() . '.class.php';
          
          // Base object file...
          $relative_path = get_path_relative_to($base_object_file, ROOT_PATH);
          if($entity->writeBaseObjectClass($base_object_file)) {
            $message = "File '$relative_path' generated";
          } else {
            $message = "Failed to generate '$relative_path' file";
          } // if
          $output->printMessage($message);
          
          // Base manager class...
          $relative_path = get_path_relative_to($base_manager_file, ROOT_PATH);
          if($entity->writeBaseManagerClass($base_manager_file)) {
            $message = "File '$relative_path' generated";
          } else {
            $message = "Failed to generate '$relative_path' file";
          } // if
          $output->printMessage($message);
          
          // Object file
          $object_file_exists = file_exists($object_file);
          $relative_path = get_path_relative_to($object_file, ROOT_PATH);
          if(!$object_file_exists || $force) {
            if($entity->writeObjectClass($object_file)) {
              $message = "File '$relative_path' generated";
            } else {
              $message = "Failed to generate '$relative_path' file";
            } // if
          } else {
            $message = "File '$relative_path' already exists. Skipping";
          } // if
          $output->printMessage($message);
          
          // Manager file
          $manager_file_exists = file_exists($manager_file);
          $relative_path = get_path_relative_to($manager_file, ROOT_PATH);
          if(!$manager_file_exists || $force) {
            if($entity->writeManagerClass($manager_file)) {
              $message = "File '$relative_path' generated";
            } else {
              $message = "Failed to generate '$relative_path' file";
            } // if
          } else {
            $message = "File '$relative_path' already exists. Skipping";
          } // if
          $output->printMessage($message);
          
          $test_file = Angie::engine()->getDevelopmentPath('tests/unit/' . $entity->getTestClassName() . '.class.php');
          $test_file_exists = file_exists($test_file);
          $relative_path = get_path_relative_to($test_file, ROOT_PATH);
          if(!$test_file_exists || $force) {
            if(file_put_contents($test_file, Angie_DBA_Generator::fetchView(ANGIE_PATH . '/project/build_model/test.php', true))) {
              $message = "File '$relative_path' generated";
            } else {
              $message = "Failed to generate '$relative_path' file";
            } // if
          } else {
            $message = "File '$relative_path' already exists. Skipping";
          } // if
          $output->printMessage($message);
          
          $fixtures_file = Angie::engine()->getDevelopmentPath('tests/fixtures/' . $entity->getFixturesName() . '.ini');
          $fixtures_file_exists = file_exists($fixtures_file);
          $relative_path = get_path_relative_to($fixtures_file, ROOT_PATH);
          if(!$fixtures_file_exists || $force) {
            if(file_put_contents($fixtures_file, Angie_DBA_Generator::fetchView(ANGIE_PATH . '/project/build_model/fixtures.php', true))) {
              $message = "File '$relative_path' generated";
            } else {
              $message = "Failed to generate '$relative_path' file";
            } // if
          } else {
            $message = "File '$relative_path' already exists. Skipping";
          } // if
          $output->printMessage($message);
          
        } // foreach
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
        array('', 'force', 'Overwrite all files'),
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
      return 'Use model description rebuild model classes';
    } // defineDescription
  
  } // Angie_Command_BuildModel

?>