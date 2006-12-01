<?php

  /**
  * Build tables command
  * 
  * Handler that is used for building model classes based on model description
  *
  * @package Angie.project
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Command_BuildTables extends Angie_Console_ExecutableCommand {
    
    const MODE_SKIP = 'skip';
    const MODE_SYNC = 'sync';
    const MODE_REBUILD = 'rebuild';
  
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
      
      $valid_modes = array(
        self::MODE_SKIP, 
        self::MODE_SYNC, 
        self::MODE_REBUILD
      ); // array
      
      require DEVELOPMENT_PATH . '/model.php';
      
      $quiet = (boolean) $this->getOption('q', 'quiet');
      $mode = $this->getOption('m', 'mode');
      if($mode && !in_array($mode, $valid_modes)) {
        $output->printMessage('Value of mode argument is not valid. Valid values are: ' . implode(', ', $valid_modes));
        return;
      } // if
      
      $connection = Angie_DB::getConnection();
      $table_prefix = Angie::getConfig('db.table_prefix', '');
      
      $tables = Angie_DBA_Generator::getTables($connection, $table_prefix);
      if(!is_foreachable($tables)) {
        if(!$quiet) {
          $output->printMessage('There are no tables in the current model');
        } // if
        return;
      } // if
      
      $database_tables = $connection->listTables(); // load list of tables in the database...
      
      foreach($tables as $table) {
        $prefixed_table_name = $table->getPrefixedName();
        if(in_array($prefixed_table_name, $database_tables)) {
          if($mode == self::MODE_SKIP) {
            if(!$quiet) {
              $output->printMessage('Table "' . $table->getName() . '" exsist. Skip.');
            } // if
            continue;
          } elseif($mode == self::MODE_SYNC) {
            if(!$quiet) {
              $output->printMessage('Table "' . $table->getName() . '" exsist. Syncing.');
            } // if
            $connection->syncTable($table, $table_prefix);
          } else {
            if(!$quiet) {
              $output->printMessage('Table "' . $table->getName() . '" exsist. Rebuilding.');
            } // if
            $connection->dropTable($prefixed_table_name, true);
            $connection->buildTable($table, $table_prefix);
          } // if
        } else {
          if(!$quiet) {
            $output->printMessage('Building table "' . $table->getName() . '"');
          } // if
          $connection->buildTable($table, $table_prefix);
        } // if
      } // foreach
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
      return 'Use model description and rebuild database tables';
    } // defineDescription
  
  } // Angie_Command_BuildModel

?>