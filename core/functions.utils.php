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
  * Return controller name based on controller class; name will be converted to underscore 
  * and 'Controller' sufix will be removed
  *
  * @param string $controller_class
  * @return string
  */
  function get_controller_name($controller_class) {
    return Inflector::underscore(substr($controller_class, 0, strlen($controller_class) - 10));
  } // get_controller_name
  
  /**
  * Return controller class based on controller name; controller name will be 
  * camelized and Controller will be added as sufix
  *
  * @param string $controller_name
  * @return string
  */
  function get_controller_class($controller_name) {
    return Inflector::camelize($controller_name) . 'Controller';
  } // get_controller_class

?>