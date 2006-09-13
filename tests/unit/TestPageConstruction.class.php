<?php

  require ANGIE_PATH . '/controller/helpers/html.php';
  require ANGIE_PATH . '/controller/helpers/page.php';

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
    
    function testHelpers() {
      set_page_title('Haha');
      $this->assertEqual(get_page_title(), 'Haha');
      
      add_javascript_to_page('http://www.activecollab.com/some.js');
      add_javascript_to_page('http://www.activecollab.com/other.js');
      $scripts = Angie_PageConstruction::getScripts();
      $this->assertTrue(is_array($scripts) && count($scripts) == 2);
      
      add_stylesheet_to_page('http://www.activecollab.com/some.css');
      add_stylesheet_to_page('http://www.activecollab.com/other.css');
      $stylesheets = Angie_PageConstruction::getScripts();
      $this->assertTrue(is_array($stylesheets) && count($stylesheets) == 2);
    } // testHelpers
    
    function testFragments() {
      Angie_PageConstruction::setFragment('ilija', 12);
      $this->assertTrue(Angie_PageConstruction::hasFragment('ilija'));
      $this->assertEqual(Angie_PageConstruction::getFragment('ilija'), 12);
    } // testFragments
  
  } // TestPageConstruction

?>