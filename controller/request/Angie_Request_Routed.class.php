<?php

  /**
  * Routed request
  * 
  * Routed requests are matched with set of routes. First one that is matched is used to extract data from the request
  *
  * @package Angie.controller
  * @subpackage requests
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Request_Routed extends Angie_Request {
  
    /**
    * Process $request_string and populat request object and $_GET
    *
    * @param string $request_string
    * @return null
    */
    protected function process($request_string) {
      $_GET = (array) Angie_Router::match($request_string);
      $this->setControllerName(array_var($_GET, 'controller', Angie::DEFAULT_CONTROLLER_NAME));
      $this->setActionName(array_var($_GET, 'action', Angie::DEFAULT_ACTION_NAME));
    } // process
  
  } // Angie_Request_Routed

?>