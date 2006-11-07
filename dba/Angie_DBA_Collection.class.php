<?php

  class Angie_DBA_Collection implements ArrayAccess, IteratorAggregate {
    
    /**
    * Owner object
    *
    * @var Angie_DBA_Object
    */
    private $owner;
    
    /**
    * Array of loaded data
    *
    * @var array
    */
    private $items;
    
    /**
    * Cached value of items count
    *
    * @var integer
    */
    private $count;
    
    /**
    * Is loaded flag
    *
    * @var boolean
    */
    private $loaded = false;
    
    /**
    * Manager class
    *
    * @var string
    */
    protected $manager_class;
    
    /**
    * Find conditions, ignored if finder_sql is provided
    *
    * @var string
    */
    protected $conditions = null;
    
    /**
    * Finder orger, ignored if finder_sql is provided
    *
    * @var string
    */
    protected $order = null;
    
    /**
    * Finder SQL
    *
    * @var mixed
    */
    private $finder;
    
    /**
    * Construct the collection
    * 
    * Collection uses $manager_class when provided finder is not a string (valid SELECT). Than $finder is treated as a 
    * array of options needed for find() method. If $finder is a string it is executed and its result is populated as 
    * array of $object_class objects
    *
    * @param Angie_DBA_Object $owner
    * @param string $manager_class
    * @param string $object_class
    * @param string $conditions
    * @param string $order
    * @param string $finder_sql
    * @return Angie_DBA_Collection
    */
    function __construct(Angie_DBA_Object $owner, $manager_class, $object_class, $conditions = null, $order = null, $finder_sql = null) {
      $this->setOwner($owner);
      $this->setManagerClass($manager_class);
      $this->setObjectClass($object_class);
      $this->setFinder($finder);
    } // __construct
    
    /**
    * Load / reload result set
    * 
    * $full determins if full rows will be returned
    *
    * @param boolean $full
    * @return null
    */
    function reload($full = false) {
      if($this->isLoaded()) {
        $this->reset();
      } // if
      
      $finder_sql = $this->getFinderSQL();
      if($finder_sql) {
        $this->items = array();
        $object_class = $this->getObjectClass();
        
        $rows = Angie_DB::getConnection()->executeAll($finder_sql);
        if(is_foreachable($rows)) {
          foreach($rows as $row) {
            $item = new $object_class();
            if($item->loadFromRow($row) && $item->isLoaded()) {
              $this->items[] = $item;
            } // if
          } // foreach
        } // if
      } else {
        $this->items = call_user_func(array($this->manager_class, 'find'), array(
          'conditions' => array_merge(array($this->getConditions()), $this->getOwner()->getInitialPkValue()),
          'order' => $this->getOrder(),
        ), $full); // call_user_func
      } // if
      
      $this->count = count($this->items);
      $this->loaded = true;
    } // reload
    
    /**
    * Return number of loaded items
    * 
    * If items are already loaded this function will just count them; if not it will read the cound in a separate query 
    * and save it
    *
    * @param void
    * @return integer
    */
    function count() {
      if($this->isLoaded()) {
        return count($this->items);
      } else {
        if(is_int($this->count)) {
          return $this->count;
        } // if
      } // if
      
      // Guess we actually need to read the count :(
      $this->count = call_user_func(array($this->manager_class, 'count'), $this->find_conditions);
      return $this->count;
    } // count
    
    /**
    * Reset data in a collection
    *
    * @param void
    * @return null
    */
    protected function reset() {
      $this->loaded = false;
      $this->items  = null;
      $this->count  = null;
    } // reset
    
    // ---------------------------------------------------
    //  Array access and IteratorAggregate implementations
    // ---------------------------------------------------
    
    /**
    * Check if specific array offset exist
    *
    * @param void
    * @return boolean
    */
    function offsetExists($offset) {
      return isset($this->items[$offset]);
    } // offsetExists
    
    /**
    * Get value at specific array offset
    *
    * @param mixed $offset
    * @return mixed
    */
    function offsetGet($offset) {
      return isset($this->items[$offset]) ? $this->items[$offset] : false;
    } // offsetGet
    
    /**
    * Set value in specific offset
    *
    * @param mixed $offset
    * @param mixed $value
    * @return null
    */
    function offsetSet($offset, $value) {
      if($offset) {
        $this->items[$offset] = $value;
      } else {
        $this->items[] = $value;
      } // if
    } // offsetSet
    
    /**
    * Unset value at specific offset
    *
    * @param mixed $offset
    * @return null
    */
    function offsetUnset($offset) {
      if(isset($this->items[$offset])) {
        unset($this->items[$offset]);
      } // if
    } // offsetUnset
    
    /**
    * Return items iterator
    *
    * @param void
    * @return ArrayIterator
    */
    function getIterator() {
      return new ArrayIterator($this->items);
    } // getIterator
    
    // ---------------------------------------------------
    //  Getters and setters
    // ---------------------------------------------------
    
    /**
    * Returns true if collection is loaded
    *
    * @param void
    * @return boolean
    */
    function isLoaded() {
      return $this->loaded;
    } // isLoaded
    
    /**
    * Get owner
    *
    * @param null
    * @return Angie_DBA_Object
    */
    function getOwner() {
      return $this->owner;
    } // getOwner
    
    /**
    * Set owner value
    *
    * @param Angie_DBA_Object $value
    * @return null
    */
    private function setOwner(Angie_DBA_Object $value) {
      $this->owner = $value;
    } // setOwner
    
    /**
    * Get manager_class
    *
    * @param null
    * @return string
    */
    function getManagerClass() {
      return $this->manager_class;
    } // getManagerClass
    
    /**
    * Set manager_class value
    *
    * @param string $value
    * @return null
    */
    private function setManagerClass($value) {
      $this->manager_class = $value;
    } // setManagerClass
    
    /**
    * Get object_class
    *
    * @param null
    * @return string
    */
    function getObjectClass() {
      return $this->object_class;
    } // getObjectClass
    
    /**
    * Set object_class value
    *
    * @param string $value
    * @return null
    */
    function setObjectClass($value) {
      $this->object_class = $value;
    } // setObjectClass
    
    /**
    * Get conditions
    *
    * @param null
    * @return string
    */
    function getConditions() {
      return $this->conditions;
    } // getConditions
    
    /**
    * Set conditions value
    *
    * @param string $value
    * @return null
    */
    function setConditions($value) {
      $this->conditions = $value;
    } // setConditions
    
    /**
    * Get order
    *
    * @param null
    * @return string
    */
    function getOrder() {
      return $this->order;
    } // getOrder
    
    /**
    * Set order value
    *
    * @param string $value
    * @return null
    */
    function setOrder($value) {
      $this->order = $value;
    } // setOrder
    
    /**
    * Get finder_sql
    *
    * @param null
    * @return string
    */
    function getFinderSQL() {
      return $this->finder_sql;
    } // getFinderSQL
    
    /**
    * Set finder_sql value
    *
    * @param string $value
    * @return null
    */
    function setFinderSQL($value) {
      $this->finder_sql = $value;
    } // setFinderSQL
  
  } // Angie_DBA_Collection

?>