<?php

  /**
  * Angie console input file
  * 
  * Top level commands for angie CLI utility are placed in /bin/commands folder. This commands can have subcommands that 
  * are based on a specific subcommand implementation.
  *
  * @package Angie.bin
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  session_start();
  
  define('ANGIE_BIN_PATH', dirname(__FILE__));
  require_once realpath(ANGIE_BIN_PATH . '/../init.php');
  
  if(!class_exists('Angie_Console')) {
    require ANGIE_PATH . '/toys/console/Angie_Console.class.php';
    require ANGIE_PATH . '/toys/console/Angie_Console_Command.class.php';
    require ANGIE_PATH . '/toys/console/Angie_Console_ExecutableCommand.class.php';
    require ANGIE_PATH . '/toys/console/Angie_Console_GeneratorCommand.class.php';
    require ANGIE_PATH . '/toys/output/Angie_Output.class.php';
    require ANGIE_PATH . '/toys/output/Angie_Output_Console.class.php';
    require ANGIE_PATH . '/toys/output/Angie_Output_Silent.class.php';
  } // if
  
  $subcommand = array_var($argv, 1);
  if(trim($subcommand) == '') {
    die("Subcommand is missing\n");
  } // if
  
  if($subcommand == '-h' || $subcommand == '--help') {
    $subcommand = 'help';
  } // if
  
  $subcommand_file = ANGIE_BIN_PATH . '/commands/' . $subcommand . '.php';
  if(!is_readable($subcommand_file)) {
    die("Subcommand '$subcommand' is not recognized\n");
  } // if
  
  require_once $subcommand_file;

?>