<?php

  /**
  * Imlementation of default application behavior. This functions can be overriden in project 
  * application class and tailored to fit speicific needs
  *
  * @package Angie.engies
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Engine_Default extends Angie_Engine {
  
    /**
    * Initialize the application - load configuration file, connect to database, prepare resources...
    *
    * @param void
    * @return null
    */
    function init() {
      
    } // init
    
    /**
    * Handle user request. Request can be provided through web browser (as URL), thorugh command 
    * line interface, user can make an API call etc
    *
    * @param Angie_Request $request Request that need to be handled
    * @return null
    */
    function execute(Angie_Request $request) {
      
    } // execute
    
    /**
    * Clean up - this function is called on script shutdown. It is used to make logger write data to 
    * the files, clean resources (open connections) etc
    *
    * @param void
    * @return null
    */
    function close() {
      
    } // close
  
  } // Angie_Engine_Default

?>