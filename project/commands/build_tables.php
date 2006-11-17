<?php

  /**
  * Handler that is used for building model classes based on model description
  *
  * @package Angie.project
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Command_BuildTables extends Angie_Console_ExecutableCommand {
  
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
      
      require PROJECT_PATH . '/dev/model.php';
      
      $options = array(
        'force' => (boolean) $this->getOption('force'),
        'quiet' => (boolean) $this->getOption('q', 'quiet'),
      ); // array
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
        array('m:', 'mode:', 'Defines the mode of the execution. When a generator find an existing table it will: in "skip" mode skip it and move to the next table, in "sync" mode it will sync constructions by altering table, in "rebuild" mode it will drop existing table and create a new one based on model description'),
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
      return 'Use model description from /dev/model.php and rebuild database tables';
    } // defineDescription
  
  } // Angie_Command_BuildModel

?>