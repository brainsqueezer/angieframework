<?php

  /**
  * Feed item class
  * 
  * Objects of this class represent single feed items. Required properties are title, link, description and publication 
  * date. Author is optional
  *
  * @package Angie.toys
  * @subpackage feed
  * @author Ilija Studen <ilija.studen@gmail.com>
  */
  class Angie_Feed_Item {
    
    /**
    * Item title
    *
    * @var string
    */
    private $title;
    
    /**
    * Item link
    *
    * @var string
    */
    private $link;
    
    /**
    * Item description
    *
    * @var string
    */
    private $description;
    
    /**
    * Publication date
    *
    * @var Angie_DateTime
    */
    private $publication_date;
    
    /**
    * Item author
    *
    * @var Angie_Feed_Author
    */
    private $author;
  
    /**
    * Constructor
    * 
    * Construct the feed item and set internal properties. Title, link, description and publication dates are required 
    * values
    *
    * @param void
    * @return Angie_Feed_Item
    * @throws Angie_Core_Error_InvalidInstance
    */
    function __construct($title, $link, $description, Angie_DateTime $publication_date) {
      $this->setTitle($title);
      $this->setLink($link);
      $this->setDescription($description);
      $this->setPublicationDate($publication_date);
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
    * Get link
    *
    * @param null
    * @return string
    */
    function getLink() {
      return $this->link;
    } // getLink
    
    /**
    * Set link value
    *
    * @param string $value
    * @return null
    */
    function setLink($value) {
      $this->link = $value;
    } // setLink
    
    /**
    * Get description
    *
    * @param null
    * @return string
    */
    function getDescription() {
      return $this->description;
    } // getDescription
    
    /**
    * Set description value
    *
    * @param string $value
    * @return null
    */
    function setDescription($value) {
      $this->description = $value;
    } // setDescription
    
    /**
    * Get publication_date
    *
    * @param null
    * @return Angie_DateTime
    */
    function getPublicationDate() {
      return $this->publication_date;
    } // getPublicationDate
    
    /**
    * Set publication_date value
    *
    * @param Angie_DateTime $value
    * @return null
    */
    function setPublicationDate($value) {
      if(!($value instanceof Angie_DateTime)) {
        throw new Angie_Core_Error_InvalidInstance('value', $value, 'Angie_DateTime');
      } // if
      $this->publication_date = $value;
    } // setPublicationDate
    
    /**
    * Get author
    *
    * @param null
    * @return Angie_Feed_Author
    */
    function getAuthor() {
      return $this->author;
    } // getAuthor
    
    /**
    * Set author value
    *
    * @param Angie_Feed_Author $value
    * @return null
    */
    function setAuthor($value) {
      if(!is_null($value) && !($value instanceof Angie_Feed_Author)) {
        throw new Angie_Core_Error_InvalidInstance('value', $value, 'Angie_Feed_Author');
      } // if
      $this->author = $value;
    } // setAuthor
  
  } // Angie_Feed_Item

?>