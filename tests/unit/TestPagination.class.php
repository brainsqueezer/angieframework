<?php

  class TestPagination extends UnitTestCase {
    
    /**
    * Pagination test object
    *
    * @var Angie_Pagination
    */
    private $pagination;
  
    /**
    * Constructor
    *
    * @param void
    * @return TestPagination
    */
    function __construct() {
      $this->UnitTestCase('Test pagination toy');
    } // __construct
    
    function setUp() {
      $this->pagination = new Angie_Pagination(97, 10, 5);
    } // setUp
    
    function testFirstPage() {
      $this->pagination->setCurrentPage(1);
      $this->assertFalse($this->pagination->hasPrevious());
      $this->assertTrue($this->pagination->hasNext());
    } // testFirstPage
    
    function testMiddlePage() {
      $this->pagination->setCurrentPage(5);
      $this->assertTrue($this->pagination->hasPrevious());
      $this->assertTrue($this->pagination->hasNext());
    } // testMiddlePage
    
    function testLastPage() {
      $this->pagination->setCurrentPage(10);
      $this->assertTrue($this->pagination->hasPrevious());
      $this->assertFalse($this->pagination->hasNext());
    } // testLastPage
    
    function testLogic() {
      $this->assertEqual($this->pagination->getTotalPages(), 10);
      $this->assertEqual($this->pagination->countItemsOnPage(10), 7);
      $this->assertEqual($this->pagination->getLimitStart(1), 0);
      $this->assertEqual($this->pagination->getLimitStart(2), 10);
      $this->assertEqual($this->pagination->getLimitStart(10), 90);
    } // testLogic
  
  } // TestPagination

?>