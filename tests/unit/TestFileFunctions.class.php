<?php

  class TestFileFunctions extends UnitTestCase {
    
    private $test_dir = null;
    private $test_empty_dir = null;
  
    /**
    * Constructor
    *
    * @param void
    * @return TestFileFunctions
    */
    function __construct() {
      $this->UnitTestCase('Test file functions');
      $this->test_dir = dirname(__FILE__) . '/file_functions';
      $this->test_empty_dir = $this->test_dir . '/empty';
    } // __construct
    
    function testGetFileExtensionFunction() {
      $this->assertEqual(get_file_extension('index.php'), 'php');
      $this->assertEqual(get_file_extension('index.php', true), '.php');
      $this->assertEqual(get_file_extension('Blog.class.php'), 'php');
      $this->assertEqual(get_file_extension('Blog.class.php', true), '.php');
    } // testGetFileExtensionFunction
    
    function testIsDirEmptyFunction() {
      $this->assertFalse(is_dir_empty($this->test_dir));
      $this->assertTrue(is_dir_empty($this->test_empty_dir));
    } // testIsDirEmptyFunction
  
  } // TestFileFunctions

?>