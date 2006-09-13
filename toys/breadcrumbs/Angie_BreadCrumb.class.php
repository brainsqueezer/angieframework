<?php

  /**
  * Single bread crumb
  *
  * @package Angie.toys
  * @subpackage breadcrumbs
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_BreadCrumb {
    
    /**
    * Crumb title. This value is required
    *
    * @var string
    */
    private $title;
    
    /**
    * Crumb URL, optional
    *
    * @var string
    */
    private $url;
    
    /**
    * Array of crumb attributes. Can hold class, javascript events etc
    *
    * @var array
    */
    private $attributes;
  
    /**
    * Construct the BreadCrumb
    *
    * @param string $title Crumb title, required
    * @param string $url Crumb URL, optional
    * @param string $attributes Additional crumb attributes like class etc. Optional
    * @return BreadCrumb
    */
    function __construct($title, $url = null, $attributes = null) {
      $this->setTitle($title);
      $this->setURL($url);
      $this->setAttributes($attributes);
    } // __construct
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get title
    *
    * @param null
    * @return string
    */
    function getTitle() {
      return $this->title;
    } // getTitle
    
    /**
    * Set title value
    *
    * @param string $value
    * @return null
    */
    function setTitle($value) {
      $this->title = $value;
    } // setTitle
    
    /**
    * Get URL
    *
    * @param null
    * @return string
    */
    function getURL() {
      return $this->url;
    } // getURL
    
    /**
    * Set URL value
    *
    * @param string $value
    * @return null
    */
    function setURL($value) {
      $this->url = $value;
    } // setURL
    
    /**
    * Get attributes
    *
    * @param null
    * @return array
    */
    function getAttributes() {
      return $this->attributes;
    } // getAttributes
    
    /**
    * Set attributes value
    *
    * @param array $value
    * @return null
    */
    function setAttributes($value) {
      $this->attributes = $value;
    } // setAttributes
  
  } // Angie_BreadCrumb

?>