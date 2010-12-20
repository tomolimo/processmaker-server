<?php
G::LoadClass('processMap');
   $oProcessMap = new processMap(new DBConnection);

   if ( isset($_GET['pid'] ) && !isset($_GET ['t']))
   {
       $rows        = $oProcessMap->getExtObjectsPermissions($_GET['pid']);

   }
    if ( isset($_GET['pid'] )&& isset($_GET['t']) )
    {
       $rows        = $oProcessMap->newExtObjectPermission($_GET['pid']);

    }

    $result['totalCount'] = count($rows);
    $result['data'] = $rows;
    print json_encode($result);
?>
