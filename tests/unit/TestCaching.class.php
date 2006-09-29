<?php

  class TestCaching extends UnitTestCase {
    
    private $test_dir;
  
    /**
    * Constructor
    *
    * @param void
    * @return TestCaching
    */
    function __construct() {
      $this->UnitTestCase('Test caching');
      $this->test_dir = dirname(__FILE__) . '/cache/';
    } // __construct
    
    function setUp() {
      Angie_Cache::setBackend(new Angie_Cache_Backend_FS($this->test_dir));
    } // setUp
    
    function tearDown() {
      Angie_Cache::cleanUp();
    } // tearDown
    
    function testGetSet() {
      Angie_Cache::set('integer', 12);
      $integer = Angie_Cache::get('integer');
      $this->assertTrue(is_integer($integer), '$integer value retrieved from cache need to an integer');
      $this->assertEqual($integer, 12);
      
      Angie_Cache::set('string', 'this is string');
      $string = Angie_Cache::get('string');
      $this->assertTrue(is_string($string), '$string value retrieved from cache need to a string');
      $this->assertEqual($string, 'this is string');
      
      $cache_this_array = array(1, 2, 3, 4, 5, '12' => 'sh');
      Angie_Cache::set('array', $cache_this_array);
      $array = Angie_Cache::get('array');
      $this->assertTrue(is_array($array), '$array value retrieved from cache need to be an array');
      $this->assertEqual($array, $cache_this_array);
      
      $cache_this_object = new Angie_Core_Error_InvalidParamValue('$array', $array);
      Angie_Cache::set('object', $cache_this_object);
      $object = Angie_Cache::get('object');
      $this->assertTrue($object instanceof Angie_Core_Error_InvalidParamValue);
      $this->assertEqual($object, $cache_this_object);
      
      Angie_Cache::save();
    } // testGetSet
    
    function testSaveLoad() {
      $array = array(1, 2, 3, 4);
      $object = new Angie_Core_Error_InvalidParamValue('$array', $array);
      
      Angie_Cache::set('integer', 12);
      Angie_Cache::set('string', 'Ilija');
      Angie_Cache::set('array', $array);
      Angie_Cache::set('object', $object);
      
      Angie_Cache::save();
      
      Angie_Cache::setBackend(new Angie_Cache_Backend_FS($this->test_dir));
      
      $this->assertEqual(Angie_Cache::get('integer'), 12);
      $this->assertEqual(Angie_Cache::get('string'), 'Ilija');
      $this->assertEqual(Angie_Cache::get('array'), $array);
      
      // Object from cache and $object can't be equal (because they are two different objects, but with equal 
      // properties), but we can inspect them
      $object_from_cache = Angie_Cache::get('object');
      $this->assertIsA($object_from_cache, 'Angie_Core_Error_InvalidParamValue');
      $this->assertEqual($object_from_cache->getVariableName(), '$array');
      $this->assertEqual($object_from_cache->getVariableValue(), $array);
    } // testSaveLoad
    
    function testQuerying() {
      
    } // testQuerying
  
  } // TestCaching

?>