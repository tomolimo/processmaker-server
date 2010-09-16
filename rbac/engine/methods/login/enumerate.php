<style>
/**
 * enumerate.php
 *  
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd., 
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 * 
 */
  .formContent {
    background: #f2f9fd;
    color : #002c72;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
    font-weight: normal;
    border: 1px solid #bee1f6;
  }
</style>
<?php

  $dbc = new DBConnection (DB_HOST, 'root', '', 'mysql' );
  
  $ses = new DBsession($dbc);
  $dset = $ses->Execute ( "SHOW DATABASES; " );
  
  $sites = array ();
  $row = $dset->Read();
  while ( is_array ( $row ) ) {
    $dbname = $row[Database];
    if ( substr ( $dbname,0,5) == 'rbac_' ) {
      $sname = substr ( $dbname,5);
      $sites[$sname]['rbac'] = 'Y';
    }
    if ( substr ( $dbname,0,3) == 'wf_' ) {
      $sname = substr ( $dbname,3);
      $sites[$sname]['wf'] = 'Y';
    }
    if ( substr ( $dbname,0,7) == 'report_' ) {
      $sname = substr ( $dbname,7);
      $sites[$sname]['report'] = 'Y';
    }
    $row = $dset->Read();
  }

  $dynaPath = '/home/workflow/engine/xmlform/dynaform/';
  if ( $handle1 = opendir ( $dynaPath ) ) {
    while ( false !== ($file1 = readdir($handle1) ))  {
      $dirSize = 0; $dirCant = 0;
      if ( $file1 != '.' && $file1 != '..' && $handle2 = opendir ( $dynaPath . $file1  ) ) {
        while ( false !== ($file2 = readdir($handle2) ))  {
          $dynaPath2 = $dynaPath . $file1 . '/' . $file2;
          if ( is_dir( $dynaPath2) &&  $file2 != '.' && $file2 != '..' && $handle3 = opendir ( $dynaPath2 ) ) {
            while ( false !== ($file3 = readdir($handle3) ))  {
              if ( $file3 != '.' && $file3 != '..' ) {
  	        $dirSize += filesize ( $dynaPath. $file1.'/'.$file2.'/'.$file3 );
		$dirCant ++;
                //print "$file1/$file2/$file3  $dirCant $dirSize <br>";
	      }	
   	    }    
  	  }
        }  
      }
      if ( $file1 != '.' && $file1 != '..'  ) {
        $dirSize = (int)($dirSize / 1024 );
        $sites[$file1]['dynaCant'] = $dirCant;
        $sites[$file1]['dynaSize'] = $dirSize;
        //print "$file1  $dirCant $dirSize Kb. <hr>";
      }
    }
  }
  
  foreach ( $sites as $key=>$val ) {    
    if ( $val['wf'] == 'Y' &&  $val['rbac'] == 'Y' ) {
      print "<table class='formContent' >";
      print "<tr><td width=300 class='formContent'>$key</td><td class='formContent'>&nbsp;" .
         ($val[wf]=='Y' ? ' wf ' : ' - ') . ($val[rbac]=='Y' ? ' rbac ' : ' - ') . ($val[report]=='Y' ? ' report ' : ' - ')  . 
	 "&nbsp;</td><td class='formContent' align='center'> cases </td>" . 
	       "<td class='formContent'> " . $val[dynaCant] . ' files &nbsp; ' .
	       "</td><td class='formContent'> " . $val[dynaSize] . " Kb. </td></tr>";
            
      $dbc1 = new DBConnection (DB_HOST, 'root', '', 'wf_' . $key );
      $ses1 = new DBsession($dbc1);
      $dset1 = $ses1->Execute ( "SELECT * FROM PROCESS; " );
      $row1 = $dset1->Read();
      while ( is_array ( $row1) ) {
        print "<tr><td>" . $row1[UID] .'. '. $row1[PRO_TITLE] . "</td><td align=center>" .  $row1[PRO_STATUS] . "</td>";
        $ses2 = new DBsession($dbc1);
        $dset2 = $ses2->Execute ( "SELECT count(*) as CANT FROM APPLICATION where APP_PROCESS = " . $row1[UID] );
        $row2 = $dset2->Read();
	print "</td><td align='center'>" .  $row2[CANT] . " </td>";

        $dynaPath = '/home/workflow/engine/xmlform/dynaform/' . $key . '/'. $row1[UID] . '/';
	//print_r ( $dynaPath);
        $dirSize = 0; $dirCant = 0;
        if ( is_dir ($dynaPath) && $handle1 = opendir ( $dynaPath ) ) {
          while ( false !== ($file1 = readdir($handle1) ))  {
            if ( $file1 != '.' && $file1 != '..' ) {
              $dirSize += (int) ( filesize ( $dynaPath. $file1 ) / 1024);
	      $dirCant ++;
              //print "$file1  $dirCant $dirSize <br>";
  	    }
          }  
        }
        //if ( is_dir ( $dynaPath) )
	//  print $dynaPath;
	print "</td><td align='center'>$dirCant </td><td align='center'> $dirSize </td></tr>";

        print "</tr>";
        $row1 = $dset1->Read();
      }
      print "</table><br>";
    }
  }
  
  ;
?>