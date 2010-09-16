<?php
global $rootFolder;

require_once ( "classes/model/AppFolder.php" );
$oPMFolder = new AppFolder();
if(($rootFolder=='0')||($rootFolder==0)){
  $folderPath['PATH']="/";
}else{
  $folderPath=$oPMFolder->getFolderStructure($rootFolder);
}

$html = '
<div>

	 <div class="boxTopBlue"><div class="a"></div><div class="b"></div><div class="c"></div></div>
	 <div class="boxContentBlue">

	  <table width="100%" style="margin:0px;" cellspacing="0" cellpadding="0">
	  <tr>
		  <td class="userGroupTitle"><div class="userGroupLink">'.G::LoadTranslation("ID_PM_FOLDER").': <a href="javascript:openPMFolder(\''.$rootFolder.'\',\''.$rootFolder.'\')">'.$folderPath['PATH'].'</a></div></td>
	  </tr>
	</table>
	</div>
	<div class="boxBottomBlue"><div class="a"></div><div class="b"></div><div class="c"></div></div>

	';
	$html.='<div class="treeBase" style="width:360px;height: expression( this.scrollHeight > 319 ? \'320px\' : \'auto\' ); /* sets max-height for IE */  max-height: 320px; /* sets max-height value for all standards-compliant browsers */  overflow:auto;">
	<div class="boxTop">
		<div class="a"></div>
		<div class="b"></div>
		<div class="c"></div>
	</div>

  <div class="content" > ';
  $html.="<div id='child_$rootFolder'  ></div>
  </div></div>
  ";
  $html.='
  <div class="boxBottom">
		<div class="a"></div>
		<div class="b"></div>
		<div class="c"></div>
	</div>
	</div>';


	$html.='<div class="treeBase" style="width:360px;height:200px; border		: 0px solid #006699; overflow	: hidden;">
	<div class="boxTop">
		<div class="a"></div>
		<div class="b"></div>
		<div class="c"></div>
	</div>

  <div class="content" > ';
  $html.="<div id='tags_cloud'  ></div>
  </div>
  ";
  $html.='
  <div class="boxBottom">
		<div class="a"></div>
		<div class="b"></div>
		<div class="c"></div>
	</div>
	</div>';



  echo $html;
  ?>