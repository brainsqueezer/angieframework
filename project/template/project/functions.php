<?= '<?php' ?>


  /**
  * Application level functions file
  * 
  * This file is automatically included on initialization so you can all the functions you need on your project here. 
  * By default this file has several system function that you can tailor to best fit your needs.
  *
  * @package <?= $project_name ?>.application
  */
  
  /**
  * Return ID of logged user
  *
  * @param void
  * @return integer
  */
  function get_logged_user_id() {
    
    // If your application is using some kind of user authetication implement this function so it returns user ID of 
    // logged in user so you can take advantage of some built in auto setters for model (created_by_id and 
    // updated_by_id will be automatically populated for you)
    
    return 0;
  } // get_logged_user_id

  /**
	* Gets called, when an undefined class is being instanciated
	* 
	* @param_string $load_class_name
	* @return null
	*/
	function __autoload($load_class_name) {
		static $loader = null;
		$class_name = strtoupper($load_class_name);
		
		// Try to get this data from index...
		if(isset($GLOBALS[Angie_AutoLoader::GLOBAL_VAR])) {
		  if(isset($GLOBALS[Angie_AutoLoader::GLOBAL_VAR][$class_name])) {
		    return include $GLOBALS[Angie_AutoLoader::GLOBAL_VAR][$class_name];
		  } // if
		} // if
		
		if(!$loader) {
			$loader = new Angie_AutoLoader();
			$loader->addDir(ROOT_PATH);
			$loader->addDir(ANGIE_PATH);
			$loader->setIndexFilename(CACHE_PATH . '/autoloader.php');
		} // if
		
		try {
			$loader->loadClass($class_name);
		} catch(Angie_Error $e) {
			print '<pre align="left">' . $e->__toString() . '</pre>';
			die();
		} // try
	} // __autoload

<?= '?>' ?>