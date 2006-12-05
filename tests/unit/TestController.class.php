<?php

  require_once ANGIE_PATH . '/tests/unit/controller/BaseTestController.class.php';
  require_once ANGIE_PATH . '/tests/unit/controller/PageTestController.class.php';

  class TestController extends UnitTestCase {
    
    private $engine;
  
    /**
    * Constructor
    *
    * @param void
    * @return TestController
    */
    function __construct() {
      $this->UnitTestCase('Test controller logic');
    } // __construct
    
    function setUp() {
      $this->engine = new Angie_Engine();
      $this->engine->setRequest(new Angie_Request_Get(''));
    } // setUp
    
    function testControllerName() {
      $base_test_controller = new BaseTestController();
      $base_test_controller->setEngine($this->engine);
      
      $this->assertEqual($base_test_controller->getControllerName(), 'base_test');
    } // testControllerName
    
    function testClassProtection() {
      $base_test_controller = new BaseTestController(false); // don't protect
      $base_test_controller->setEngine($this->engine);
      
      $this->assertTrue($base_test_controller->isValidAction('invisible'));
      
      $base_test_controller = new BaseTestController(true); // protect
      $base_test_controller->setEngine($this->engine);
      
      $this->assertFalse($base_test_controller->isValidAction('invisible'));
    } // testClassProtection
    
    function testPageControllerViewPath() {
      $default_application = Angie::getConfig('system.default_application');
      
      $page_test_controller = new PageTestController();
      $page_test_controller->setEngine($this->engine);
      
      $page_test_controller->setView('sample');
      $this->assertEqual($page_test_controller->getViewPath(), "/project/applications/$default_application/views/page_test/sample.php");
      
      $page_test_controller->setView(array('controller', 'view'));
      $this->assertEqual($page_test_controller->getViewPath(), "/project/applications/$default_application/views/controller/view.php");
      
      $this->assertEqual($page_test_controller->getLayoutPath(), "/project/applications/$default_application/layouts/page_test.php");
    } // testPageControllerViewPath
  
  } // TestController

?>