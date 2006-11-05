<?php

  /**
  * Start Angie project tool
  *
  * @package Angie.bin
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */

  if(!isset($argv[2])) {
    die('Please provide project path');
  } // if
  
  $project_console_file = with_slash($argv[2]) . 'dev/scripts/console.php';
  if(!is_file($project_console_file)) {
    die("'$argv[2]' is not a valid Angie project");
  } // if
  
  require_once $project_console_file;

?>