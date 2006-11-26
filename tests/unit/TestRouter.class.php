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
      $query_string = array_var($_SERVER, 'QUERY_STRING');
      
      Angie_Router::map('homepage', '/');
      Angie_Router::map('controller', '/:controller');
      Angie_Router::map('with_detaults', '/with_defaults/:controller/:id', array('action' => 'default_action', 'application' => 'frontend'));
      Angie_Router::map('longer', '/ilija/studen/:controller/:action/:id/');
      Angie_Router::map('with_query_string', '/with_query_string/:controller/:action/');
      
      $url_params = Angie_Router::match('/', $query_string);
      $this->assertEqual(array_var($url_params, 'application'), Angie::engine()->getDefaultapplicationName());
      $this->assertEqual(array_var($url_params, 'controller'), Angie::engine()->getDefaultControllerName());
      $this->assertEqual(array_var($url_params, 'action'), Angie::engine()->getDefaultActionName());
      
      $url_params = Angie_Router::match('controller/', $query_string);
      $this->assertEqual(array_var($url_params, 'application'), Angie::engine()->getDefaultapplicationName());
      $this->assertEqual(array_var($url_params, 'controller'), 'controller');
      $this->assertEqual(array_var($url_params, 'action'), Angie::engine()->getDefaultActionName());
      
      $url_params = Angie_Router::match('with_defaults/controller_name/1458', $query_string);
      $this->assertEqual(array_var($url_params, 'application'), 'frontend');
      $this->assertEqual(array_var($url_params, 'controller'), 'controller_name');
      $this->assertEqual(array_var($url_params, 'action'), 'default_action');
      $this->assertEqual((integer) array_var($url_params, 'id'), 1458);
      
      $url_params = Angie_Router::match('ilija/studen/admin/view_story/12', $query_string);
      $this->assertEqual(array_var($url_params, 'application'), Angie::engine()->getDefaultapplicationName());
      $this->assertEqual(array_var($url_params, 'controller'), 'admin');
      $this->assertEqual(array_var($url_params, 'action'), 'view_story');
      $this->assertEqual((integer) array_var($url_params, 'id'), 12);
      
      $query_string = 'param1=value1&param2=value2';
      $url_params = Angie_Router::match('with_query_string/controller/action', $query_string);
      $this->assertEqual(array_var($url_params, 'controller'), 'controller');
      $this->assertEqual(array_var($url_params, 'application'), Angie::engine()->getDefaultapplicationName());
      $this->assertEqual(array_var($url_params, 'action'), 'action');
      $this->assertEqual(array_var($url_params, 'param1'), 'value1');
      $this->assertEqual(array_var($url_params, 'param2'), 'value2');
      
      $query_string = 'id=4555&action=testing&controller=ninja';
      $url_params = Angie_Router::match('ilija/studen/admin/view_story/12', $query_string);
      $this->assertEqual(array_var($url_params, 'application'), Angie::engine()->getDefaultapplicationName());
      $this->assertEqual(array_var($url_params, 'controller'), 'admin');
      $this->assertEqual(array_var($url_params, 'action'), 'view_story');
      $this->assertEqual((integer) array_var($url_params, 'id'), 12);
    } // testRouting
  
  } // TestRouter

?>