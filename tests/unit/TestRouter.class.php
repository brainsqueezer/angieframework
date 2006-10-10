<?php

  class TestRouter extends UnitTestCase {
  
    /**
    * Constructor
    *
    * @param void
    * @return TestRouter
    */
    function __construct() {
      $this->UnitTestCase('Test router');
    } // __construct
    
    function testMapping() {
      Angie_Router::map('default', 'ilija/studen/:controller/:action/:id/');
      $this->assertTrue(Angie_Router::match('ilija/studen/admin/view_story/'));
    } // testMapping
  
  } // TestRouter

?>