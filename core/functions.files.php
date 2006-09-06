<?php

  /**
  * General set of functions for file handling
  *
  * @package Angie
  * @subpackage core.functions
  * @author Ilija Studen <ilija.studen@gmail.com>
  */

  /**
  * Check if specific folder is writable. 
  * 
  * is_writable() function has problems on Windows because it does not really 
  * checks for ACLs; it checks just the value of Read-Only property and that 
  * is incorect on some Windows installations.
  * 
  * This function will actually try to create (and delete) a test file in order
  * to check if folder is really writable
  *
  * @param string $path
  * @return boolean
  */
  function folder_is_writable($path) {
    if(!is_dir($path)) {
      return false;
    } // if
    
    do {
      $test_file = with_slash($path) . sha1(uniqid(rand(), true));
    } while(is_file($test_file));
    
    $put = @file_put_contents($test_file, 'test');
    if($put === false) {
      return false;
    } // if
    
    @unlink($test_file);
    return true;
  } // folder_is_writable
  
  /**
  * Check if specific file is writable
  * 
  * This function will try to open target file for writing (just open it!) in order to
  * make sure that this file is really writable. There are some known problems with
  * is_writable() on Windows (see description of folder_is_writable() function for more 
  * details).
  * 
  * @see folder_is_writable() function
  * @param string $path
  * @return boolean
  */
  function file_is_writable($path) {
    if(!is_file($path)) {
      return false;
    } // if
    
    $open = @fopen($path, 'a+');
    if($open === false) {
      return false;
    } // if
    
    @fclose($open);
    return true;
  } // file_is_writable
  
  /**
  * Return the files from specific directory. This function can filter result
  * by file extension (accepted param is single extension or array of extensions)
  *
  * @example get_files($dir, array('doc', 'pdf', 'xst'))
  *
  * @param string $dir Dir that need to be scaned
  * @param mixed $extension Singe or multiple file extensions that need to be
  *   mached. If null no check is performed...
  * @param boolean $recursive Walk recursivlly through directory and return all files
  *   that match the extension
  * @return array
  */
  function get_files($dir, $extension = null, $recursive = false) {
    if(!is_dir($dir)) {
      return false;
    } // if
    
  	$dir = with_slash($dir);
  	if(!is_null($extension)) {
  	  if(is_array($extension)) {
  	    foreach($extension as $k => $v) {
  	      $extension[$k] = strtolower($v);
  	    } // foreach
  	  } else {
  	    $extension = strtolower($extension);
  	  } // if
  	} // if
  	
		$d = dir($dir);
		$files = array();
		
		while(($entry = $d->read()) !== false) {
		  if(str_starts_with($entry, '.')) {
		    continue;
		  } // if
		  
	    $path = $dir . $entry;
	    
	    if(is_file($path)) {
	    	if(is_null($extension)) {
	    	  $files[] = $path;
	    	} else {
	    		if(is_array($extension)) {
	    		  if(in_array(strtolower(get_file_extension($path)), $extension)) {
	    		    $files[] = $path;
	    		  } // if
	    		} else {
	    		  if(strtolower(get_file_extension($path)) == $extension) {
	    		    $files[] = $path;
	    		  } // if
	    		} // if
	    	} // if
	    } elseif(is_dir($path)) {
	      if($recursive) {
	        $subfolder_files = get_files($path, $extension, true);
	        if(is_array($subfolder_files)) {
	          $files = array_merge($files, $subfolder_files);
	        } // if
	      } // if
	    } // if
		  
		} // while
		
		$d->close();
		return count($files) > 0 ? $files : null;
  } // get_files
  
  /**
  * Return file extension from specific filename. Examples:
  * 
  * get_file_extension('index.php') -> returns 'php'
  * get_file_extension('index.php', true) -> returns '.php'
  * get_file_extension('Blog.class.php', true) -> returns '.php'
  *
  * @param string $path File path
  * @param boolean $leading_dot Include leading dot
  * @return string
  */
  function get_file_extension($path, $leading_dot = false) {
  	$filename = basename($path);
  	$dot_offset = (boolean) $leading_dot ? 0 : 1;
  	
    if( ($pos = strrpos($filename, '.')) !== false ) {
      return substr($filename, $pos + $dot_offset, strlen($filename));
    } // if
    
    return '';
  } // get_file_extension
  
  /**
  * Walks recursively through directory and calculates its total size - returned in bytes
  *
  * @param string $dir Directory
  * @return integer
  */
  function dir_size($dir) {
  	$totalsize = 0;
  	
  	if($dirstream = @opendir($dir)) {
  		while(false !== ($filename = readdir($dirstream))) {
  			if(($filename != '.') && ($filename != '..')) {
  				$path = with_slash($dir) . $filename;
  				if (is_file($path)) $totalsize += filesize($path);
  				if (is_dir($path)) $totalsize += dir_size($path);
  			} // if
  		} // while
  	} // if
  	
  	closedir($dirstream);
  	return $totalsize;
  } // end func dir_size
  
  /**
  * Remove specific directory
  *
  * @param string $dir Directory path
  * @return boolean
  */
  function delete_dir($dir) {
  	$dh = opendir($dir);
  	while($file = readdir($dh)) {
  		if(($file != ".") && ($file != "..")) {
  			$fullpath = $dir . "/" . $file;
  			
  			if(!is_dir($fullpath)) {
  				unlink($fullpath);
  			} else {
  				delete_dir($fullpath);
  			} // if
  		} // if
  	} // while

  	closedir($dh);
  	return rmdir($dir) ? true : false;
  } // end func delete_dir
  
  /**
  * Force creation of all dirs
  *
  * @access public
  * @param void
  * @return null
  */
  function force_mkdir($path, $chmod = null) {
    if(is_dir($path)) {
      return true;
    } // if
    
    $real_path = str_replace('\\', '/', $path);
    $parts = explode('/', $real_path);
    
    $forced_path = '';
    foreach($parts as $part) {
      
      // Skip first on windows
      if($forced_path == '') {
        $start = substr(__FILE__, 0, 1) == '/' ? '/' : '';
        $forced_path = $start . $part;
      } else {
        $forced_path .= '/' . $part;
      } // if
      
      if(!is_dir($forced_path)) {
        if(!is_null($chmod)) {
          if(!mkdir($forced_path)) {
            return false;
          } // if
        } else {
          if(!mkdir($forced_path, $chmod)) {
            return false;
          } // if
        } // if
      } // if
    } // foreach
    
    return true;
  } // force_mkdir
  
  /**
  * This function will return true if $dir_path is empty
  *
  * @param string $dir_path
  * @return boolean
  */
  function is_dir_empty($dir_path) {
		$d = dir($dir_path);
    if($d) {
  		while(false !== ($entry = $d->read())) {
  		  if(($entry == '.') || ($entry == '..')) {
  		    continue;
  		  } // if
  		  return false;
  		} // while
		} // if
		return true;
  } // is_dir_empty

?>