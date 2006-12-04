<?php

  /**
  * Run tests for a specific project
  *
  * @package Angie.bin
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  if(isset($return_help) && $return_help) {
    return 'Run tests for a given project. Second parameter is a valid project path. If it is missing current working directory will be used.';
  } // if

  $project_path = array_var($argv, 2, getcwd());
  if(trim($project_path) == '') {
    $project_path = getcwd();
  } // if
  if(!is_dir($project_path)) {
    die('Please provide project path');
  } // if
  
  $project_test_file = with_slash($project_path) . 'development/scripts/test.php';
  if(!is_file($project_test_file)) {
    die("'$project_path' is not a valid Angie project");
  } // if
  
  require_once $project_test_file;

?>