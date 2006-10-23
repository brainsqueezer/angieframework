<?php

  /**
  * Silent output
  * 
  * This output object will not print anything. It is used mostly for tests
  *
  * @package Angie.toys
  * @subpackage output
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Output_Silent extends Angie_Output {
  
    /**
    * Print nothing
    *
    * @param string $message
    * @param string $type
    * @return null
    */
    function printMessage($message, $type = null) {
      // pssssst!
    } // printMessage
  
  } // Angie_Output_Silent

?>