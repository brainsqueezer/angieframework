<?php

  /**
  * Page construction is a wrapper that let users put all meta data about spcific HTML 
  * document - meta information, links, scripts, title etc
  *
  * @package Angie.toys
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  final class Angie_PageConstruction {
    
    /** Link types **/
    const LINK_TYPE_REL = 'rel';
    const LINK_TYPE_REV = 'rev';
    
    /**
    * Page title
    *
    * @var string
    */
    static private $title;
  
    /**
    * Array of rendered meta tags
    *
    * @var array
    */
    static private $meta = array();
    
    /**
    * Array of rendered link tags
    *
    * @var array
    */
    static private $links = array();
    
    /**
    * Array of rendered script tags
    *
    * @var array
    */
    static private $scripts = array();
    
    /**
    * Array of page fragments that templates can set and share
    *
    * @var array
    */
    static private $framgents = array();
    
    // ---------------------------------------------------
    //  General methods
    // ---------------------------------------------------
    
    /**
    * Get title
    *
    * @param null
    * @return string
    */
    static function getTitle() {
      return self::$title;
    } // getTitle
    
    /**
    * Set title value
    *
    * @param string $value
    * @return null
    */
    static function setTitle($value) {
      self::$title = $value;
    } // setTitle
    
    /**
    * Return array of meta tags
    *
    * @param void
    * @return array
    */
    static function getMetaTags() {
      return self::$meta;
    } // getMetaTags
    
    /**
    * Set value of specific meta attribute
    *
    * @param string $name Name of the meta property
    * @param string $content
    * @param boolean $http_equivalent
    * @return void
    */
    static function setMetaTag($name, $content, $http_equivalent = false) {
      $meta_name = trim(strtolower($name));
      if(!isset(self::$meta[$meta_name])) {
        self::$meta[$meta_name] = meta_tag($meta_name, $content, $http_equivalent);
      } // if
    } // setMetaTag
    
    /**
    * Return all link tags
    *
    * @param void
    * @return array
    */
    static function getLinks() {
      return self::$links;
    } // getLinks
    
    /**
    * Add rel link to this page (rel can be used to specify the relationship of the target of 
    * the link to the current page)
    * 
    * More details: http://www.htmldog.com/reference/htmltags/link/
    *
    * @param string $href
    * @param string $rel Relation type
    * @param array $attributes Additional link attributes
    * @return null
    */
    static function addRelLink($href, $rel, $attributes = null) {
      self::$links[] = link_tag_rel($href, $rel, $attributes);
    } // addLink
    
    /**
    * Add rel link to this page (rev can be used to specify the relationship of the current page 
    * to the target of the link)
    * 
    * More details: http://www.htmldog.com/reference/htmltags/link/
    *
    * @param string $href
    * @param string $rev Relation type
    * @param array $attributes
    * @return null
    */
    static function addRevLink($href, $rev, $attributes = null) {
      self::$links[] = link_tag_rev($href, $rev, $attributes);
    } // addRevLink
    
    /**
    * Return all page scripts that go in <head> tag
    *
    * @param void
    * @return array
    */
    static function getScripts() {
      return self::$scripts;
    } // getScripts
    
    /**
    * Add script to the page construction. This function can add script as external 
    * file or as inline code
    *
    * @param string $value If $inline is true $value is actual JavaScript code, else 
    *   it is URL of external javascript file
    * @param boolean $inline
    * @param string $type Script type
    * @return null
    */
    static function addScript($value, $inline = false, $type = 'text/javascript') {
      self::$scripts[] = script_tag($value, $inline, $type);
    } // addScript
    
    /**
    * Return value of specific page fragment
    *
    * @param string $name Fragment name
    * @return string
    */
    static function getFragment($name) {
      return array_var(self::$framgents, $name);
    } // getFragment
    
    /**
    * Set value of specific fragment
    *
    * @param string $name Name of the fragment
    * @param string $content
    * @return null
    */
    static function setFragment($name, $content) {
      self::$framgents[$name] = $content;
    } // setFragment
    
    /**
    * Check if specific fragment exists
    *
    * @param string $name Fragment name
    * @return boolean
    */
    static function hasFragment($name) {
      return isset(self::$framgents[$name]);
    } // hasFragment
    
    // ---------------------------------------------------
    //  Additional page methods
    // ---------------------------------------------------
    
    /**
    * Set page keywords
    *
    * @param string $value
    * @return null
    */
    function setKeywords($value) {
      self::setMetaTag('keywords', $value);
    } // setKeywords
    
    /**
    * Set page description
    *
    * @param string $value
    * @return null
    */
    function setDescription($value) {
      self::setMetaTag('description', $value);
    } // setDescription
  
  } // Angie_PageConstruction

?>