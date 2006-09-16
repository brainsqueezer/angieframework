<?php

  //require_once ROOT . '/environment/classes/logger/Logger.class.php';

  class TestLogger extends UnitTestCase {
    
    private $test_folder;
    
    private $test_file;
  
    /**
    * Constructor
    *
    * @param void
    * @return TestLogger
    */
    function __construct() {
      $this->UnitTestCase('Test logger');
      $this->test_folder = dirname(__FILE__) . '/logger';
      $this->test_file = $this->test_folder . '/' . gmdate('Y-m-d');
    } // __construct
    
    function setUp() {
      Angie_Logger::setGroup(new Angie_Logger_Group());
    } // setUp
    
    function tearDown() {
      @unlink($this->test_file);
    } // tearDown
    
    function testGroup() {
      Angie_Logger::setGroup(new Angie_Logger_Group());
      $default_group = Angie_Logger::getGroup();
      $this->assertEqual($default_group->getName(), Angie_Logger::DEFAULT_GROUP_NAME);
      Angie_Logger::setGroup(new Angie_Logger_Group('mysql'), 'mysql');
      $mysql_group = Angie_Logger::getGroup('mysql');
      $this->assertEqual($mysql_group->getName(), 'mysql');
    } // testGroup
    
    function testLogging() {
      Angie_Logger::getGroup()->setMinSeverity(Angie_Logger::DEBUG);
      $this->assertTrue(Angie_Logger::log('Debug', Angie_Logger::DEBUG));
      $this->assertTrue(Angie_Logger::log('Info', Angie_Logger::INFO));
      $this->assertTrue(Angie_Logger::log('Warning', Angie_Logger::WARNING));
      $this->assertTrue(Angie_Logger::log('Error', Angie_Logger::ERROR));
      $this->assertTrue(Angie_Logger::log('Fatal', Angie_Logger::FATAL));
      $this->assertTrue(Angie_Logger::log('Unknown', Angie_Logger::UNKNOWN));
      
      Angie_Logger::getGroup()->setMinSeverity(Angie_Logger::ERROR);
      $this->assertFalse(Angie_Logger::log('Debug', Angie_Logger::DEBUG));
      $this->assertFalse(Angie_Logger::log('Info', Angie_Logger::INFO));
      $this->assertFalse(Angie_Logger::log('Warning', Angie_Logger::WARNING));
      $this->assertTrue(Angie_Logger::log('Error', Angie_Logger::ERROR));
      $this->assertTrue(Angie_Logger::log('Fatal', Angie_Logger::FATAL));
      $this->assertTrue(Angie_Logger::log('Unknown', Angie_Logger::UNKNOWN));
      
      Angie_Logger::setEnabled(false);
      $this->assertFalse(Angie_Logger::log('Debug', Angie_Logger::DEBUG));
      $this->assertFalse(Angie_Logger::log('Info', Angie_Logger::INFO));
      $this->assertFalse(Angie_Logger::log('Warning', Angie_Logger::WARNING));
      $this->assertFalse(Angie_Logger::log('Error', Angie_Logger::ERROR));
      $this->assertFalse(Angie_Logger::log('Fatal', Angie_Logger::FATAL));
      $this->assertFalse(Angie_Logger::log('Unknown', Angie_Logger::UNKNOWN));
    } // testLogging
    
    function testFileBackend() {
      Angie_Logger::setBackend(new Angie_Logger_Backend_File($this->test_file));
      if(!Angie_Logger::getEnabled()) {
        Angie_Logger::setEnabled(true); // re-enable
      } // if
      
      Angie_Logger::log('This is one debug message', Angie_Logger::DEBUG);
      Angie_Logger::log("Multiline info message\nTo keep things interesting\nReally", Angie_Logger::DEBUG);
      Angie_Logger::log('Just another single line error', Angie_Logger::ERROR);
      Angie_Logger::log('What is this?', Angie_Logger::UNKNOWN);
      $exception = new Exception('This is test exception. Will are not throwing it, just using it for testing');
      Angie_Logger::log($exception);
      
      $this->assertTrue(Angie_Logger::saveGroup());
      
      Angie_Logger::setGroup(new Angie_Logger_Group('additional'), 'additional');
      Angie_Logger::log('This is one debug message', Angie_Logger::DEBUG, 'additional');
      Angie_Logger::log("Multiline info message\nTo keep things interesting\nReally", Angie_Logger::DEBUG, 'additional');
      Angie_Logger::log('Just another single line error', Angie_Logger::ERROR, 'additional');
      Angie_Logger::log('What is this?', Angie_Logger::UNKNOWN, 'additional');
      
      $this->assertTrue(Angie_Logger::saveAll());
    } // testFileBackend
  
  } // TestLogger

?>