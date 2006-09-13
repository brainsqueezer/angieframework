<?php

  class TestPageConstruction extends UnitTestCase {
  
    /**
    * Constructor
    *
    * @param void
    * @return TestPageConstruction
    */
    function __construct() {
      $this->UnitTestCase('Test page construction');
    } // __construct
    
    function testHead() {
      Angie_PageConstruction::setKeywords('test, test2');
      Angie_PageConstruction::setDescription('This is test page');
      
      $meta = Angie_PageConstruction::getMetaTags();
      $this->assertTrue(is_array($meta) && count($meta) == 2);
    } // testHead
    
    function testFragments() {
      
    } // testFragments
  
  } // TestPageConstruction

?>