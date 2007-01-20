<?= '<?php' ?>

  // ---------------------------------------------------
  //  Init
  // ---------------------------------------------------

  define('ANGIE_ENVIRONMENT', 'console');
  
  require realpath(dirname(__FILE__) . '/../../init.php'); // init project
  
  set_exception_handler('console_exception_handler');
  
  // ---------------------------------------------------
  //  Welcome
  // ---------------------------------------------------

  print "\n";
  print " Welcome to Angie console tool.\n Type in 'help' for more information.";
  print "\n";
  
  // ---------------------------------------------------
  //  Do
  // ---------------------------------------------------
  
  $php = '';
  do {
    
    // Read available commands. Array of command is read on every execution just in case. Angie will cache the array 
    // after the first run
    $available_commands = Angie::getAvailableCommands();
    
    // If PHP command does not start with any of this keywords we'll prefix a command with return so we can
    // output the commands result
    $invalid_starts = array('return', 'unset', 'print', 'if', 'foreach', 'for', 'while', 'do', 'throw');
    
    if($php) {
      print "\nangie+> ";
    } else {
      print "\nangie> ";
    } // if
    $peaces = explode(' ', trim(fgets(STDIN)));
    $command = array_var($peaces, 0);
    
    if($php && ($command <> '?>')) {
      if(trim(array_var($peaces, 0))) {
        $for_eval = trim(implode(' ', $peaces));
        
        $return = true;
        foreach($invalid_starts as $start) {
          if(str_starts_with($for_eval, $start)) {
            $return = false;
          } // if
        } // foreach
        
        $for_eval = $return ? 'return ' . $for_eval : $for_eval;
        
        if(!str_ends_with($for_eval, ';')) {
          $for_eval .= ';';
        } // if
        
        ob_start();
        $reply = eval($for_eval);
        if(is_object($reply)) {
          print 'Object (' . get_class($reply) . ')';
        } else {
          var_dump($reply);
        } // if
        print trim(ob_get_clean());
      } else {
        print 'Nothing to execute';
      } // if
    } else {
      if($command == 'exit') {
        break;
      } elseif($command == '<?= '<?php' ?>') {
        $php = true;
        print 'PHP mode: On';
      } elseif($command == '<?= '?>' ?>') {
        $php = false;
        print 'PHP mode: Off';
      } elseif(in_array($command, array_keys($available_commands))) {
        require_once array_var($available_commands, $command);
        
        try {
          $handler = Angie::constructCommandHandler($command);
          $handler = Angie_Console::prepareCommand($handler, array_slice($peaces, 1));
          if($handler->getOption('h', 'help')) {
            print $handler->defineHelp();
          } elseif($handler->isQuiet()) {
            $handler->execute(new Angie_Output_Silent());
          } else {
            $handler->execute(new Angie_Output_Console());
          } // if
        } catch(Exception $e) {
          print "Command error:\n\n" . $e->getMessage() . ". Trace:\n\n" . $e->getTraceAsString();
        } // if
        
      } else {
        print "Command '$command' not recognized";
      } // if
    } // if
    print "\n";
  } while(1);
  die("\n Bye\n");

<?= '?>' ?>