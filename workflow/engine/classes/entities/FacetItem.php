<?php
require_once ('Base.php');

/**
 *
 *
 * Entity Face item, represent an option in a facet group
 * 
 * @author dev-HebertSaak
 *        
 */
class Entity_FacetItem extends Entity_Base {
  public $facetName = '';
  public $facetPrintName = '';
  public $facetCount = '';
  public $facetSelectCondition = ''; // selected condition used to select
                                        // this facet
  
  private function __construct() {
  }
  
  static function CreateEmpty() {
    $obj = new Entity_FacetItem ();
    return $obj;
  }
  
  static function CreateForInsert($data) {
    $obj = new Entity_FacetItem ();
    
    $obj->initializeObject ( $data );
    
    $requiredFields = array (
        "facetName",
        "facetCount" 
    );
    
    $obj->validateRequiredFields ( $requiredFields );
    
    return $obj;
  }

}