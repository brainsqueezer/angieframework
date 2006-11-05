<?php

  class Angie_Command_All extends Angie_Console_ExecutableCommand {
  
    /**
    * Execute the command
    * 
    * Trigger this function to exeucte the command based on the input arguments
    *
    * @param Angie_Output $output
    * @return null
    */
    function execute(Angie_Output $output) {
      $commands = Angie::getAvailableCommands();
      if(is_foreachable($commands)) {
        $output->printMessage('Available commands:');
        $output->printMessage('');
        
        $longest_command_name = 0;
        $name_description_map = array();
        foreach($commands as $command => $handler_file) {
          if(strlen($command) > $longest_command_name) {
            $longest_command_name = strlen($command);
          } // if
          
          require_once $handler_file;
          $handler = Angie::constructCommandHandler($command);
          
          $name_description_map[$command] = $handler->defineDescription();
        } // foreach
        
        foreach($name_description_map as $name => $description) {
          $result = $name;
          
          for($i = strlen($name); $i < ($longest_command_name + 2); $i++) {
            $result .= ' ';
          } // for
          
          $result .= '- ' . $description;
          
          $output->printMessage($result);
        } // foreach
        
        $output->printMessage('');
        $output->printMessage('To see more information for any specific command type <command> --help');
      } else {
        $output->printMessage('No commands available');
      } // if
    } // execute
    
    /**
    * Return command description
    *
    * @param void
    * @return string
    */
    function defineDescription() {
      return 'List all available commands with a short description';
    } // defineDescription
  
  } // Angie_Command_All

?>