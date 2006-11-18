<?php

  /**
  * Pagination toy
  * 
  * Pagination class can fully describe any paginated result based on total number of
  * items, number of items per page and current page number.
  * 
  * Examples:
  * <pre>
  * $pagination = new Angie_Pagination(97, 10, 5);
  * 
  * $pagination->getTotalPages(); // 10
  * $pagination->countItemsOnPage(3); // 10
  * $pagination->countItemsOnPage(10); // 7
  * 
  * $pagination->setCurrentPage(1);
  * $pagination->hasPrevious(); // false
  * $pagination->hasNext(); // true
  * 
  * $this->pagination->setCurrentPage(5);
  * $pagination->hasPrevious(); // true
  * $pagination->hasNext(); // true
  * 
  * $pagination->setCurrentPage(10);
  * $pagination->hasPrevious(); // true
  * $pagination->hasNext(); // false
  * </pre>
  * 
  * This class is really useful when you are displaying large number of database records
  * and want to display them on pages. paginate() method of any model manager class lets you
  * paginate database results easily - it will return an array of two elements:
  * 
  * <ol>
  * <li>Array of items that are on current page (it can be empty if resultset is empty or 
  * current page is out of pagination boundaries)</li>
  * <li>Object that describes paginated result (object of Angie_Pagination class)</li>
  * </ol>
  * 
  * Example:
  * <pre>
  * list($files, $pagination) = ProjectFiles::paginate(array(
  *   'conditions' => array('`folder_id` = ?', 12)
  * ), 10, 2);
  * </pre>
  * 
  * Pagination description can be reused in views for rendering pagination controls with single
  * line of code using built in {@link pagination.php pagination} helpers.
  *
  * @package Angie.toys
  * @subpackage pagination
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Pagination {
    
    /**
    * Total number of items
    *
    * @var integer
    */
    private $total_items = 0;
    
    /**
    * Number of items per page
    *
    * @var integer
    */
    private $items_per_page = 10;
    
    /**
    * Number of current page
    *
    * @var integer
    */
    private $current_page = 1;
    
    /**
    * Cached total pages value. If null this value will be calculated by getTotalPages()
    * method and cached
    *
    * @var integer
    */
    private $total_pages = null;
  
    /**
    * Construct the Angie_Pagination
    *
    * @param integer $total_items Number of items
    * @param integer $items_per_page Number of items per page. Default is 10
    * @param integer $current_page Current page. Default is 1
    * @return Angie_Pagination
    */
    function __construct($total_items = null, $items_per_page = null, $current_page = null) {
      if(!is_null($total_items)) {
        $this->setTotalItems($total_items);
      } // if
      if(!is_null($items_per_page)) {
        $this->setItemsPerPage($items_per_page);
      } // if
      if(!is_null($current_page)) {
        $this->setCurrentPage($current_page);
      } // if
    } // __construct
    
    // ---------------------------------------------------
    //  Check and get
    // ---------------------------------------------------
    
    /**
    * Check if specific page is current page. If $page is null function will use
    * current page
    *
    * @param integer $page Page that need to be checked. If null function will
    *   use current page
    * @return boolean
    */
    function isCurrent($page = null) {
      $page = is_null($page) ? $this->getCurrentPage() : (integer) $page;
      return $page == $this->getCurrentPage();
    } // isCurrent
    
    /**
    * Check if specific page is first page. If $page is null function will use
    * current page
    *
    * @param integer $page Page that need to be checked. If null function will
    *   use current page
    * @return boolean
    */
    function isFirst($page = null) {
      $page = is_null($page) ? $this->getCurrentPage() : (integer) $page;
      return $page == 1;
    } // isFirst
    
    /**
    * Check if specific page is last page. If $page is null function will use
    * current page
    *
    * @param integer $page Page that need to be checked. If null function will
    *   use current page
    * @return boolean
    */
    function isLast($page = null) {
      $page = is_null($page) ? $this->getCurrentPage() : (integer) $page;
      if(is_null($last = $this->getTotalPages())) {
        return false;
      } // if
      return $page == $last;
    } // isLast
    
    /**
    * Return previous page. If there is some kind of error function will return 
    * current page. Check existance of prev page using hasPrevious() function
    *
    * @param void
    * @return integer
    */
    function getPrevious() {
      return $this->hasPrevious() ? $this->getCurrentPage() - 1 : $this->getCurrentPage();
    } // getPreviousPage
    
    /**
    * Check if specific page has previous page. If $page is null function will use
    * current page
    *
    * @param integer $page Page that need to be checked. If null function will
    *   use current page
    * @return boolean
    */
    function hasPrevious($page = null) {
      $page = is_null($page) ? $this->getCurrentPage() : (integer) $page;
      return $page > 1;
    } // hasPrevious
    
    /**
    * Return next page number. In case of an error this function will return current 
    * page number. Check if next page exists using hasNext() function
    *
    * @param void
    * @return integer
    */
    function getNext() {
      return $this->hasNext() ? $this->getCurrentPage() + 1 : $this->getCurrentPage();
    } // getNext
    
    /**
    * Check if specific page has next page. If $page is null function will use
    * current page
    *
    * @param integer $page Page that need to be checked. If null function will
    *   use current page
    * @return boolean
    */
    function hasNext($page = null) {
      $page = is_null($page) ? $this->getCurrentPage() : (integer) $page;
      if(is_null($last = $this->getTotalPages())) {
        return false;
      } // if
      return $page < $last;
    } // hasNext
    
    /**
    * Return total number of pages
    *
    * @param void
    * @return integer
    */
    function getTotalPages() {
      if(is_int($this->total_pages)) {
        return $this->total_pages;
      } // if
      if(($this->getItemsPerPage() < 1) || ($this->getTotalItems() < 1)) {
        return 1; // there must be one page
      } // if
      
      if(($this->getTotalItems() % $this->getItemsPerPage()) == 0) {
        $this->total_pages = (integer) ($this->getTotalItems() / $this->getItemsPerPage());
      } else {
        $this->total_pages = (integer) ($this->getTotalItems() / $this->getItemsPerPage()) + 1; 
      } // if
      
      return $this->total_pages;
    } // getTotalPages
    
    /**
    * Return number of items on specific page
    *
    * @param integer $page
    * @return integer
    */
    function countItemsOnPage($page) {
      $page = (integer) $page;
      if($page < 1) $page = 1;
      
      if(($page + 1) * $this->getItemsPerPage() > $this->getTotalItems()) {
        return $this->getTotalItems() - (($page - 1) * $this->getItemsPerPage());
      } else {
        return $this->getItemsPerPage();
      } // if
    } // countItemsOnPage
    
    /**
    * Return first param for LIMIT in queries. Second one is number of items per page
    *
    * @param integer $page On witch page? If null current will be used
    * @return integer
    */
    function getLimitStart($page = null) {
      $page = is_null($page) ? $this->getCurrentPage() : (integer) $page;
      $page -= 1; // Start is one page down...
      
      return ($page * $this->getItemsPerPage());
    } // getLimitStart
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Get total_items
    *
    * @param null
    * @return integer
    */
    function getTotalItems() {
      return $this->total_items;
    } // getTotalItems
    
    /**
    * Set total_items value
    *
    * @param integer $value
    * @return null
    */
    function setTotalItems($value) {
      $this->total_pages = null;
      $this->total_items = (integer) $value > 0 ? (integer) $value : 0;
    } // setTotalItems
    
    /**
    * Get items_per_page
    *
    * @param null
    * @return integer
    */
    function getItemsPerPage() {
      return $this->items_per_page;
    } // getItemsPerPage
    
    /**
    * Set items_per_page value
    *
    * @param integer $value
    * @return null
    */
    function setItemsPerPage($value) {
      $this->total_pages = null;
      $this->items_per_page = (integer) $value > 0 ? (integer) $value : 10;
    } // setItemsPerPage
    
    /**
    * Get current_page
    *
    * @param null
    * @return integer
    */
    function getCurrentPage() {
      return $this->current_page;
    } // getCurrentPage
    
    /**
    * Set current_page value
    *
    * @param integer $value
    * @return null
    */
    function setCurrentPage($value) {
      $this->current_page = (integer) $value > 0 ? (integer) $value : 1;
    } // setCurrentPage
  
  } // Angie_Pagination

?>