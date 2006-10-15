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
    
    function testRouting() {
      Angie_Router::map('/');
      Angie_Router::map('/:controller');
      Angie_Router::map('/with_defaults/:controller/:id', array('action' => 'default_action'));
      Angie_Router::map('ilija/studen/:controller/:action/:id/');
      
      $url_params = Angie_Router::match('/');
      $this->assertEqual(array_var($url_params, 'controller'), Angie::DEFAULT_CONTROLLER_NAME);
      $this->assertEqual(array_var($url_params, 'action'), Angie::DEFAULT_ACTION_NAME);
      
      $url_params = Angie_Router::match('/controller/');
      $this->assertEqual(array_var($url_params, 'controller'), 'controller');
      $this->assertEqual(array_var($url_params, 'action'), Angie::DEFAULT_ACTION_NAME);
      
      $url_params = Angie_Router::match('/with_defaults/controller_name/1458');
      $this->assertEqual(array_var($url_params, 'controller'), 'controller_name');
      $this->assertEqual(array_var($url_params, 'action'), 'default_action');
      $this->assertEqual((integer) array_var($url_params, 'id'), 1458);
      
      $url_params = Angie_Router::match('ilija/studen/admin/view_story/12');
      $this->assertEqual(array_var($url_params, 'controller'), 'admin');
      $this->assertEqual(array_var($url_params, 'action'), 'view_story');
      $this->assertEqual((integer) array_var($url_params, 'id'), 12);
    } // testRouting
  
  } // TestRouter

?>