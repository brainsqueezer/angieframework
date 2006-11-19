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
    * @throws Angie_Router_Error_Match
    */
    protected function process($request_string) {
      $request_path = '';
      $query_string = '';
      
      $query_string_pos = strrpos($request_string, '?');
      if($query_string_pos === false) {
        $request_path = $request_string;
      } else {
        $request_path = substr($request_string, 0, $query_string_pos);
        $query_string = substr($request_string, $query_string_pos + 1);
      } // if
      
      $_GET = (array) Angie_Router::match($request_path, $query_string);
      
      $this->setApplicationName(array_var($_GET, 'application'), Angie::engine()->getDefaultApplicationName());
      $this->setControllerName(array_var($_GET, 'controller', Angie::engine()->getDefaultControllerName()));
      $this->setActionName(array_var($_GET, 'action', Angie::engine()->getDefaultActionName()));
    } // process
  
  } // Angie_Request_Routed

?>