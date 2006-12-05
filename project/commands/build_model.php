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
      
      require DEVELOPMENT_PATH . '/model.php';
      
      $output_dir = PROJECT_PATH . '/models';
      
      $options = array(
        'force'      => (boolean) $this->getOption('force'),
        'quiet'      => (boolean) $this->getOption('q', 'quiet'),
        'output_dir' => $output_dir,
      ); // array
      
      $quiet = array_var($options, 'quiet');
      $force = array_var($options, 'force');
      
      // Check output directory
      if(!is_dir($output_dir)) {
        throw new Angie_FileSystem_Error_DirDnx($output_dir);
      } // if
      
      if(!folder_is_writable($output_dir)) {
        throw new Angie_FileSystem_Error_DirNotWritable($output_dir);
      } // if
      
      if(!$quiet) {
        $output->printMessage('Output directory exists and is writable', 'ok');
      } // if
      
      // Loop through entities
      if(is_foreachable(Angie_DBA_Generator::getEntities())) {
        foreach(Angie_DBA_Generator::getEntities() as $entity) {
          $entity_output_dir = with_slash($output_dir) . $entity->getOutputDir();
          
          if(is_dir($entity_output_dir)) {
            if(!$quiet) {
              $output->printMessage("Directory '" . get_path_relative_to($entity_output_dir, $output_dir) . "' exists. Continue.");
            } // if
          } else {
            if(mkdir($entity_output_dir)) {
              if(!$quiet) {
                $output->printMessage("Directory '" . get_path_relative_to($entity_output_dir, $output_dir) . "' created");
              } // if
            } else {
              throw new Angie_FileSystem_Error_DirNotWritable(self::$output_dir);
            } // if
          } // if
          
          $entity->generate($output, $entity_output_dir, $options);
          
          $test_file = DEVELOPMENT_PATH . '/tests/unit/' . $entity->getName() . '.php';
          
          
          $fixtures_file = DEVELOPMENT_PATH . '/tests/fixtures/' . $entity->getName() . '.ini';
          
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
    
    /**
    * This will return only part of the path relative to $output_dir
    *
    * @param string $path
    * @return string
    */
    private function relativeToOutput($path, $ouput_dir) {
      return substr($path, strlen($output_dir));
    } // relativeToOutput
  
  } // Angie_Command_BuildModel

?>