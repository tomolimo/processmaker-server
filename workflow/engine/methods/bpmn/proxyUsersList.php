<?php
try
 {
   G::LoadClass('processMap');
   $oProcessMap = new processMap(new DBConnection);
   if ( isset($_GET['pid'] ) )
   {
       $rows        = $oProcessMap->usersExtList($_GET['pid'], $_GET['tid']);
       array_shift($rows);
   }
   else
   {
       $rows        = $oProcessMap->getAvailableExtUsersCriteria($_GET['tid']);
   }
  /*else
   {
    require_once 'classes/model/Users.php';
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(UsersPeer::USR_UID);

    $sDataBase = 'database_' . strtolower(DB_ADAPTER);
    if(G::LoadSystemExist($sDataBase)){
      G::LoadSystem($sDataBase);
      $oDataBase = new database();
      $oCriteria->addAsColumn('USR_COMPLETENAME', $oDataBase->concatString("USR_LASTNAME", "' '", "USR_FIRSTNAME"));
    }

    $oCriteria->addSelectColumn(UsersPeer::USR_USERNAME);

    $oDataset = UsersPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();

    $result = array();
    $rows = array();
    $index =  isset($start) ? $start : 0;
    while($aRow = $oDataset->getRow()){
      $aRow['index'] = ++$index;
      $rows[] = $aRow;

      $oDataset->next();
    }
   }
   */
    $result['totalCount'] = count($rows);
    $result['data'] = $rows;
    print json_encode( $result ) ;
 
 }
  catch ( Exception $e ) {
  	print json_encode ( $e->getMessage() );
  }
