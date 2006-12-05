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
      
      $match = Angie_Router::match($request_path, $query_string);
      if($match instanceof Angie_Router_Match) {
        $_GET = $match->getMatches();
        
        $this->setApplicationName(array_var($_GET, 'application'));
        $this->setControllerName(array_var($_GET, 'controller'));
        $this->setActionName(array_var($_GET, 'action'));
      } else {
        throw new Angie_Router_Error_Match($request_string, 'Invalid match result returned');
      } // if
    } // process
  
  } // Angie_Request_Routed

?>