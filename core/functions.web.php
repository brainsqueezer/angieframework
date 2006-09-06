<?php

  /**
  * All web related functions - content forwarding, redirections, header manipulation etc
  *
  * @package Angie
  * @subpackage core.functions
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  /**
  * Redirect to specific URL (header redirection). 
  * 
  * Usually URLs passed to this function are escaped so they can be printed in templates and 
  * not break the validator (&amp; problem) so this functions undo htmlspecialchars() first
  *
  * @param string $to Redirect to this URL
  * @param boolean $die Die when finished
  * @return void
  */
  function redirect_to($to, $die = true) {
  	$to = undo_htmlspecialchars($to);
    header('Location: ' . $to);
    if($die) {
      die();
    } // if
  } // end func redirect_to
  
  /**
  * Redirect to referer
  *
  * @access public
  * @param string $alternative Alternative URL is used if referer is not valid URL
  * @return null
  */
  function redirect_to_referer($alternative = null) {
    $referer = get_referer();
    if(is_valid_url($referer)) {
      redirect_to($referer);
    } else {
      redirect_to($alternative);
    } // if
  } // redirect_to_referer
  
  /**
  * Return referer URL
  *
  * @param string $default This value is returned if referer is not found or is empty
  * @return string
  */
  function get_referer($default = null) {
    return array_var($_SERVER, 'HTTP_REFERER', $default);
  } // get_referer

  /**
  * Forward specific file to the browser as a stream of data. Download can be forced 
  * (dispolition: attachment) or passed inline
  *
  * @param string $path File path
  * @param string $type Serve file as this type
  * @param string $name If set use this name, else use filename (basename($path))
  * @param boolean $force_download Force download (add Disposition => attachement)
  * @return boolean
  */
  function download_file($path, $type = 'application/octet-stream', $name = '', $force_download = false) {
    if(!is_readable($path)) {
      return false;
    } // if
    
    $filename = trim($name) == '' ? basename($path) : trim($name);
    return download_contents(file_get_contents($path), $type, $filename, filesize($path), $force_download);
  } // download_file
  
  /**
  * Use content (from file, from database, other source...) and pass it to the browser as a file
  *
  * @param string $content
  * @param string $type MIME type
  * @param string $name File name
  * @param integer $size File size
  * @param boolean $force_download Send Content-Disposition: attachment to force save dialog
  * @todo Make sure that this function works properly. It was a long time since it was written
  *   and it was hacked together to work because of the short deadline
  * @return boolean
  */
  function download_contents($content, $type, $name, $size, $force_download = false) {
    if(connection_status() != 0) {
      return false; // check connection
    } // if
    
    if($force_download) {
      header("Cache-Control: public");
    } else {
      header("Cache-Control: no-store, no-cache, must-revalidate");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
    } // if
    header("Expires: " . gmdate("D, d M Y H:i:s", mktime(date("H") + 2, date("i"), date("s"), date("m"), date("d"), date("Y"))) . " GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Content-Type: $type");
    header("Content-Length: " . (string) $size);
    
    // Prepare disposition
    $disposition = $force_download ? 'attachment' : 'inline';
    header("Content-Disposition: $disposition; filename=\"" . $name) . "\"";
    header("Content-Transfer-Encoding: binary");
    print $content;
    
    return((connection_status() == 0) && !connection_aborted());   
  } // download_contents
  
  /**
  * This function will strip slashes if magic quotes is enabled so 
  * all input data ($_GET, $_POST, $_COOKIE) is free of slashes
  *
  * @access public
  * @param void
  * @return null
  */
  function fix_input_quotes() {
    if(get_magic_quotes_gpc()) {
      array_stripslashes($_GET);
      array_stripslashes($_POST);
      array_stripslashes($_COOKIE);
    } // if
  } // fix_input_quotes
  
  /**
  * This function will walk recursivly thorugh array and strip slashes from scalar values
  *
  * @param array $array
  * @return null
  */
  function array_stripslashes(&$array) {
    if(!is_array($array)) return;
    foreach($array as $k => $v) {
      if(is_array($array[$k])) {
        array_stripslashes($array[$k]);
      } else {
        $array[$k] = stripslashes($array[$k]);
      } // if
    } // foreach
    return $array;
  } // array_stripslashes

?>