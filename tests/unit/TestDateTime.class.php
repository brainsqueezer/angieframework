<?php

  class TestDateTime extends UnitTestCase {
  
    /**
    * Construct the TestDateTimeValue
    *
    * @access public
    * @param void
    * @return TestDateTimeValue
    */
    function __construct() {
      $this->UnitTestCase('Test Angie_DateTime class');
    } // __construct
    
    function testInstantation() {
      $now = Angie_DateTime::now();
      $this->assertIsA($now, 'Angie_DateTime');
    } // testInstantation
    
    function testMySQLStringConversion() {
      $mysql_formated_date = gmdate(DATE_MYSQL);
      $now_from_string = Angie_DateTime::makeFromString($mysql_formated_date);
      $this->assertEqual($mysql_formated_date, $now_from_string->toMySQL());
    } // testMySQLStringConversion
    
    function testNow() {
      $now = Angie_DateTime::now();
      $now_from_string = Angie_DateTime::makeFromString(gmdate(DATE_MYSQL));
      
      $this->assertEqual($now->getDay(), $now_from_string->getDay());
      $this->assertEqual($now->getMonth(), $now_from_string->getMonth());
      $this->assertEqual($now->getYear(), $now_from_string->getYear());
      $this->assertEqual($now->getHour(), $now_from_string->getHour());
      $this->assertEqual($now->getMinute(), $now_from_string->getMinute());
      $this->assertEqual($now->getSecond(), $now_from_string->getSecond());
    } // testNow
    
    function testCreateFromParams() {
      $datetime = Angie_DateTime::make(14, 30, 15, 11, 14, 1984);
      $this->assertEqual($datetime->getDay(), 14);
      $this->assertEqual($datetime->getMonth(), 11);
      $this->assertEqual($datetime->getYear(), 1984);
      $this->assertEqual($datetime->getHour(), 14);
      $this->assertEqual($datetime->getMinute(), 30);
      $this->assertEqual($datetime->getSecond(), 15);
    } // testCreateFromParams
    
    function testCreateFromString() {
      $datetime = Angie_DateTime::makeFromString('14 Nov 1984 14:30:15');
      $this->assertEqual($datetime->getDay(), 14);
      $this->assertEqual($datetime->getMonth(), 11);
      $this->assertEqual($datetime->getYear(), 1984);
      $this->assertEqual($datetime->getHour(), 14);
      $this->assertEqual($datetime->getMinute(), 30);
      $this->assertEqual($datetime->getSecond(), 15);
    } // testCreateFromString
    
    function testFormats() {
      $datetime = Angie_DateTime::makeFromString('14 Nov 1984 14:30:15');
      $this->assertEqual($datetime->toMySQL(), '1984-11-14 14:30:15');
      $this->assertEqual($datetime->toISO8601(), '1984-11-14T14:30:15+0000');
      $this->assertEqual($datetime->toAtom(), '1984-11-14T14:30:15+0000');
      
      // Problems with this assertation: on some installation (tested on PHP 5.0.4) this
      // function will return 'Wed, 14 Nov 1984 14:30:15 GMT Standard Time' and on some '
      // Wed, 14 Nov 1984 14:30:15 GMT' as expected. Because of that this assertation
      // has been a bit modified - it will check if string starts with the
      // 'Wed, 14 Nov 1984 14:30:15 GMT' to make sure that date and the timezone are 
      // recognized. If PHP adds something behind that it would be OK
      //$this->assertEqual($datetime->toRSS(), 'Wed, 14 Nov 1984 14:30:15 GMT');
      $this->assertTrue(str_starts_with($datetime->toRSS(), 'Wed, 14 Nov 1984 14:30:15 GMT'));
    } // testFormats
    
    function testIsYesterday() {
      $yesterday_from_string = Angie_DateTime::makeFromString('yesterday');
      $today = Angie_DateTime::now();
      $yesterday_from_today = Angie_DateTime::make($today->getHour(), $today->getMinute(), $today->getSecond(), $today->getMonth(), $today->getDay() - 1, $today->getYear());
      $this->assertEqual($yesterday_from_string->getDay(), $yesterday_from_today->getDay());
      $this->assertEqual($yesterday_from_string->getMonth(), $yesterday_from_today->getMonth());
      $this->assertEqual($yesterday_from_string->getYear(), $yesterday_from_today->getYear());
    } // testIsYesterday
    
    function testAdvance() {
      $datetime = Angie_DateTime::makeFromString('14 Nov 1984 14:30:15');
      $datetime->advance(3600);
      $this->assertEqual($datetime->getHour(), 15);
      $datetime->advance(-15);
      $this->assertEqual($datetime->getSecond(), 0);
    } // testAdvance
    
    function testTimezones() {
      $timezones = Angie_Timezones::getAll();
      $this->assertTrue(is_array($timezones) && (count($timezones) == 33));
      
      $gmt = Angie_Timezones::getByOffset(0);
      $this->assertIsA($gmt, 'Angie_Timezone');
      $this->assertEqual($gmt->getFormattedOffset(), '');
      
      $belgrade = Angie_Timezones::getByOffset(3600);
      $this->assertIsA($belgrade, 'Angie_Timezone');
      $this->assertEqual($belgrade->getFormattedOffset(), '+01:00');
      $this->assertEqual($belgrade->getFormattedOffset(''), '+0100');
    } // testTimezones
  
  } // TestDateTimeValue

?>