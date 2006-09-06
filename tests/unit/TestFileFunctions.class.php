<?php

  class TestFileFunctions extends UnitTestCase {
  
    /**
    * Constructor
    *
    * @param void
    * @return TestFileFunctions
    */
    function __construct() {
      $this->UnitTestCase('Test file functions');
    } // __construct
    
    function testGetFileExtensionFunction() {
      $this->assertEqual(get_file_extension('index.php'), 'php');
      $this->assertEqual(get_file_extension('index.php', true), '.php');
      $this->assertEqual(get_file_extension('Blog.class.php'), 'php');
      $this->assertEqual(get_file_extension('Blog.class.php', true), '.php');
    } // testGetFileExtensionFunction
  
  } // TestFileFunctions

?>