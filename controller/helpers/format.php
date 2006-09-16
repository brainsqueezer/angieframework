<?php

  /**
  * Helpers that are used for formating various data
  *
  * @package Angie.controller
  * @subpackage helpers
  * @author Ilija Studen <ilija.studen@gmail.com>
  */

  /**
  * Format filesize
  *
  * @param integer $in_bytes Site in bytes
  * @return string
  */
  function format_filesize($in_bytes) {
    $units = array(
      'TB' => 1099511627776,
      'GB' => 1073741824,
      'MB' => 1048576,
      'kb' => 1024,
      //0 => 'bytes'
    ); // array
    
    foreach($units as $current_unit => $unit_min_value) {
      if($in_bytes > $unit_min_value) {
        $formated_number = number_format($in_bytes / $unit_min_value, 2);
        
        while(str_ends_with($formated_number, '0')) {
          $formated_number = substr($formated_number, 0, strlen($formated_number) - 1); // remove zeros from the end
        } // while
        if(str_ends_with($formated_number, '.')) {
          $formated_number = substr($formated_number, 0, strlen($formated_number) - 1); // remove dot from the end
        } // if
        
        return $formated_number . $current_unit;
      } // if
    } // foreach
    
    return $in_bytes . 'bytes';
  } // format_filesize

?>