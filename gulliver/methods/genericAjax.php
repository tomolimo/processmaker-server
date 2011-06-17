<?php 

$request = isset($_POST['request'])? $_POST['request']: null;
if( !isset($request) ){
	$request = isset($_GET['request'])? $_GET['request']: null;
}
if( isset($request) ){
	switch($request){
		case 'deleteGridRowOnDynaform':
			
			if( isset($_SESSION['APPLICATION']) ){
				G::LoadClass('case');
			 	$oApp= new Cases();
			  	$aFields = $oApp->loadCase($_SESSION['APPLICATION']);
			  	unset($aFields['APP_DATA'][$_POST['gridname']][$_POST['rowpos']]);
			  	$oApp->updateCase($_SESSION['APPLICATION'], $aFields);
			}
			
		break;
    /** widgets **/
    case 'suggest':

      try{
        $sData = base64_decode(str_rot13($_GET['hash']));
        list($SQL, $DB_UID) = explode('@|', $sData);
        // Remplace values for dependent fields
        $aDependentFieldsKeys  = explode("|", base64_decode(str_rot13($_GET['dependentFieldsKeys'])));
        $aDependentFieldsValue = explode("|", $_GET['dependentFieldsValue']);
        if($aDependentFieldsKeys){
          $SQL = str_replace($aDependentFieldsKeys, $aDependentFieldsValue, $SQL);
        }
        if (1===preg_match('/^\s*SELECT\s+([\w\W]+?)(?:\s+FROM\s+`?([^`]+?)`?)(?:\s+WHERE\s+([\w\W]+?))?(?:\s+GROUP\s+BY\s+([\w\W]+?))?(?:\s+ORDER\s+BY\s+([\w\W]+?))?(?:\s+BETWEEN\s+([\w\W]+?)\s+AND\s+([\w\W]+?))?(?:\s+LIMIT\s+(\d+)\s*,\s*(\d+))?\s*$/im', $SQL, $matches)) {
          $sqlColumns   = $matches[1];
          $sqlFrom      = isset($matches[2])?$matches[2]:'';
          $sqlWhere     = isset($matches[3])?$matches[3]:'';
          $sqlGroupBy   = isset($matches[4])?$matches[4]:'';
          $sqlOrderBy   = isset($matches[5])?$matches[5]:'';
          $sqlLowLimit  = isset($matches[8])?$matches[8]:'';
          $sqlHighLimit = isset($matches[9])?$matches[9]:'';
          // New SQL String
          $SQL = "SELECT " . $sqlColumns . " FROM " . $sqlFrom;
          $aFieldSelect = explode(",", $sqlColumns);
          $sFieldSel = (count($aFieldSelect)>1 ) ? $aFieldSelect[1] : $aFieldSelect[0];
          if( strlen(trim($sqlWhere))>0) {
            $SQL .= " WHERE " . $sqlWhere . " AND " . $sFieldSel . " LIKE '". $_GET['input']."%'";
          } else {
            $SQL .= " WHERE " . $sFieldSel . " LIKE '". $_GET['input']."%'";
          }
          if( strlen(trim($sqlGroupBy))>0) {
            $SQL .= " GROUP BY " . $sqlGroupBy;
          }
          if( strlen(trim($sqlOrderBy))>0) {
            $SQL .= " ORDER BY " . $sqlOrderBy;
          }
        } else {
          //fixed: improving the statement sql by krlos
          $sSql=substr($SQL, 6, strlen($SQL));
          $pattern = "/\bfrom\b/i";
          $replacement = 'FROM';
          $sSql = preg_replace($pattern, $replacement, $sSql);
          $aSql = explode("FROM", $sSql);

          $afieldSql = explode(",",$aSql[0]);

          if(count($afieldSql)>1)
            $SQL .= " where $afieldSql[1] like '". $_GET['input']."%'";
          else
            $SQL .= " where $afieldSql[0] like '". $_GET['input']."%'";
          //add fixed
        }

        $aRows = Array();
        try {
            $con = Propel::getConnection($DB_UID);
            $con->begin();
            $rs = $con->executeQuery($SQL);
            $con->commit();

            while ( $rs->next() ) {
                array_push($aRows, $rs->getRow());
            }
        } catch (SQLException $sqle) {
            $con->rollback();
        }

        $input = strtolower( $_GET['input'] );
        $len = strlen($input);
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 0;

        $aResults = array();
        $count = 0;


        if ($len){
            for ($i=0;$i<count($aRows);$i++){
                $aRow = $aRows[$i];
                $nCols = sizeof($aRow);

                $aRow = array_values($aRow);
                switch( $nCols ){
                    case 1:
                        $id = $aRow[0];
                        $value = $aRow[0];
                        $info = '';
                        break;

                    case 2:

                        $id = $aRow[0];
                        $value = $aRow[1];
                        $info = '';
                        break;

                    case $nCols >= 3:
                        //print_r($aRow);
                        $id = $aRow[0];
                        $value = $aRow[1];
                        $info = $aRow[2];
                        break;
                }


                // had to use utf_decode, here
                // not necessary if the results are coming from mysql
                //
                if (strtolower(substr($value,0,$len)) == $input){
                    $count++;
                    $aResults[] = array( "id"=>$id ,"value"=>htmlspecialchars($value), "info"=>htmlspecialchars($info) );
                }

                if ($limit && $count==$limit)
                    break;
            }
        }

        header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
        header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
        header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header ("Pragma: no-cache"); // HTTP/1.0

        if (isset($_REQUEST['json'])) {
            header("Content-Type: application/json");
            echo "{\"status\":0,  \"results\": [";
            $arr = array();
            $aReplace = array("(\r\n)", "(\n\r)", "(\n)", "(\r)");
            for ($i=0;$i<count($aResults);$i++) {
              $arr[] = "{\"id\": \"".$aResults[$i]['id']."\", \"value\": \"". preg_replace($aReplace, "", $aResults[$i]['value']) ."\", \"info\": \"".$aResults[$i]['info']."\"}";		
            }
            echo implode(", ", $arr);
            echo "]}";
        } else {
            header("Content-Type: text/xml");

            echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
            for ($i=0;$i<count($aResults);$i++)
            {
                echo "<rs id=\"".$aResults[$i]['id']."\" info=\"".$aResults[$i]['info']."\">".$aResults[$i]['value']."</rs>";
            }
            echo "</results>";
        }

      } catch(Exception $e){
        $err = $e->getMessage();
        //$err = eregi_replace("[\n|\r|\n\r]", ' ', $err);
        $err = preg_replace("[\n|\r|\n\r]", ' ', $err);//Made compatible to PHP 5.3
        echo '{"status":1, "message":"'.$err.'"}';
      }
    break;


      case 'storeInTmp':
      try{
        $con = Propel::getConnection($_GET['cnn']);
        if($_GET['pkt'] == 'int'){
          $rs = $con->executeQuery("SELECT MAX({$_GET['pk']}) as lastId FROM {$_GET['table']};");
          $rs->next();
          $row = $rs->getRow();
          $gKey = (int)$row['lastId'] + 1;

        } else {
          $gKey = md5(date('Y-m-d H:i:s').'@'.rand());
        }

        $rs = $con->executeQuery("INSERT INTO {$_GET['table']} ({$_GET['pk']}, {$_GET['fld']}) VALUES ('$gKey', '{$_GET['value']}');");
        echo '{status:0, message:"success"}';
      }catch( Exception $e){
        $err = $e->getMessage();
        //$err = eregi_replace("[\n|\r|\n\r]", ' ', $err);
        $err = preg_replace("[\n|\r|\n\r]", ' ', $err);//Made compatible to PHP 5.3
        echo '{result:1, message:"'.$err.'"}';
      }
      break;
	}	
}

