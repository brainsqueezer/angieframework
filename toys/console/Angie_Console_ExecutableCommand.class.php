<?php

  /**
  * Executable console command
  * 
  * Executable commands inherit console command options extraction and add methods that describe the command and make 
  * it executable. It is used as base for most of Angie console commands because it provides methods that make listing 
  * and implementing commands run through console pretty easy:
  * 
  * - getDescription() - Returns the desciription of the command and is usually used when we need to list more commands 
  *   at once with a short description
  * - getHelp() - Returns command help that is rendered on request
  * - execute() - Called in order to execute the command. Output object is provided so command can notify user on 
  *   progress
  *
  * @package Angie.toys
  * @subpackage console
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Console_ExecutableCommand extends Angie_Console_Command {
  
    /**
    * Execute the command
    * 
    * Trigger this function to exeucte the command based on the input arguments
    *
    * @param Angie_Output $output
    * @return null
    */
    abstract function execute(Angie_Output $output);
    
    /**
    * Return command help
    * 
    * Return help describing this command, available arguments etc
    *
    * @param void
    * @return string
    */
    abstract function getHelp();
    
    /**
    * Return command description
    * 
    * Return short description that describes this command - usually used when listing available commands
    *
    * @param void
    * @return string
    */
    abstract function getDescription();
  
  } // Angie_Console_ExecutableCommand

?>