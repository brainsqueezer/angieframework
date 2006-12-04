<?php

  /**
  * Show help for top set of commands
  *
  * @package Angie.bin
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  $return_help = true;
  
  $commands_folder = dirname(__FILE__);
  $command_files = get_files($commands_folder, 'php');
  
  $commands_help = array();
  if(is_foreachable($command_files)) {
    foreach($command_files as $command_file) {
      $command_name = substr(basename($command_file), 0, strlen(basename($command_file)) - 4);
      if($command_name == 'help') {
        continue;
      } // if
      $commands_help[$command_name] = require $command_file;
    } // foreach
  } // if
  
  print "Angie command line tool is made out of many small scripts that automate common development tasks.\n\nAvailable commands:\n\n";
  
  foreach($commands_help as $command_name => $command_help) {
    print "  $command_name - $command_help\n\n";
  } // foreach
  
  print "\nThere is more commands available in project command line tool that is initialized by executing 'init' command.\n";

?>