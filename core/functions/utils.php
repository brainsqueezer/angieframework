<?php

  /**
  * Angie specific utility functions
  *
  * @package Angie.core
  * @subpackage functions
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  /**
  * This function will walk through $dir and collect all .php files that has 'Test' as the beginning of 
  * filename, extract filenames and return them as array.
  *
  * @param string $dir Where to look
  * @return array
  */
  function collect_test_from_dir($dir) {
    $files = get_files($dir, 'php', true);
    
    $tests = array();
    if(is_array($files)) {
      foreach($files as $file) {
        $basename = basename($file);
        if(str_starts_with($basename, 'Test')) {
          $testname = substr($basename, 0, strpos($basename, '.'));
          if($testname) {
            $tests[$testname] = $file;
          } // if
        } // if
      } // foreach
    } // if
    
    return count($tests) ? $tests : null;
  } // collect_test_from_dir
  
  /**
  * Return valid boolean value
  *
  * @param mixed $value
  * @return boolean
  */
  function boolval($value) {
    return (boolean) $value;
  } // boolval
  
  /**
  * Take input value and return valid datetime object
  *
  * @param void
  * @return Angie_DateTime
  */
  function datetimeval($value) {
    if($value instanceof Angie_DateTime) {
      return $value;
    } else {
      if(is_integer($value)) {
        return new Angie_DateTime($value);
      } else {
        return new Angie_DateTime(strtotime((string) $value));
      } // if
    } // if
  } // datetimeval

?>