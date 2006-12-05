<?php

  // We need default engine for this test
  require ANGIE_PATH . '/engine/Angie_Engine_Default.class.php';

  class TestAngie extends UnitTestCase {
  
    function __construct() {
      $this->UnitTestCase('Test Angie');
    } // __construct
    
    function testEngineAccess() {
//      Angie::setEngine(new Angie_Engine_Default());
//      $this->assertTrue(Angie::engine() instanceof Angie_Engine_Default);
//      $this->assertTrue(is_null(Angie::engine('dnx')));
//      Angie::setEngine(new Angie_Engine_Default(), 'additional');
//      $this->assertTrue(Angie::engine('additional') instanceof Angie_Engine_Default);
    } // testEngineAccess
  
  } // TestAngie

?>