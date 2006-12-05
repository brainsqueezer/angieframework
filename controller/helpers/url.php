<?php

  /**
  * URL helpers
  *
  * @package Angie.controller
  * @subpackage helpers
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  
  /**
  * Assemble URL based on input data
  * 
  * This function will call assemble method of $route_name route with given 
  * arguments. In case of any problem assemble error is thrown.
  * 
  * Set $qs_separator value if you wish to change how query string argumnes are 
  * spearated. By default amp entity is used.
  *
  * @param string $route_name
  * @param array $arguments
  * @param string $anchor
  * @param string $url_base
  * @return string
  * @throws Angie_Router_Error_Assemble
  */
  function url_for($route_name, $arguments = null, $anchor = null, $url_base = null, $qs_separator = '&amp;') {
    return Angie_Router::assemble($route_name, $arguments, $url_base, $qs_separator, $anchor);
  } // url_for
  
  /**
  * Render a link to a given resource
  * 
  * This function will render a link from a given arguments. URL is generated 
  * with url_for helper. Parameters:
  * 
  * - $text        - Text of the link
  * - $route_name  - Name of the route that is used for URL assembly
  * - $arguments   - URL arguments that are forwareded to URL assembler
  * - $attribute   - Array of HTML attributes used for anchor tag generation
  * - $options     - Associative array of additional options
  * 
  * Additional options include:
  * 
  * - url_base      - Base used for generation of absolute URL-s
  * - qs_separator  - Separator that is used to separate query string arguments
  * - anchor        - URL anchor
  *
  * @param void
  * @return string
  */
  function link_to($text, $route_name, $arguments = null, $attributes = null, $options = null) {
    $url_base     = array_var($options, 'url_base', null);
    $qs_separator = array_var($options, 'query_string_separator', '&amp;');
    $anchor       = array_var($options, 'anchor');
    
    return anchor_tag($text, url_for($route_name, $arguments, $anchor, $url_base, $query_string_separator), $attributes);
  } // link_to

?>