<?php

  /**
  * Timezones
  * 
  * This class let user get a list of all timezones or to get information on any single timezone.
  *
  * @package Angie.datetime
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  final class Angie_Timezones {
    
    /**
    * Offset in seconds - cities map
    *
    * @var array
    */
    static private $timezones = array(
      -43200 => array('International Date Line West'),
      -39600 => array('Midway Island', 'Samoa'),
      -36000 => array('Hawaii'),
      -32400 => array('Alaska'),
      -28800 => array('Pacific Time (US & Canada)', 'Tijuana'),
      -25200 => array('Mountain Time (US & Canada)', 'Chihuahua', 'La Paz', 'Mazatlan', 'Arizona'),
      -21600 => array('Central Time (US & Canada)', 'Saskatchewan', 'Guadalajara', 'Mexico City', 'Monterrey', 'Central America'),
      -18000 => array('Eastern Time (US & Canada)', 'Indiana (East)', 'Bogota', 'Lima', 'Quito'),
      -14400 => array('Atlantic Time (Canada)', 'Caracas', 'La Paz', 'Santiago'),
      -12600 => array('Newfoundland'),
      -10800 => array('Brasilia', 'Buenos Aires', 'Georgetown', 'Greenland'), 
       -7200 => array('Mid-Atlantic'),
       -3600 => array('Azores', 'Cape Verde Is.'),
           0 => array('Dublin', 'Edinburgh', 'Lisbon', 'London', 'Casablanca', 'Monrovia'),
        3600 => array('Belgrade', 'Bratislava', 'Budapest', 'Ljubljana', 'Prague', 'Sarajevo', 'Skopje', 'Warsaw', 'Zagreb', 'Brussels', 'Copenhagen', 'Madrid', 'Paris', 'Amsterdam', 'Berlin', 'Bern', 'Rome', 'Stockholm', 'Vienna', 'West Central Africa'),
        7200 => array('Bucharest', 'Cairo', 'Helsinki', 'Kyev', 'Riga', 'Sofia', 'Tallinn', 'Vilnius', 'Athens', 'Istanbul', 'Minsk', 'Jerusalem', 'Harare', 'Pretoria'),
       10800 => array('Moscow', 'St. Petersburg', 'Volgograd', 'Kuwait', 'Riyadh', 'Nairobi', 'Baghdad'),
       12600 => array('Tehran'),
       14400 => array('Abu Dhabi', 'Muscat', 'Baku', 'Tbilisi', 'Yerevan'),
       16200 => array('Kabul'),
       18000 => array('Ekaterinburg', 'Islamabad', 'Karachi', 'Tashkent'),
       19800 => array('Chennai', 'Kolkata', 'Mumbai', 'New Delhi'),
       20700 => array('Kathmandu'),
       21600 => array('Astana', 'Dhaka', 'Sri Jayawardenepura', 'Almaty', 'Novosibirsk'),
       23400 => array('Rangoon'),
       25200 => array('Bangkok', 'Hanoi', 'Jakarta', 'Krasnoyarsk'),
       28800 => array('Beijing', 'Chongqing', 'Hong Kong', 'Urumqi', 'Kuala Lumpur', 'Singapore', 'Taipei', 'Perth', 'Irkutsk', 'Ulaan Bataar'),
       32400 => array('Seoul', 'Osaka', 'Sapporo', 'Tokyo', 'Yakutsk'),
       34200 => array('Darwin', 'Adelaide'),
       36000 => array('Canberra', 'Melbourne', 'Sydney', 'Brisbane', 'Hobart', 'Vladivostok', 'Guam', 'Port Moresby'),
       39600 => array('Magadan', 'Solomon Is.', 'New Caledonia'),
       43200 => array('Fiji', 'Kamchatka', 'Marshall Is.', 'Auckland', 'Wellington'),
       46800 => array('Nuku\'alofa'),
    ); // array
  
    /**
    * Return all timezones
    * 
    * Use map at self::$timezones and return array of populated Angie_Timezone objects
    *
    * @param void
    * @return array
    */
    static function getAll() {
      $result = array();
      foreach(self::$timezones as $offset => $name) {
        $result[] = new Angie_Timezone($offset, implode(', ', $name));
      } // foreach
      return $result;
    } // getAll
    
    /**
    * Return timezone object by offset (in seconds)
    * 
    * Invalid parametar exception will be thrown if timezone with a given offset does not exist
    *
    * @param integer $offset
    * @return Angie_Timezone
    * @throws Angie_Core_Error_InvalidParamValue
    */
    static function getByOffset($offset) {
      $name = array_var(self::$timezones, $offset);
      if(is_array($name)) {
        return new Angie_Timezone($offset, implode(', ', $name));
      } else {
        throw new Angie_Core_Error_InvalidParamValue('offset', $offset, "Timezone with offset of $offset seconds does not exist");
      } // if
    } // getByOffset
  
  } // Angie_Timezones

?>