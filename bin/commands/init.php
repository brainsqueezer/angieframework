<?php

  /**
  * Start Angie project tool
  *
  * @package Angie.bin
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  if(isset($return_help) && $return_help) {
    return 'Initialize project level tool. This tool automates many project level tasks. Second parameter is path of a valid Angie project. If it is missing current working directory is used.';
  } // if

  $project_path = array_var($argv, 2, getcwd());
  if(!is_dir($project_path)) {
    die("Please provide project path\n");
  } // if
  
  $project_console_file = with_slash($project_path) . 'development/scripts/console.php';
  if(!is_file($project_console_file)) {
    die("'$project_path' is not a valid Angie project\n");
  } // if
  
  require_once $project_console_file;

?>