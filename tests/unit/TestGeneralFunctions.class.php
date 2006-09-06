<?php

  class TestGeneralFunctions extends UnitTestCase {
    
    function __construct() {
      $this->UnitTestCase('Test general functions');
    } // __construct
    
    function testStrStartsWithFunction() {
      $this->assertTrue(str_starts_with('Ilija Studen', 'I'));
      $this->assertTrue(str_starts_with('Ćurčić Nenad', 'Ć'));
      $this->assertFalse(str_starts_with('Windows', 'M'));
    } // testStrStartsWithFunction
    
    function testStrEndsWithFunction() {
      $this->assertTrue(str_ends_with('Ilija Studen', 'n'));
      $this->assertTrue(str_ends_with('Ćurčić', 'ć'));
      $this->assertFalse(str_ends_with('Windows', 'X'));
    } // testStrEndsWithFunction
    
    function testWithWithoutSlash() {
      $start_with = 'ilija';
      $start_with = with_slash($start_with);
      $this->assertTrue(str_ends_with($start_with, '/'));
      $start_with = without_slash($start_with);
      $this->assertFalse(str_ends_with($start_with, '/'));
    } // testWithWithoutSlash
    
    function testReplaceFirst() {
      $this->assertEqual(
        str_replace_first('?', '12', 'SELECT * FROM `hehe` WHERE `x` = ? AND `y` = ?'), 
        'SELECT * FROM `hehe` WHERE `x` = 12 AND `y` = ?'
      ); // assertEqual
    } // testReplaceFirst
    
    /**
    * Test is_valid_email function
    *
    * @param void
    * @return null
    * @todo This test need to be extended
    */
    function testIsValidEmail() {
      $this->assertTrue(is_valid_email('ilija.studen@activecollab.com'));
      $this->assertTrue(is_valid_email('ilija.studen@code.activecollab.com'));
      $this->assertFalse(is_valid_email('ilija.studen"activecollab.com'));
    } // testIsValidEmail
    
    /**
    * Test is_valid_url function
    *
    * @param void
    * @return null
    * @todo This test need to be extended a bit
    */
    function testIsValidUrl() {
      $this->assertTrue(is_valid_url('http://www.google.com'));
      $this->assertTrue(is_valid_url('http://www.google.com/'));
      $this->assertTrue(is_valid_url('http://google.com'));
      $this->assertTrue(is_valid_url('http://google.com/?search_for=12'));
      $this->assertTrue(is_valid_url('http://google.com/index.php?search_for=12'));
      $this->assertFalse(is_valid_url('ilija studen'));
    } // testIsValidUrl
    
    function testIsValidFunctionName() {
      $this->assertTrue(is_valid_function_name('testSomething'));
      $this->assertTrue(is_valid_function_name('test_something'));
      $this->assertFalse(is_valid_function_name('12test_something'));
      $this->assertFalse(is_valid_function_name('test something'));
    } // testIsValidFunctionName
    
    function testUndoHtmlspecialchars() {
      $this->assertEqual(
        undo_htmlspecialchars('&lt;title&gt;something &amp; anything&lt;/title&gt;'), 
        '<title>something & anything</title>');
    } // testUndoHtmlspecialchars
    
    function testArrayVar() {
      $test_this = array(
        'color' => 'red',
        12 => 'yes, it is'
      ); // array
      $this->assertEqual(array_var($test_this, 'color'), 'red');
      $this->assertEqual(array_var($test_this, 12), 'yes, it is');
      $this->assertEqual(array_var($test_this, 'dnx!', 'default'), 'default');
      $this->assertFalse(array_var($test_this, 18), 'yes, it is');
    } // testArrayVar
    
    function testStringToArray() {
      $string = 'ilija';
      $array = array('i', 'l', 'i', 'j', 'a');
      $this->assertEqual(string_to_array($string), $array);
      $this->assertNotEqual(string_to_array('stevan'), $array);
    } // testStringToArray
    
    function testArrayFlat() {
      $array = array(
        12,
        array(13, 14),
        15,
        array(array(16, 17), 18)
      );
      $flat = array(12, 13, 14, 15, 16, 17, 18);
      $this->assertEqual(array_flat($array), $flat);
    } // testArrayFlat
  
  } // TestGeneralFunctions

?>