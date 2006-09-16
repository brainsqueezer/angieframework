<?php

  /**
  * This file contains set of helpers that make work with BreadCrumbs toy a bit easier
  *
  * @package Angie.controller
  * @subpackage helpers
  * @author Ilija Studen <ilija.studen@gmail.com>
  */

  /**
  * Return all crumbs
  *
  * @param void
  * @return array
  */
  function bread_crumbs() {
    return Angie_BreadCrumbs::getCrumbs();
  } // bread_crumbs
  
  /**
  * Add single bread crumb to the list
  *
  * @param string $title Crumb title, required
  * @param string $url Crumb URL, optional
  * @param string $attributes Additional crumb attributes like class etc. Optional
  * @return Angie_BreadCrumb
  */
  function add_bread_crumb($title, $url = null, $attributes = null) {
    return Angie_BreadCrumbs::addCrumb(new Angie_BreadCrumb($title, $url, $attributes));
  } // add_bread_crumb
  
  /**
  * Add multiple breadcrumbs to the list. There are two possible sets of values:
  * 
  * 1. First (and only) param is Angie_BreadCrumb object that need to be added
  * 2. Three params - title, url and additional attributes. Only title is required
  *
  * @param
  * @return null
  */
  function add_bread_crumbs() {
    Angie_BreadCrumbs::addByFunctionArguments(func_get_args());
  } // add_bread_crumbs

?>