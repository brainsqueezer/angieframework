<?php

  /**
  * Import initial data
  * 
  * Improt initial data by including initial data file from development folder
  *
  * @package Angie.project
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Command_ImportInitialData extends Angie_Console_ExecutableCommand {
  
    /**
    * Execute the command
    * 
    * Trigger this function to exeucte the command based on the input arguments
    *
    * @param Angie_Output $output
    * @return null
    */
    function execute(Angie_Output $output) {
      $initial_data_file = Angie::engine()->getDevelopmentPath('initial_data.php');
      
      if(is_file($initial_data_file)) {
        require $initial_data_file;
        $output->printMessage("Initial data file found and loaded");
      } else {
        $output->printMessage("Initial data file was not found in development folder");
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
      return 'Import initial data into database';
    } // defineDescription
  
  } // Angie_Command_BuildModel

?>