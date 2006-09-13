<?php

  /**
  * This file contains helpers that are useful when you are working with HTML pages - this 
  * helpers use Angie_PageConstruction class and know where to find stylesheets, images 
  * and other assets and how to link them up with the page that need to be rendered
  *
  * @package Angie.controller
  * @subpackage helpers
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  // ---------------------------------------------------
  //  Page construction helpers
  // ---------------------------------------------------
    
  /**
  * Return page title
  *
  * @param void
  * @return string
  */
  function get_page_title() {
    $page_title = Angie_PageConstruction::getTitle();
    return trim($page_title) == '' ? $page_title : PROJECT_NAME;
  } // get_page_title
  
  /**
  * Set page title
  *
  * @param string $value
  * @return null
  */
  function set_page_title($value) {
    Angie_PageConstruction::setTitle($value);
  } // set_page_title
  
  /**
  * Add external stylesheet file to page
  *
  * @param string $href
  * @param string $title
  * @param string $media
  * @return null
  */
  function add_stylesheet_to_page($href, $title = null, $media = null) {
    if(!is_valid_url($href)) {
      $href = get_stylesheet_url($href);
    } // if
    
    if(trim($media) == '') {
      $media = 'all';
    } // if
    
    Angie_PageConstruction::addLink($href, 'rel', 'stylesheet', array('title' => $title, 'media' => $media));
  } // add_stylesheet_to_page
  
  /**
  * Add external JS to page
  *
  * @param string $src URL of external JS file
  * @return null
  */
  function add_javascript_to_page($src) {
    if(!is_valid_url($src)) {
      $src = get_javascript_url($src);
    } // if
    
    Angie_PageConstruction::addScript($src);
  } // add_javascript_to_page

?>