<?php

  /**
  * Print name and version of the framework
  *
  * @package Angie.bin
  * @subpackage commands
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  if(isset($return_help) && $return_help) {
    return 'Display framework version';
  } // if

  print 'Angie ' . ANGIE_VERSION . "\n";
  
?>