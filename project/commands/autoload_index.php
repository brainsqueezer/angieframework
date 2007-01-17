<?php

  class Angie_Command_AutoloadIndex extends Angie_Console_GeneratorCommand {
    
    /**
    * Execute this command
    *
    * @param Angie_Output $output
    * @return null
    */
    function execute(Angie_Output $output) {
      $loader = new Angie_AutoLoader();
      
			$loader->addDir(ROOT_PATH, 'ROOT_PATH');
			$loader->addDir(ANGIE_PATH, 'ANGIE_PATH');
			
			$loader->addToIgnoreList(ANGIE_PATH . '/bin');
			$loader->addToIgnoreList(ANGIE_PATH . '/tests');
			$loader->addToIgnoreList(ANGIE_PATH . '/vendor');
			
			$loader->addToIgnoreList(Angie::engine()->getDevelopmentPath());
			$loader->addToIgnoreList(Angie::engine()->getPublicPath());
			$loader->addToIgnoreList(Angie::engine()->getVendorPath());
			
			$loader->setIndexFilename(Angie::engine()->getCachePath('autoloader.php'));
			
			try {
			  $loader->createCache();
			  $output->printMessage('Autoload index created successfully');
			} catch(Exception $e) {
			  $output->printMessage('Failed to create autoload index');
			  throw new $e;
			} // try
    } // execute
  
    /**
    * Return options definition array
    * 
    * Single element in options definition array consists of three elements. 
    * First element is a short option (one letter plus optional colon saying 
    * that this option requires an argument), long option name with option colon 
    * and help
    *
    * @param void
    * @return array
    */
    function defineOptions() {
      return array(
        array('q', 'quiet', 'Don\'t print progress messages to the console'),
        array('h', 'help', 'Show help')
      ); // array
    } // defineOptions
    
    /**
    * Return command description
    *
    * @param void
    * @return string
    */
    function defineDescription() {
      return 'Rebuild autoloader index';
    } // defineDescription
  
  } // Angie_Command_AutoloadIndex

?>