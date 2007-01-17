<?php
	
	/**
	* Fancy class loader which uses an index
	* 
	* This class is based on SmartLoader
	*
	* @package Angie.toys
	* @author Ilija Studen <ilija.studen@gmail.com>
	*/
	class Angie_AutoLoader {
	  
		/**
		* mod settings for the index file
		*/
		const INDEX_FILE_MOD = 0775;
		
		/**
		* Name of the $GLOBALS var where we'll store class names
		*
		* @var string
		*/
		const GLOBAL_VAR = 'autoloader_classes';
		
		/**
		* Filename of index file
		*
		* @var string
		*/
		private $index_filename = 'autoloader_index.php';
		
		/**
		* Array of directory paths that need to be parsed
		*
		* @var array
		*/
		private $parse_directories = array();
		
		/**
		* Array of directories that need to be ignored
		*
		* @var array
		*/
		private $ignore_directories = array();
		
		/**
		* Extension of files that need to be scaned
		*
		* @var string
		*/
		private $scan_file_extension = 'class.php';
		
		/**
		* Ignore hidden and system files
		*
		* @var boolean
		*/
		private $ignore_hidden_files = true;
		
		/**
		* Cached array of parsed directories, keep it from endless loop
		* 
		* @var array
		*/
		private $parsed_directories = array();
		
		/**
		* Class index
		*
		* @var array
		*/
		private $class_index = array();
		
		// ---------------------------------------------------
		//  Doers
		// ---------------------------------------------------
		
		/**
		* Loads a class by its name
		*
		* @param string $class_name
		* @return boolean
		* @throws Angie_Error
		* @throws Angie_FileSystem_Error_FileDnx
		*/
		public function loadClass($load_class_name) {
			static $retrying = false; // is this our second loading attempt?
			
			$class_name = strtoupper($load_class_name);
			
			/* Recreate the index file, if outdated */
			if(!isset($GLOBALS[self::GLOBAL_VAR])) {
				if($retrying || !is_readable($this->getIndexFilename())) {
					$this->createCache();
					if(!is_readable($this->getIndexFilename())) {
					  throw new Angie_FileSystem_Error_FileDnx('SmartLoader index file "' . $this->getIndexFilename() . '" is not readable!');
					} // if
				} // if
				include $this->getIndexFilename();
			} // if
			
			/* include the needed file or retry on failure */
			if(isset($GLOBALS[self::GLOBAL_VAR][$class_name])) {
				if(@include($GLOBALS[self::GLOBAL_VAR][$class_name])) {
					return true;
				} else {
					if($retrying) {
						throw new Angie_Error('Class file "' . $GLOBALS[self::GLOBAL_VAR][$class_name] . '" for class "'.$class_name.'" cannot be included!');
						return false;
					} // if
				} // if
			} elseif($retrying) {
				/* we failed while retrying. this is bad. */
				throw new Angie_Error('Could not find class file for "' . $class_name . '"');
				return false;
			} // if
			
			/* including failed. try again. */
			unset($GLOBALS[self::GLOBAL_VAR]);
			$retrying = true;
			return $this->loadClass($class_name);
		} // loadClass
		
		/**
		* - Scans the class dirs for class/interface definitions and 
		* 	creates an associative array (class name => class file) 
		* - Generates the array in PHP code and saves it as index file
		*
		* @param param_type $param_name
		* @throws Exception
		*/
		function createCache() {
		  if(is_foreachable($this->parse_directories)) {
		    foreach($this->parse_directories as $path_constant => $path) {
		      $this->parseDir($path, $path_constant);
		    } // foreach
		  } // if
			return $this->createIndexFile();
		} // createCache
		
		/**
		* Write out to the index file
		*
		* @throws Angie_Error
		*/
		private function createIndexFile() {
			/* generate php index file */
			$index_content = "<?php\n";
			
			foreach($this->class_index as $path_constant => $files) {
			  if(is_foreachable($files)) {
			    foreach($files as $class_name => $class_file) {
			      if(str_starts_with($class_file, $this->parse_directories[$path_constant])) {
			        $actual_path = $path_constant . " . " . var_export(substr($class_file, strlen($this->parse_directories[$path_constant])), true);
			      } else {
			        $actual_path = var_export($class_file, true);
			      } // if
			      
			      $index_content .= "\t\$GLOBALS['" . self::GLOBAL_VAR . "'][". var_export(strtoupper($class_name), true) . "] = " . $actual_path . ";\n";
			    } // foreach
			  } // if
			} // foreach
			
			$index_content .= "?>";
			if(!@file_put_contents($this->getIndexFilename(), $index_content)) {
				throw new Angie_Error('Could not write to "'.$this->getIndexFilename().'". Make sure, that your webserver has write access to it.');
			} // if
			
			// Apply mod rights
			@chmod($this->getIndexFilename(), self::INDEX_FILE_MOD);
		} // createIndexFile
		
		/**
		* Parses a directory for class/interface definitions. Saves found definitions
		* in $classIndex
		*
		* @param string $directory_path
		* @throws Exception
		* @return boolean Success
		*/
		private function parseDir($directory_path, $directory_constant) {
		  if(in_array(with_slash($directory_path), $this->ignore_directories)) {
		    return;
		  } // if
		  
		  $directory_path = with_slash($directory_path);
		  if(in_array($directory_path, $this->parsed_directories)) {
		    return;
		  } else {
		    $this->parsed_directories[] = $directory_path;
		  } // if
		  
		  if(!isset($this->class_index[$directory_constant])) {
		    $this->class_index[$directory_constant] = array();
		  } // if
		  
		  $dir = dir($directory_path);
		  while (false !== ($entry = $dir->read())) {
		    if($entry == '.' || $entry == '..') {
		      continue;
		    } // if
		    
		    $path = $directory_path . $entry;
		    if(is_dir($path)) {
		      if($this->getIgnoreHiddenFiles() && ($entry[0] == '.')) {
		        continue;
		      } // if
		      if(!is_readable($path)) {
		        continue;
		      } // if
		      $this->parseDir($path, $directory_constant);
		    } elseif(is_file($path)) {
		      if(!is_readable($path)) {
		        continue;
		      } // if
		      if(str_ends_with($path, $this->getScanFileExtension())) {
		        $this->parseFile($path, $directory_constant);
		      } // if
		    } // if
		  } // if
		  $dir->close();
		} // parseDir
		
		/**
		* Parse a file for PHP classes and add them to our classIndex
		*
		* @access private
		* @param string path to file
		* @throws Exception
		*/
		private function parseFile($path, $path_constant) {
			if(!$buf = @file_get_contents($path)) {
			  throw new Exception('Couldn\'t read file contents from "'.$path.'".');
			} // if
			
			/* searching for classes */
			//if(preg_match_all("%(interface|class)\s+(\w+)\s+(extends\s+(\w+)\s+)?(implements\s+\w+\s*(,\s*\w+\s*)*)?{%im", $buf, $result)) {
			if(preg_match_all('%(interface|class)\s+(\w+)\s+(extends\s+(\w+)\s+)?(implements\s+\w+\s*(,\s*\w+\s*)*)?{%', $buf, $result)) {
			  if(isset($result[2]) && is_foreachable($result[2])) {
  				foreach($result[2] as $class_name) {
  				  $this->class_index[$path_constant][$class_name] = str_replace('\\', '/', $path);
  				} // foreach
			  } // if
			} // if
		} // parseFile
		
		// ---------------------------------------------------
		//  Gettes and setters
		// ---------------------------------------------------
		
		/**
		* Add directory that need to be scaned
		*
		* @param stirng $path Direcotry path
		* @param string $path_constant
		* @return null
		*/
		function addDir($path, $path_constant) {
		  if(is_dir($path)) {
		    $this->parse_directories[$path_constant] = $path;
		  } // if
		} // addDir
		
		/**
		* Add a path to ignore list
		*
		* @param string $path
		* @return null
		*/
		function addToIgnoreList($path) {
		  $add_path = with_slash($path);
		  if(!in_array($add_path, $this->ignore_directories)) {
		    $this->ignore_directories[] = $add_path;
		  } // if
		} // addToIgnoreList
		
		/**
		* Get index_filename
		*
		* @access public
		* @param null
		* @return string
		*/
		function getIndexFilename() {
		  return $this->index_filename;
		} // getIndexFilename
		
		/**
		* Set index_filename value
		*
		* @access public
		* @param string $value
		* @return null
		*/
		function setIndexFilename($value) {
		  $this->index_filename = $value;
		} // setIndexFilename
		
		/**
		* Get scan_file_extension
		*
		* @access public
		* @param null
		* @return string
		*/
		function getScanFileExtension() {
		  return $this->scan_file_extension;
		} // getScanFileExtension
		
		/**
		* Set scan_file_extension value
		*
		* @access public
		* @param string $value
		* @return null
		*/
		function setScanFileExtension($value) {
		  $this->scan_file_extension = $value;
		} // setScanFileExtension
		
		/**
		* Get ignore_hidden_files
		*
		* @access public
		* @param null
		* @return boolean
		*/
		function getIgnoreHiddenFiles() {
		  return $this->ignore_hidden_files;
		} // getIgnoreHiddenFiles
		
		/**
		* Set ignore_hidden_files value
		*
		* @access public
		* @param boolean $value
		* @return null
		*/
		function setIgnoreHiddenFiles($value) {
		  $this->ignore_hidden_files = $value;
		} // setIgnoreHiddenFiles
		
	} // Angie_AutoLoader
	
?>