<?php

  /**
  * Pagination helpers
  * 
  * Helpers that use pagination toy to render pagination controls - from pretty simple page 
  * lists to more powerful pagination controls
  *
  * @package Angie.controller
  * @subpackage helpers
  * @author Ilija Studen <ilija.studen@gmail.com>
  */

  /**
  * Render simple pagination
  * 
  * Simple pagination will render simple list of page links separated with $separator. $pagination
  * argument is pagination description. $url_base and $page_placeholder are two params that will be
  * used to generate links for proper pages ($page_placeholder value in URL will be replaced with
  * value of current page). Pages will be imploaded with $separator as a glue string.
  * 
  * Example:
  * <pre>
  * simple_pages($pagination, 'http://www.google.com/?page=#PAGE#', '#PAGE#');
  * </pre>
  *
  * @param Angie_Pagination $pagination
  * @param string $url_base
  * @param string $page_placeholder
  * @param string $separator
  * @return string
  */
  function simple_pagination(Angie_Pagination $pagination, $url_base, $page_placeholder = '#PAGE#', $separator = ', ') {
    $page_urls = array();
    
    for($i = 1; $i <= $pagination->getTotalPages(); $i++) {
      if($i == $pagination->getCurrentPage()) {
        $page_urls[] = "($i)";
      } else {
        $page_urls[] = '<a href="' . str_replace($page_placeholder, $i, $url_base) . '">' . $i . '</a>';
      } // if
    } // for
    
    return count($page_urls) ? implode($separator, $page_urls) : '';
  } // simple_pagination
  
  /**
  * Render advanced pagination
  * 
  * Differenced between simple and advanced paginations is that advanced pagination uses view files so 
  * its output can be changed in a great number of ways. Advanced pagination can also use default 
  * view where everything is in place and ready to go or developer can define new views for special cases
  * 
  * $pagination argument is pagination description instance. $url_base and $page_placeholder are two params 
  * that will be used to generate links for proper pages ($page_placeholder value in URL will be replaced 
  * with value of current page). $view can be absolute path to existing view file or filename of template
  * 
  * Example:
  * <pre>
  * // Use built in pagination view
  * advanced_pagination($pager, 'http://www.google.com/?page=#PAGE#');
  * 
  * // Use your own template
  * advanced_pagination($pager, 'http://www.google.com/?page=#PAGE#', 'my_pagination');
  * </pre>
  * 
  * All variables are just passed to the template, nothing is done inside the function!
  *
  * @param Angie_Pagination $pagination
  * @param string $url_base
  * @param string $template
  * @param string $page_placeholder
  * @return string
  */
  function advanced_pagination(Angie_Pagination $pagination, $url_base, $view = 'advanced_pagination', $page_placeholder = '#PAGE#') {
    Angie::getTemplateEngine()->assignToView(array(
      'advanced_pagination_object' => $pagination,
      'advanced_pagination_url_base' => $url_base,
      'advanced_pagination_page_placeholder' => urlencode($page_placeholder)
    )); // tpl_assign
    
    $view_path = is_file($view) ? $view : Angie::engine()->getViewPath($view_path);
    return Angie::getTemplateEngine()->fetchView($view_path);
  } // advanced_pagination

?>