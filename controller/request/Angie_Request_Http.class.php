<?php

  /**
  * Default reuqst handle for URL request without routing ("dirty" URLs)
  *
  * @package Angie.controller
  * @subpackage requests
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Request_Http extends Angie_Request {
  
    /**
    * This function will get input string and transform it into controller/action/params. 
    * In case of any error it will return false
    *
    * @param string $request_string Request that need to be processed
    * @return boolean
    */
    function process($request_string) {
      
    } // process
  
  } // Angie_Request_Http

?>