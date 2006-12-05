<?php

  /**
  * General HTML helpers that are used for rendering standard tags
  *
  * @package Angie.controller
  * @subpackage helpers
  * @author Ilija Studen <ilija.studen@gmail.com>
  */

  /**
  * Open HTML tag
  *
  * @param string $name Tag name
  * @param array $attributes Array of tag attributes
  * @param boolean $empty If tag is empty it will be automaticly closed
  * @return string
  */
  function open_html_tag($name, $attributes = null, $empty = false) {
    $attribute_string = '';
    if(is_array($attributes) && count($attributes)) {
      $prepared_attributes = array();
      foreach($attributes as $k => $v) {
        if(trim($k) <> '') {
          
          if(is_bool($v)) {
            if($v) $prepared_attributes[] = "$k=\"$k\"";
          } else {
            $prepared_attributes[] = $k . '="' . clean($v) . '"';
          } // if
          
        } // if
      } // foreach
      $attribute_string = implode(' ', $prepared_attributes);
    } // if
    
    $empty_string = $empty ? ' /' : ''; // Close?
    return "<$name $attribute_string$empty_string>"; // And done...
  } // html_tag
  
  /**
  * Close specific HTML tag
  *
  * @param string $name Tag name
  * @return string
  */
  function close_html_tag($name) {
    return "</$name>";
  } // close_html_tag
  
  /**
  * Return anchor (a) tag
  *
  * @param string $text
  * @param string $href
  * @param array $attributes
  * @return string
  */
  function anchor_tag($text, $href, $attributes = null) {
    if(!is_array($attributes)) {
      $attributes = array();
    } // if
    $attributes['href'] = $href;
    
    return open_html_tag('a', $attributes) . clean($text) . '</a>';
  } // anchor_tag
  
  /**
  * Return title tag
  *
  * @param string $title
  * @return string
  */
  function title_tag($title) {
    return open_html_tag('title') . $title . '</title>';
  } // title_tag

  /**
  * Prepare link tag
  *
  * @param string $href
  * @param string $rel_or_rev Rel or rev
  * @param string $rel
  * @param array $attributes
  * @return string
  */
  function link_tag($href, $rel_or_rev = 'rel', $rel = 'alternate', $attributes = null) {
    $all_attributes = array(
      'href' => $href,
      $rel_or_rev => $rel
    ); // array
    
    if(is_array($attributes) && count($attributes)) {
      $all_attributes = array_merge($all_attributes, $attributes);
    } // if
    
	  return open_html_tag('link', $all_attributes, true);
  } // link_tag
  
  /**
  * Rel link tag
  *
  * @param string $href
  * @param string $rel
  * @param string $attributes
  * @return string
  */
  function link_tag_rel($href, $rel, $attributes = null) {
    return link_tag($href, 'rel', $rel, $attributes);
  } // link_tag_rel
  
  /**
  * Rev link tag
  *
  * @param string $href
  * @param string $rel
  * @param string $attributes
  * @return string
  */
  function link_tag_rev($href, $rel, $attributes = null) {
    return link_tag($href, 'rev', $rel, $attributes);
  } // link_tag_rev
  
  /**
  * Return code of meta tag
  *
  * @param string $name Name of the meta property
  * @param string $content
  * @param boolean $http_equiv
  * @return string
  */
  function meta_tag($name, $content, $http_equiv = false) {
    $name_attribute = $http_equiv ? 'http-equiv' : 'name';
    
    // Prepare attributes
    $attributes = array(
      $name_attribute => $name,
      'content' => $content
    ); // array
    
    return open_html_tag('meta', $attributes, true);
  } // meta_tag
  
  /**
  * Render script tag
  *
  * @param string $content If inline this is actual script code, if not it is link to external
  *   script file
  * @param boolean $inline
  * @param string $type Script type
  * @param array $attributes Additional attributes
  * @return null
  */
  function script_tag($content, $inline = true, $type = 'text/javascript', $attributes = null) {
    if(!is_array($attributes)) {
      $attributes = array();
    } // if
    
    $attributes['type'] = $type;
    
    if($inline) {
      return open_html_tag('script', $attributes) . $content . close_html_tag('script');
    } else {
      $attributes['src'] = $content;
      return open_html_tag('script', $attributes) . '</script>';
    } // if
  } // script_tag
  
  /**
  * Render style tag inside optional conditional comment
  *
  * @param string $content
  * @param string $condition Condition for conditional comment (IE, lte IE6...). If null
  *   conditional comment will not be added
  * @return string
  */
  function style_tag($content, $condition = null) {
    $open = $close = '';
    if($condition) {
      $open = "<!--[if $condition]>\n";
      $close = '<![endif]-->';
    } // if
    
    return $open . open_html_tag('style', array('type' => 'text/css')) . $content . '</style>' . "\n" . $close;
  } // style_tag

?>