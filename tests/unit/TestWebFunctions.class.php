<?php

  class TestWebFunctions extends UnitTestCase {
  
    /**
    * Constructor
    *
    * @param void
    * @return TestWebFunctions
    */
    function __construct() {
      $this->UnitTestCase('Test web functions');
    } // __construct
    
    function testValidUrlProtocol() {
      $this->assertEqual(valid_url_protocol('www.google.com'), 'http://www.google.com');
      $this->assertEqual(valid_url_protocol('www.google.com', false, 'https'), 'https://www.google.com');
      $this->assertEqual(valid_url_protocol('', true, 'https'), '');
      $this->assertEqual(valid_url_protocol('http://www.google.com'), 'http://www.google.com');
    } // testValidUrlProtocol
  
  } // TestWebFunctions

?>