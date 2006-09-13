<?php

  require ANGIE_PATH . '/controller/helpers/breadcrumbs.php';

  class TestBreadCrumbs extends UnitTestCase {
  
    /**
    * Constructor
    *
    * @param void
    * @return TestBreadCrumbs
    */
    function __construct() {
      $this->UnitTestCase('Test BreadCrumbs');
    } // __construct
    
    function testAdding() {
      
      // Test single argument
      add_bread_crumb('Test1', 'http://www.google.com/');
      
      $crumbs = bread_crumbs();
      $this->assertTrue(is_array($crumbs) && (count($crumbs) == 1));
      
      $crumb = array_var($crumbs, 0);
      $this->assertTrue($crumb instanceof Angie_BreadCrumb);
      $this->assertEqual($crumb->getTitle(), 'Test1');
      $this->assertEqual($crumb->getUrl(), 'http://www.google.com/');
      
      // Test array of arguments
      add_bread_crumbs(array(
        array('Test2', 'http://www.yahoo.com/'),
        array('Test3', 'http://www.msn.com/')
      ));
      
      $crumbs = bread_crumbs();
      $this->assertTrue(is_array($crumbs) && (count($crumbs) == 3));
      
      $crumb = array_var($crumbs, 1);
      $this->assertTrue($crumb instanceof Angie_BreadCrumb);
      $this->assertEqual($crumb->getTitle(), 'Test2');
      $this->assertEqual($crumb->getUrl(), 'http://www.yahoo.com/');
      
      $crumb = array_var($crumbs, 2);
      $this->assertTrue($crumb instanceof Angie_BreadCrumb);
      $this->assertEqual($crumb->getTitle(), 'Test3');
      $this->assertEqual($crumb->getUrl(), 'http://www.msn.com/');
      
      // Test constructed crumb
      add_bread_crumbs(new Angie_BreadCrumb('Test4', 'http://www.altavista.com/'));
      
      $crumbs = bread_crumbs();
      $this->assertTrue(is_array($crumbs) && (count($crumbs) == 4));
      
      $crumb = array_var($crumbs, 3);
      $this->assertTrue($crumb instanceof Angie_BreadCrumb);
      $this->assertEqual($crumb->getTitle(), 'Test4');
      $this->assertEqual($crumb->getUrl(), 'http://www.altavista.com/');
    } // testAdding
  
  } // TestBreadCrumbs

?>