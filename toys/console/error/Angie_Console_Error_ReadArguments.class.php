<?php

  /**
  * Read arguments error
  * 
  * This error is thrown when we fail to read console arguments
  *
  * @package Angie.toys
  * @subpackage console.errors
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Console_Error_ReadArguments extends Angie_Error {
  
    /**
    * Constructor
    *
    * @param string $message
    * @return Angie_Console_Error_ReadArguments
    */
    function __construct($message = null) {
      if(is_null($message)) {
        $message = 'Could not read cmd args (register_argc_argv=Off?)';
      } // if
      parent::__construct($message);
    } // __construct
  
  } // Angie_Console_Error_ReadArguments

?>