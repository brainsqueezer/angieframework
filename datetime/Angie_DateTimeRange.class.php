<?php

  /**
  * Datetime range makes managing ranges between two datetime objects pretty easy. This class 
  * can describe time range and provides methods for extraction data about the range itself 
  * (number of days/seconds etc between two dates etc)
  *
  * @package Angie.datetime
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_DateTimeRange {
    
    /**
    * From datetime object
    *
    * @var Angie_DateTime
    */
    private $from;
    
    /**
    * To datetime object
    *
    * @var Angie_DateTime
    */
    private $to;
  
    /**
    * Constructor
    *
    * @param Angie_DateTime $from
    * @param Angie_DateTime $to
    * @return Angie_DateTimeRange
    */
    function __construct(Angie_DateTime $from, Angie_DateTime $to) {
      $this->setFrom($from);
      $this->setTo($to);
    } // __construct
    
    /**
    * Move $from value for specific $amount of seconts
    *
    * @param integer $amount
    * @return null
    */
    function advanceFrom($amount) {
      $this->from->advance($amount, true);
    } // advanceFrom
    
    /**
    * Move $to timestamp value for specific $amount of seconts
    *
    * @param integer $amount
    * @return null
    */
    function advanceTo($amount) {
      $this->to->advance($amount, true);
    } // advanceTo
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get from
    *
    * @param null
    * @return Angie_DateTime
    */
    function getFrom() {
      return $this->from;
    } // getFrom
    
    /**
    * Set from value
    *
    * @param Angie_DateTime $value
    * @return null
    */
    function setFrom(Angie_DateTime $value) {
      $this->from = $value;
    } // setFrom
    
    /**
    * Get to
    *
    * @param null
    * @return Angie_DateTime
    */
    function getTo() {
      return $this->to;
    } // getTo
    
    /**
    * Set to value
    *
    * @param Angie_DateTime $value
    * @return null
    */
    function setTo(Angie_DateTime $value) {
      $this->to = $value;
    } // setTo
  
  } // Angie_DateTimeRange

?>