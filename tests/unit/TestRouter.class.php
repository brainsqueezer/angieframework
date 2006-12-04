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
    
    function setUp() {
      Angie_Router::cleanUp();
      
      Angie_Router::map('homepage', '/');
      Angie_Router::map('controller', '/:controller');
      Angie_Router::map('with_detaults', '/with_defaults/:controller/:id', array(
        'controller' => 'default_controller',
        'action' => 'default_action', 
        'application' => 'frontend'
      )); // map
      Angie_Router::map('long', '/ilija/studen/:controller/:action/:id/');
      Angie_Router::map('with_query_string', '/with_query_string/:controller/:action/');
    } // setUp
    
    function testRouting() {
      $query_string = array_var($_SERVER, 'QUERY_STRING');
      
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
    
    function testAssembling() {
      $url_base = 'http://www.google.com';
      
      $homepage = Angie_Router::assemble('homepage');
      $this->assertEqual($homepage, '/');
      
      $homepage = Angie_Router::assemble('homepage', null, $url_base);
      $this->assertEqual($homepage, 'http://www.google.com/');
      
      $homepage = Angie_Router::assemble('homepage', array('id' => 12, 'sort' => 'by_name'));
      $this->assertEqual($homepage, '/?id=12&sort=by_name');
      
      $homepage = Angie_Router::assemble('homepage', array('id' => 12, 'sort' => 'by_name'), $url_base);
      $this->assertEqual($homepage, 'http://www.google.com/?id=12&sort=by_name');
      
      $with_defaults = Angie_Router::assemble('with_detaults', array('id' => 12));
      $this->assertEqual($with_defaults, '/with_defaults/default_controller/12');
      
      $with_defaults = Angie_Router::assemble('with_detaults', array('controller' => 'not_default', 'id' => 12));
      $this->assertEqual($with_defaults, '/with_defaults/not_default/12');
      
      $with_defaults = Angie_Router::assemble('with_detaults', array('controller' => 'not_default', 'id' => 12, 'query1' => 1, 'query2' => 2));
      $this->assertEqual($with_defaults, '/with_defaults/not_default/12?query1=1&query2=2');
      
      $with_defaults = Angie_Router::assemble('with_detaults', array('controller' => 'not_default', 'id' => 12, 'query1' => 1, 'query2' => 2), '', '&', 'anch');
      $this->assertEqual($with_defaults, '/with_defaults/not_default/12?query1=1&query2=2#anch');
      
      $with_defaults = Angie_Router::assemble('with_detaults', array('controller' => 'not_default', 'id' => 12, 'query1' => 1, 'query2' => 2), $url_base, '&', 'anch');
      $this->assertEqual($with_defaults, 'http://www.google.com/with_defaults/not_default/12?query1=1&query2=2#anch');
      
      $with_defaults = Angie_Router::assemble('with_detaults', array('controller' => 'not_default', 'id' => 12, 'query1' => 1, 'query2' => 2), $url_base, '&amp;');
      $this->assertEqual($with_defaults, 'http://www.google.com/with_defaults/not_default/12?query1=1&amp;query2=2');
    } // testAssembling
  
  } // TestRouter

?>