<?php
try
 {
   G::LoadClass('processMap');
   $oProcessMap = new processMap(new DBConnection);
   if ( isset($_GET['pid'] ) )
   {
       $rows        = $oProcessMap->getExtInputDocumentsCriteria($_GET['pid']);
       array_shift($rows);
   }
  
    $result['totalCount'] = count($rows);
    $result['data'] = $rows;
    print json_encode( $result) ;
 
 }
  catch ( Exception $e ) {
  	print json_encode ( $e->getMessage() );
  }
