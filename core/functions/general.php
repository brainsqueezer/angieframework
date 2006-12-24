<?php

  /**
  * General purpose functions
  * 
  * This file contains various general purpose functions used for string and array manipulation, input filtering, ouput 
  * cleaning end so on.
  *
  * @package Angie.core
  * @subpackage functions
  * @author Ilija Studen <ilija.studen@gmail.com>
  */

  /**
  * This function will return true only if input string starts with
  * niddle
  *
  * @param string $string Input string
  * @param string $niddle Needle string
  * @return boolean
  */
  function str_starts_with($string, $niddle) {  
  	return substr($string, 0, strlen($niddle)) == $niddle;  	
  } // end func str_starts with
  
  /**
  * This function will return true only if input string ends with
  * niddle
  *
  * @param string $string Input string
  * @param string $niddle Needle string
  * @return boolean
  */
  function str_ends_with($string, $niddle) {
    return substr($string, strlen($string) - strlen($niddle), strlen($niddle)) == $niddle;
  } // end func str_ends_with
  
  /**
  * Return path with trailing slash
  *
  * @param string $path Input path
  * @return string Path with trailing slash
  */
  function with_slash($path) {
    return str_ends_with($path, '/') ? $path : $path . '/';
  } // end func with_slash
  
  /**
  * Remove trailing slash from the end of the path (if exists)
  *
  * @param string $path File path that need to be handled
  * @return string
  */
  function without_slash($path) {
    return str_ends_with($path, '/') ? substr($path, 0, strlen($path) - 1) : $path;
  } // without_slash
  
  /**
  * Replace first $search_for with $replace_with in $in. If $search_for is not found
  * original $in string will be returned...
  *
  * @param string $search_for Search for this string
  * @param string $replace_with Replace it with this value
  * @param string $in Haystack
  * @return string
  */
  function str_replace_first($search_for, $replace_with, $in) {
    $pos = strpos($in, $search_for);
    if($pos === false) {
      return $in;
    } else {
      return substr($in, 0, $pos) . $replace_with . substr($in, $pos + strlen($search_for), strlen($in));
    } // if
  } // str_replace_first
  
  /**
  * Make a passsword out of list of allowed characters with a given length
  *
  * @param integer $length
  * @param string $allowed_chars
  * @return string
  */
  function make_password($length = 10, $allowed_chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789') {
    $pass = '';
    $allowed_chars_len = strlen($allowed_chars);
    
    for($i = 0; $i < $length; $i++) {
      $pass .= substr($allowed_chars, rand(0, $allowed_chars_len), 1);
    } // for
    
    return $pass;
  } // make_password
  
  // ---------------------------------------------------
  //  Input validation
  // ---------------------------------------------------
  
  /**
  * Check if selected email has valid email format
  *
  * @param string $user_email Email address
  * @return boolean
  */
  function is_valid_email($user_email) {
    $chars = EMAIL_FORMAT;
    if(strstr($user_email, '@') && strstr($user_email, '.')) {
    	return (boolean) preg_match($chars, $user_email);
    } else {
    	return false;
    } // if
  } // end func is_valid_email
  
  /**
  * Verify the syntax of the given URL.
  *
  * @param $url The URL to verify.
  * @return boolean
  */
  function is_valid_url($url) {
    if(str_starts_with(strtolower($url), 'http://localhost')) {
      return true;
    } // if
    return preg_match(URL_FORMAT, $url);
  } // end func is_valid_url 
  
  /**
  * This function will return true if $str is valid function name (made out of alpha numeric characters + underscore)
  *
  * @param string $str
  * @return boolean
  */
  function is_valid_function_name($str) {
    $check_str = trim($str);
    if($check_str == '') {
      return false; // empty string
    } // if
    
    $first_char = substr_utf($check_str, 0, 1);
    if(is_numeric($first_char)) {
      return false; // first char can't be number
    } // if
    
    return (boolean) preg_match("/^([a-zA-Z0-9_]*)$/", $check_str);
  } // is_valid_function_name
  
  /**
  * Check if specific string is valid hash. Lenght is not checked!
  *
  * @param string $hash
  * @return boolean
  */
  function is_valid_hash($hash) {
    return preg_match("/^([a-f0-9]*)$/", $hash);
  } // is_valid_hash
  
  /**
  * This function will return ID from array variables
  * 
  * Default settings will get 'id' variable from $_GET. If ID is not found function will return NULL. Variable name need 
  * to be valid PHP name and value in that field need to be numeric
  *
  * @param string $var_name Variable name. Default is 'id'
  * @param array $from Extract ID from this array. If NULL $_GET will be used
  * @param mixed $default Default value is returned in case of any error
  * @return integer
  */
  function get_id($var_name = 'id', $from = null, $default = null) {
    $var_name = trim($var_name);
    if($var_name == '') {
      return $default; // empty varname?
    } // if
    
    if(is_null($from)) {
      $from = $_GET;
    } // if
    
    if(!is_array($from)) {
      return $default; // $from is array?
    } // if
    if(!is_valid_function_name($var_name)) {
      return $default; // $var_name is valid?
    } // if
    
    $value = array_var($from, $var_name, $default);
    return is_numeric($value) ? (integer) $value : $default;
  } // get_id
  
  // ---------------------------------------------------
  //  Cleaning
  // ---------------------------------------------------
  
  /**
  * This function will return clean variable info
  *
  * @param mixed $var
  * @param string $indent Indent is used when dumping arrays recursivly
  * @param string $indent_close_bracet Indent close bracket param is used
  *   internaly for array output. It is shorter that var indent for 2 spaces
  * @return null
  */
  function clean_var_info($var, $indent = '&nbsp;&nbsp;', $indent_close_bracet = '') {
    if(is_object($var)) {
      return 'Object (class: ' . get_class($var) . ')';
    } elseif(is_resource($var)) {
      return 'Resource (type: ' . get_resource_type($var) . ')';
    } elseif(is_array($var)) {
      $result = 'Array (';
      if(count($var)) {
        foreach($var as $k => $v) {
          $k_for_display = is_integer($k) ? $k : "'" . clean($k) . "'";
          $result .= "\n" . $indent . '[' . $k_for_display . '] => ' . clean_var_info($v, $indent . '&nbsp;&nbsp;', $indent_close_bracet . $indent);
        } // foreach
      } // if
      return $result . "\n$indent_close_bracet)";
    } elseif(is_int($var)) {
      return '(int)' . $var;
    } elseif(is_float($var)) {
      return '(float)' . $var;
    } elseif(is_bool($var)) {
      return $var ? 'true' : 'false';
    } elseif(is_null($var)) {
      return 'NULL';
    } else {
      return "(string) '" . clean($var) . "'";
    } // if
  } // clean_var_info
  
  /**
  * Equivalent to htmlspecialchars(), but allows &#[0-9]+ (for unicode)
  * 
  * This function was taken from punBB codebase <http://www.punbb.org/>
  *
  * @param string $str
  * @return string
  */
  function clean($str) {
    //$str = preg_replace('/&(?!#[0-9]+;)/s', '&amp;', $str);
    $str = preg_replace('/&(?!#(?:[0-9]+|x[0-9A-F]+);?)/si', '&amp;', $str);
  	$str = str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $str);
  
  	return $str;
  } // clean
  
  /**
  * Convert entities back to valid characteds
  *
  * @param string $escaped_string
  * @return string
  */
  function undo_htmlspecialchars($escaped_string) {
    $search = array('&amp;', '&lt;', '&gt;');
    $replace = array('&', '<', '>');
    return str_replace($search, $replace, $escaped_string);
  } // undo_htmlspecialchars
  
  // ---------------------------------------------------
  //  Object handling function
  // ---------------------------------------------------
  
  /**
  * Populate object properties from array through setter
  * 
  * This function will loop through $array, prepare setter based on element key and 
  * if setter exists and is not protected it will be called with elements value as 
  * first parametar.
  *
  * @param object $object
  * @param array $array
  * @param array $protected_methods
  * @return null
  */
  function populate_through_setter($object, $array, $protected_methods = null) {
    if(!is_object($object)) {
      return;
    } // if
    
    if(is_foreachable($array)) {
      $object_methods = get_class_methods(get_class($object));
      $protected_methods = is_array($protected_methods) ? $protected_methods : array();
      
      foreach($array as $property_name => $property_value) {
        $setter = 'set' . Angie_Inflector::camelize($property_name);
        if(in_array($setter, $object_methods) && !in_array($setter, $protected_methods)) {
          $object->$setter($property_value);
        } // if
      } // foreahc
    } // if
  } // populate_through_setter
  
  // ---------------------------------------------------
  //  Array handling functions
  // ---------------------------------------------------
  
  /**
  * Is $var foreachable
  * 
  * This function will return true if $var is array and it is not empty
  *
  * @param mixed $var
  * @return boolean
  */
  function is_foreachable($var) {
    return is_array($var) && count($var);
  } // is_foreachable
  
  /**
  * Return variable from an array
  * 
  * If field $name does not exists in array this function will return $default
  *
  * @param array $from Hash
  * @param string $name
  * @param mixed $default
  * @return mixed
  */
  function array_var($from, $name, $default = null) {
    if(is_array($from)) {
      return isset($from[$name]) ? $from[$name] : $default;
    } elseif(is_object($from) && ($from instanceof ArrayAccess)) {
      return isset($from[$name]) ? $from[$name] : $default;
    } // if
    return $default;
  } // array_var
  
  /**
  * Flattens the array
  * 
  * This function will walk recursivly throug $array and all array values will be appended to $array and removed from
  * subelements. Keys are not preserved (it just returns array indexed form 0 .. count - 1)
  *
  * @param array $array If this value is not array it will be returned as one
  * @return array
  */
  function array_flat($array) {
    if(!is_array($array)) {
      return array($array);
    } // if
    
    $result = array();
    
    foreach($array as $value) {
      if(is_array($value)) {
        $value = array_flat($value);
        foreach($value as $subvalue) {
          $result[] = $subvalue;
        } // if
      } else {
        $result[] = $value;
      } // if
    } // if
    
    return $result;
  } // array_flat
  
  /**
  * This function will return $str as an array
  *
  * @param string $str
  * @return array
  */
  function string_to_array($str) {
    if(!is_string($str) || (strlen($str) == 0)) {
      return array();
    } // if
    
    $result = array();
    for($i = 0, $strlen = strlen($str); $i < $strlen; $i++) {
      $result[] = $str[$i];
    } // if
    
    return $result;
  } // string_to_array
  
  /**
  * Extract results of specific method from an array of objects
  * 
  * This method will go through all items of an $array and call $method. Results will be agregated into one array that 
  * will be returned. If $check_if_method_exists is set to true than additional checks will be done on the object 
  * (slower but safer). $check_if_method_exists is Off by default.
  * 
  * If $preserve_keys is true keys will be preserved in the resulting array...
  *
  * @param array $array
  * @param string $method
  * @param array $arguments
  * @param boolean $preserve_keys
  * @param boolean $check_if_method_exists
  * @return array
  */
  function objects_array_extract($array, $method, $arguments = null, $preserve_keys = false, $check_if_method_exists = false) {
    if(!is_array($array)) {
      return null;
    } // if
    
    $results = array();
    foreach($array as $key => &$element) {
      $call = array($element, $method);
      if(is_callable($call, false)) {
        if(is_array($arguments)) {
          $result = call_user_func_array($call, $arguments);
        } elseif(is_string($arguments)) {
          $result = call_user_func($call, $arguments);
        } else {
          $result = call_user_func($call);
        } // if
        
        if($preserve_keys) {
          $results[$key] = $result;
        } else {
          $results[] = $result;
        } // if
        
      } // if
    } // foreach
    return $results;
  } // objects_array_extract
  
  // ---------------------------------------------------
  //  Misc functions
  // ---------------------------------------------------
  
  /**
  * Show var dump. pre_var_dump() is used for testing only!
  *
  * @access public
  * @param mixed $var
  * @return null
  */
  function pre_var_dump($var) {
    print "<pre>\n";
    var_dump($var);
    print "</pre>\n";
  } // pre_var_dump
  
  /**
  * Return max upload size
  * 
  * This function will check for max upload size and return value in bytes. By default it will compare values of 
  * upload_max_filesize and post_max_size from php.ini, but it can also take additional values provided as arguments 
  * (for instance, if you store data in MySQL database one of the limiting factors can be max_allowed_packet 
  * configuration value). 
  * 
  * Examples:
  * <pre>
  * $max_size = get_max_upload_size(); // check only data from php.ini
  * $max_size = get_max_upload_size(12000, 18000); // take this values into calculation too
  * </pre>
  *
  * @param mixed
  * @return integer
  */
  function get_max_upload_size() {
    $arguments = func_get_args();
    if(!is_array($arguments)) {
      $arguments = array();
    } // if
    
    $arguments[] = php_config_value_to_bytes(ini_get('upload_max_filesize'));
    $arguments[] = php_config_value_to_bytes(ini_get('post_max_size'));
    
    $min = null;
    foreach($arguments as $argument) {
      if(is_null($min)) {
        $min = $argument;
      } else {
        $min = min($argument, $min);
      } // if
    } // if
    
    return $min;
  } // get_max_upload_size
  
  /**
  * Convert filesize value from php.ini to bytes
  * 
  * Convert PHP config value (2M, 8M, 200K...) to bytes. This function was taken from PHP documentation. $val is string 
  * value that need to be converted
  *
  * @param string $val
  * @return integer
  */
  function php_config_value_to_bytes($val) {
    $val = trim($val);
    $last = strtolower($val{strlen($val)-1});
    switch($last) {
      // The 'G' modifier is available since PHP 5.1.0
      case 'g':
        $val *= 1024;
      case 'm':
        $val *= 1024;
      case 'k':
        $val *= 1024;
    } // if
    
    return $val;
  } // php_config_value_to_bytes
  
  /**
  * This function will return request string relative to dispatch file
  *
  * @param void
  * @return stirng
  */
  function get_request_string() {
    return substr($_SERVER['REQUEST_URI'], strlen(dirname($_SERVER['PHP_SELF'])));
  } // get_request_string
  
  /**
  * Compare $value1 and $value2 with $comparision and return boolean result
  * 
  * Examples:
  * <pre>
  * is_true_statement(1, COMPARE_EQ, 1); // true
  * is_true_statement(1, COMPARE_EQ, 3); // false
  * </pre>
  *
  * @param mixed $value1
  * @param string $comparision
  * @param mixed $value2
  * @return boolean
  */
  function is_true_statement($value1, $comparision = COMPARE_EQ, $value2) {
    switch($comparision) {
      case COMPARE_LT:
        if($value1 < $value2) {
          return true;
        } // if
        break;
      case COMPARE_LE:
        if($value1 <= $value2) {
          return true;
        } // if
        break;
      case COMPARE_GT:
        if($value1 > $value2) {
          return true;
        } // if
        break;
      case COMPARE_GE:
        if($value1 >= $value2) {
          return true;
        } // if
        break;
      case COMPARE_EQ:
        if($value1 == $value2) {
          return true;
        } // if
        break;
      case COMPARE_NE:
        if($value1 != $value2) {
          return true;
        } // if
        break;
    } // switch
    return false;
  } // is_true_statement

?>