<?php

  class TestConsole extends UnitTestCase {
  
    /**
    * Constructor
    *
    * @param void
    * @return TestGeopt
    */
    function __construct() {
      $this->UnitTestCase('Test getopt toy');
    } // __construct
    
    function testShort() {
      $command = Angie_Console::prepareCommand(
        explode(' ', 'create-controller my_controller -v -o -d base_directory'),
        array('v', 'o', 'd:'),
        array()
      ); // prepareCommand
      
      $this->assertIsA($command, 'Angie_Console_Command');
      $this->assertEqual($command->getArgument(0), 'create-controller');
      $this->assertEqual($command->getArgument(1), 'my_controller');
      $this->assertTrue($command->getOption('v'));
      $this->assertTrue($command->getOption('o'));
      $this->assertEqual($command->getOption('d'), 'base_directory');
    } // testShort
    
    function testLong() {
      $command = Angie_Console::prepareCommand(
        explode(' ', 'create-controller my_controller --verbose --overwrite --dir=base_directory'),
        array(),
        array('verbose', 'overwrite', 'dir:')
      ); // prepareCommand
      
      $this->assertIsA($command, 'Angie_Console_Command');
      $this->assertEqual($command->getArgument(0), 'create-controller');
      $this->assertEqual($command->getArgument(1), 'my_controller');
      $this->assertTrue($command->getOption('verbose'));
      $this->assertTrue($command->getOption('overwrite'));
      $this->assertEqual($command->getOption('dir'), 'base_directory');
    } // testLong
    
    function testCombined() {
      $command = Angie_Console::prepareCommand(
        explode(' ', 'create-controller my_controller -v -p password --overwrite --dir=base_directory'),
        array('v', 'p:'),
        array('overwrite', 'dir:')
      ); // prepareCommand
      
      $this->assertIsA($command, 'Angie_Console_Command');
      $this->assertEqual($command->getArgument(0), 'create-controller');
      $this->assertEqual($command->getArgument(1), 'my_controller');
      $this->assertTrue($command->getOption('v'));
      $this->assertTrue($command->getOption('overwrite'));
      $this->assertEqual($command->getOption('p'), 'password');
    } // testExtraction
    
    function testOptionals() {
      $command = Angie_Console::prepareCommand(
        explode(' ', 'do -v'),
        array('v')
      ); // prepareCommand
      
      $this->assertIsA($command, 'Angie_Console_Command');
      $this->assertTrue($command->getOption('v'));
      $this->assertFalse($command->getOption('u'));
      $this->assertFalse($command->getOption('unknown'));
      
      $command = Angie_Console::prepareCommand(
        explode(' ', 'do -v'),
        array('v'),
        array('verbose')
      ); // prepareCommand
      
      $this->assertTrue($command->getOption('v', 'verbose'));
      
      $command = Angie_Console::prepareCommand(
        explode(' ', 'do --verbose'),
        array('v'),
        array('verbose')
      ); // prepareCommand
      
      $this->assertTrue($command->getOption('v', 'verbose'));
      
      $command = Angie_Console::prepareCommand(
        explode(' ', 'do'),
        array('v'),
        array('verbose')
      ); // prepareCommand
      
      $this->assertFalse($command->getOption('v', 'verbose'));
    } // testOptionals
  
  } // TestConsole

?>