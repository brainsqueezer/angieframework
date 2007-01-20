<?= '<?php' ?>

  /**
  * Application level functions file
  * 
  * This file is automatically included on initialization so you can all the functions you need on your project here. 
  * By default this file has several system function that you can tailor to best fit your needs.
  *
  * @package <?= $project_name ?>.application
  * @author Ilija Studen <ilija.studen@gmail.com>
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
	  if(!isset($GLOBALS['autoloader_classes']) && is_file($autoloader_file = Angie::engine()->getCachePath('autoloader.php'))) {
	    require_once $autoloader_file;
	  } // if
	  
	  $class_name = strtoupper($load_class_name);;
	  
	  if(isset($GLOBALS['autoloader_classes'][$class_name])) {
	    return require_once $GLOBALS['autoloader_classes'][$class_name];
	  } // if
	  
	  if($load_class_name == 'Doctrine') {
	    return require_once ANGIE_PATH . '/vendor/doctrine/Doctrine.php';
	  } // if
	  
	  if(str_starts_with($load_class_name, 'Doctrine')) {
	    return Doctrine::autoload($load_class_name);
	  } // if
	  
	  $error = new Angie_Error("Failed to autoload '$load_class_name' class");
	} // __autoload
	
	// ---------------------------------------------------
	//  Project wide functions
	// ---------------------------------------------------
	
	/**
  * Return owner company object if we are on company website and it is loaded
  *
  * @param void
  * @return Company
  */
  function owner_company() {
    return Angie::engine()->getCompany();
  } // owner_company
  
  /**
  * Return logged user if we are on company website
  *
  * @param void
  * @return User
  */
  function logged_user() {
    return Angie::engine()->getLoggedUser();
  } // logged_user
  
  /**
  * Return active project if we are on company website
  *
  * @param void
  * @return Project
  */
  function active_project() {
    return Angie::engine()->getSelectedProject();
  } // active_project

<?= '?>' ?>