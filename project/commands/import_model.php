<?php

  /**
  * Import model command
  * 
  * This handler is used to import model from a given database and build a model 
  * definition based on it.
  *
  * @package Angie.project
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Command_ImportModel extends Angie_Console_ExecutableCommand {
    
    /**
    * Execute import model command
    * 
    * Trigger this function to exeucte the command based on the input arguments
    *
    * @param Angie_Output $output
    * @return null
    */
    function execute(Angie_Output $output) {
      $type   = $this->getOption('type');
      $host   = $this->getOption('host');
      $user   = $this->getOption('username');
      $name   = $this->getOption('name');
      $prefix = $this->getOption('prefix');
      $file   = $this->getOption('file');
      
      if($this->getOption('p')) {
        print 'Insert password: ';
        $pass = trim(fgets(STDIN));
      } else {
        $pass = '';
      } // if
      
      if($type <> 'mysql') {
        throw new Angie_Error('Only MySQL engine is currently supported');
      } // if
      
      $connection = new Angie_DB_MySQL_Connection();
      $connection->connect(array(
        'hostname' => $host,
        'username' => $user,
        'password' => $pass,
        'name'     => $name,
        'persist'  => false,
      )); // Angie_DB_MySQL_Connection
      
      if(empty($file)) {
        $file = DEVELOPMENT_PATH . '/model.php';
      } // if
      
      $all_tables = $connection->describeTables();
      if(is_foreachable($all_tables)) {
        $tables = array();
        $prefix_len = strlen($prefix);
        foreach($all_tables as $table) {
          if(($prefix_len == 0) || str_starts_with($table->getName(), $prefix)) {
            $tables[substr($table->getName(), $prefix_len)] = $table;
          } // if
        } // foreach
      } else {
        $output->printMessage('There are no tables in selected database');
        return;
      } // if
      
      if(count($tables) == 0) {
        $output->printMessage("There are no tables prefixed with '$prefix'");
        return;
      } // if
      
      $template_engine = new Angie_TemplateEngine_Php();
      $template_engine->assignToView('project_name', Angie::getConfig('project.name'));
      $template_engine->assignToView('tables', $tables);
      
      if(file_put_contents($file, $template_engine->fetchView(ANGIE_PATH . '/project/import_model/model.php'))) {
        $output->printMessage("Model has been sucessfully writen into '" . basename($file) . "'");
      } else {
        $output->printMessage("Failed to write model description into '" . basename($file) . "'");
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
        array('', 'type:', 'Type of the database. Currently only MySQL is support'),
        array('', 'host:', 'Database hostname'),
        array('', 'username:', 'Database username'),
        array('', 'name:', 'Database name'),
        array('', 'prefix:', 'Table prefix. Only tables with a given prefix will be imported and it will be removed from entity name'),
        array('', 'file:', 'Path of a target file'),
        array('p', '', 'Ask for password'),
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
      return 'Import model description from a given database';
    } // defineDescription
    
  } // Angie_Command_ImportModel

?>