<?php

  /**
  * All web related functions - content forwarding, redirections, header manipulation etc
  *
  * @package Angie
  * @subpackage functions
  * @author Ilija Studen <ilija.studen@gmail.com>
  */

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

?>