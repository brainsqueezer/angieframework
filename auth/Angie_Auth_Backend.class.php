<?php

  /**
  * Abstract authentication backend interface
  *
  * @package Angie.auth
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  abstract class Angie_Auth_Backend {
  
    /**
    * Fetch and reuturn user based on given ID
    *
    * @param mixed $id
    * @return User
    */
    abstract function getUser($id);
    
    /**
    * Use credentials to authenticate user
    *
    * @param mixd $credentials
    * @return User
    */
    abstract function authenticate($credentials);
  
  } // Angie_Auth_Backend

?>