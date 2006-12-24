<?php

  interface Angie_Auth_User {
    
    function getUsername();
    
    function checkPassword($password);
    
    function getDisplayName();
    
  } // Angie_Auth_User

?>