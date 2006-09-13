<?php

  /**
  * Single date time value. This class provides some handy methods for working 
  * with timestamps and extracting data from them
  *
  * @version 1.0
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DateTime {
    
    /**
    * Internal timestamp value
    *
    * @var integer
    */
    private $timestamp;
    
    /**
    * Cached day value
    *
    * @var integer
    */
    private $day;
    
    /**
    * Cached month value
    *
    * @var integer
    */
    private $month;
    
    /**
    * Cached year value
    *
    * @var integer
    */
    private $year;
    
    /**
    * Cached hour value
    *
    * @var integer
    */
    private $hour;
    
    /**
    * Cached minutes value
    *
    * @var integer
    */
    private $minute;
    
    /**
    * Cached seconds value
    *
    * @var integer
    */
    private $second;
    
    // ---------------------------------------------------
    //  Static methods
    // ---------------------------------------------------
    
    /**
    * Init datetime library (set global timezone to GMT)
    *
    * @param void
    * @return null
    */
    static function init() {
      static $inited = false;
      
      if($inited) {
        return;
      } // if
      
      ini_set('date.timezone', 'GMT');
      if(function_exists('date_default_timezone_set')) {
        date_default_timezone_set('GMT');
      } else {
        putenv('TZ=GMT');
      } // if
      
      $inited = true;
    } // init
    
    /**
    * Returns current time object
    *
    * @param void
    * @return Angie_DateTime
    */
    static function now() {
      return new Angie_DateTime(time());
    } // now
    
    /**
    * This function works like mktime, just it always returns GMT
    *
    * @param integer $hour
    * @param integer $minute
    * @param integer $second
    * @param integer $month
    * @param integer $day
    * @param integer $year
    * @return Angie_DateTime
    */
    static function make($hour, $minute, $second, $month, $day, $year) {
      return new Angie_DateTime(mktime($hour, $minute, $second, $month, $day, $year));
    } // make
    
    /**
    * Make time from string using strtotime() function. This function will return null
    * if it fails to convert string to the time
    *
    * @param string $str
    * @return Angie_DateTime
    */
    static function makeFromString($str) {
      $timestamp = strtotime($str);
      return ($timestamp === false) || ($timestamp === -1) ? null : new Angie_DateTime($timestamp);
    } // makeFromString
    
    // ---------------------------------------------------
    //  Instance methods
    // ---------------------------------------------------
  
    /**
    * Construct the Angie_DateTime
    *
    * @param integer $timestamp
    * @return Angie_DateTime
    */
    function __construct($timestamp) {
      $this->setTimestamp($timestamp);
    } // __construct
    
    /**
    * Advance for specific time
    *
    * @param void
    * @param integer $input Move the timestamp for this number of seconds
    * @param boolean $mutate If true update this timestamp, else reutnr new object and dont touch internal timestamp
    * @throws InvalidParamError
    */
    function advance($input, $mutate = true) {
      $timestamp = (integer) $input;
      if($timestamp <> 0) {
        if($mutate) {
          $this->setTimestamp($this->getTimestamp() + $timestamp);
        } else {
          return new Angie_DateTime($this->getTimestamp() + $timestamp);
        } // if
      } // if
    } // advance
    
    /**
    * This function will return true if this day is today
    *
    * @param void
    * @return boolean
    */
    function isToday() {
      $today = Angie_DateTime::now();
      return $this->getDay() == $today->getDay() &&
             $this->getMonth() == $today->getMonth() &&
             $this->getYear() == $today->getYear();
    } // isToday
    
    /**
    * This function will return true if this datetime is yesterday
    *
    * @param void
    * @return boolean
    */
    function isYesterday() {
      $yesterday = Angie_DateTime::makeFromString('yesterday');
      return $this->getDay() == $yesterday->getDay() &&
             $this->getMonth() == $yesterday->getMonth() &&
             $this->getYear() == $yesterday->getYear();
    } // isYesterday
    
    /**
    * This function will move interlan data to the beginning of day and return modified object. 
    * It can be called as:
    * 
    * $beggining = Angie_DateTime::now()->beginningOfDay()
    *
    * @access public
    * @param void
    * @return DateTime
    */
    function beginningOfDay() {
      $this->setHour(0);
      $this->setMinute(0);
      $this->setSecond(0);
      return $this;
    } // beginningOfDay
    
    /**
    * This function will set hours, minutes and seconds to 23:59:59 and return this object.
    * 
    * If you wish to get end of this day simply type:
    * 
    * $end = Angie_DateTime::now()->endOfDay()
    *
    * @param void
    * @return null
    */
    function endOfDay() {
      $this->setHour(23);
      $this->setMinute(59);
      $this->setSecond(59);
      return $this;
    } // endOfDay
    
    // ---------------------------------------------------
    //  Format to some standard values
    // ---------------------------------------------------
    
    /**
    * Return formated datetime
    *
    * @param string $format
    * @return string
    */
    function format($format) {
      return gmdate($format, $this->getTimestamp());
    } // format
    
    /**
    * Return datetime formated in MySQL datetime format
    *
    * @param void
    * @return string
    */
    function toMySQL() {
      return $this->format(DATE_MYSQL);
    } // toMySQL
    
    /**
    * Return ISO8601 formated time
    *
    * @param void
    * @return string
    */
    function toISO8601() {
      return $this->format(DATE_ISO8601);
    } // toISO
    
    /**
    * Return atom formated time (W3C format)
    *
    * @param void
    * @return string
    */
    function toAtom() {
      return $this->format(DATE_ATOM);
    } // toAtom
    
    /**
    * Return RSS format
    *
    * @param void
    * @return string
    */
    function toRSS() {
      return $this->format(DATE_RSS);
    } // toRSS
    
    /**
    * Return iCalendar formated date and time
    *
    * @param void
    * @return string
    */
    function toICalendar() {
      return $this->format('Ymd\THis\Z');
    } // toICalendar
    
    // ---------------------------------------------------
    //  Utils
    // ---------------------------------------------------
    
    /**
    * Break timestamp into its parts and set internal variables
    *
    * @param void
    * @return null
    */
    private function parse() {
      $data = getdate($this->timestamp);
      
      if($data) {
        $this->year   = (integer) $data['year'];
        $this->month  = (integer) $data['mon'];
        $this->day    = (integer) $data['mday'];
        $this->hour   = (integer) $data['hours'];
        $this->minute = (integer) $data['minutes'];
        $this->second = (integer) $data['seconds'];
      } // if
    } // parse
    
    /**
    * Update internal timestamp based on internal param values
    *
    * @param void
    * @return null
    */
    private function setTimestampFromAttributes() {
      $this->setTimestamp(mktime(
        $this->hour, 
        $this->minute, 
        $this->second, 
        $this->month, 
        $this->day, 
        $this->year
      )); // setTimestamp
    } // setTimestampFromAttributes
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get timestamp
    *
    * @param null
    * @return integer
    */
    function getTimestamp() {
      return $this->timestamp;
    } // getTimestamp
    
    /**
    * Set timestamp value
    *
    * @param integer $value
    * @return null
    */
    private function setTimestamp($value) {
      $this->timestamp = $value;
      $this->parse();
    } // setTimestamp
    
    /**
    * Return year
    *
    * @param void
    * @return integer
    */
    function getYear() {
      return $this->year;
    } // getYear
    
    /**
    * Set year value
    *
    * @param integer $value
    * @return null
    */
    function setYear($value) {
      $this->year = (integer) $year;
      $this->setTimestampFromAttributes();
    } // setYear
    
    /**
    * Return numberic representation of month
    *
    * @param void
    * @return integer
    */
    function getMonth() {
      return $this->month;
    } // getMonth
    
    /**
    * Set month value
    *
    * @param integer $value
    * @return null
    */
    function setMonth($value) {
      $this->month = (integer) $value;
      $this->setTimestampFromAttributes();
    } // setMonth
    
    /**
    * Return days
    *
    * @param void
    * @return integer
    */
    function getDay() {
      return $this->day;
    } // getDay
    
    /**
    * Set day value
    *
    * @param integer $value
    * @return null
    */
    function setDay($value) {
      $this->day = (integer) $value;
      $this->setTimestampFromAttributes();
    } // setDay
    
    /**
    * Return hour
    *
    * @param void
    * @return integer
    */
    function getHour() {
      return $this->hour;
    } // getHour
    
    /**
    * Set hour value
    *
    * @param integer $value
    * @return null
    */
    function setHour($value) {
      $this->hour = (integer) $value;
      $this->setTimestampFromAttributes();
    } // setHour
    
    /**
    * Return minute
    *
    * @param void
    * @return integer
    */
    function getMinute() {
      return $this->minute;
    } // getMinute
    
    /**
    * Set minutes value
    *
    * @param integer $value
    * @return null
    */
    function setMinute($value) {
      $this->minute = (integer) $value;
      $this->setTimestampFromAttributes();
    } // setMinute
    
    /**
    * Return seconds
    *
    * @param void
    * @return integer
    */
    function getSecond() {
      return $this->second;
    } // getSecond
    
    /**
    * Set seconds
    *
    * @param integer $value
    * @return null
    */
    function setSecond($value) {
      $this->second = (integer) $value;
      $this->setTimestampFromAttributes();
    } // setSecond
  
  } // Angie_DateTime

?>