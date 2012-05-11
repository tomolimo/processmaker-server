<?php
require_once ('Base.php');

class Entity_SolrUpdateDocument extends Entity_Base {
  var $workspace = '';
  var $document = '';
  
  private function __construct() {
  }
  
  static function CreateEmpty() {
    $obj = new Entity_SolrUpdateDocument ();
    return $obj;
  }
  
  static function CreateForRequest($data) {
    $obj = new Entity_SolrUpdateDocument ();
    
    $obj->initializeObject ( $data );
    
    $requiredFields = array (
        "workspace",
        "document" 
    );
    
    $obj->validateRequiredFields ( $requiredFields );
    
    return $obj;
  }

}