<?php

  /**
  * Run tests for a specific project
  *
  * @package Angie.bin
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */

  if(!isset($argv[2]) || (trim($argv[2]) == '')) {
    die('Please provide project path');
  } // if
  
  $project_test_file = with_slash($argv[2]) . 'dev/scripts/test.php';
  if(!is_file($project_test_file)) {
    die("'$argv[2]' is not a valid Angie project");
  } // if
  
  require_once $project_test_file;

?>