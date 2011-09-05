* { behavior: url(/skin/green/iepngfix.htc) }
body
{
	margin		:0px;
	background-color:#ECECEC;
	color		:#808080;
	font		:normal 8pt sans-serif,Tahoma,MiscFixed;
}

.Footer
{
	font		:normal 8pt sans-serif,Tahoma,MiscFixed;
	color		:#000;
	height		:150px;
	text-align	:center;
}
.Footer .image
{
	/*background-image: url(/skins/green/images/bf.jpg);
	background-repeat: repeat-x;
	height:10px;*/

}
.Footer .content
{
	color		:black;
	padding		:10px;
}
.Footer .content a
{
	color		:#006699;
	text-decoration	:none;
}
.Footer .content a:hover
{
	color		:orange;
}
.logout a
{
	font:bold 8pt Tahoma,sans-serif,MiscFixed;
	color:#006699;
	text-decoration:none;
}
.logout a:hover
{
	color:orange;
}
.temporalMessage
{
	color	:red;
	font	:normal 8pt Tahoma,sans-serif,MiscFixed;
	text-decoration:none;
}
.userGroupTitle
{
	color	:black;
	font	:bold 8pt Tahoma,sans-serif,MiscFixed;
	padding-left:10px;
}
.userGroupLink
{
	padding:0px;
	padding-left:10px;
	padding-bottom:10px;
}
.userGroupLink a
{
	color	:#006699;
	text-decoration:none;
	font	:bold 8pt Tahoma,sans-serif,MiscFixed;
}
.userGroupLink a:hover
{
	color	:orange;
}
span.treePlusLink
{
	background-color:#E68B2C;
	color:white;
	font:normal 7pt Tahoma,MiscFixed;
	text-decoration:none;
	padding-left:1px;
	padding-right:1px;
	overflow:hidden;
	cursor:pointer;
}
span.treePlusLink:hover
{
	background-color:#EFC395;
}
span.XtreeMinus
{
	background-color:#006699;
	color:white;
	font:normal 7pt Tahoma,MiscFixed;
	text-decoration:none;
	padding-left:1px;
	padding-right:1px;
	overflow:hidden;
	cursor:pointer;
}
span.XtreeMinus:hover
{
	background-color:#EFC395;
}

/* leimnud.app.menuRight CSS begin */

/* Processmaker CSS  begin */
.pm_separator___processmaker
{

}
.pm_separatorOn___processmaker
{

}
.pm_separatorOff___processmaker,.pm_separatorOn___processmaker
{
	height: 7px;
	cursor:pointer;
	text-align:center;
	overflow:hidden;
}
.pm_separatorDOff___processmaker,.pm_separatorDOn___processmaker, .pm_separatorOver___processmaker, .pm_separatorOut___processmaker
{
	height:7px;
	width:100%;
	background-color:#C1D2EE;
	background-color:buttonface;
	border-color:buttonhighlight buttonshadow buttonshadow buttonhighlight;
	border-style:solid;
	border-width:1px 0pt;
	overflow:hidden;
}
.pm_separatorDOff___processmaker
{
	background:url(/js/maborak/core/images/separator.up.gif);
	background-repeat:no-repeat;
	background-position:50% 2;

}
.pm_separatorDOn___processmaker
{
	background:url(/js/maborak/core/images/separator.down.gif);
	background-repeat:no-repeat;
	background-position:50% 50%;
}
.pm_separatorOver___processmaker
{
	background-color:#C1D2EE;
}
.pm_separatorOut___processmaker
{
	background-color:buttonface;
}

/* Processmaker CSS  end */
/* processmap Theme.Task begin */
/*theme firefox begin*/
.processmap_task___firefox
{
	position	: absolute;
	height		: 30;
	width		: 150;
	background-color: #006699;
	border		: 1px solid #006699;
	z-index		: 10;
	overflow	: hidden;
	cursor		: move;
}
.processmap_task_label___firefox
{
	color:white;
	text-align:center;
	cursor:text;
	padding-top:11;
}
.processmap_text___firefox
{
	position	: absolute;
	cursor		: move;
	background-color:red;
}
.processmap_title___firefox
{

}
	/*theme firefox end*/

	/*theme processmap begin*/
.processmap_task___processmaker
{
	position	: absolute;
	height		: 40;
	height		: 38;
	width		: 171;
	width		: 164;
	z-index		: 10;
	overflow	: hidden;
	cursor		: move;
	/*background	: url(/js/processmap/core/images/bg_task.gif);
	background	: url(/images/fondotask.png);*/
	//background-color:#006699;
	//background-color:#6F6F6F;
	background	: url(/images/borderTask.gif);
	background-color:#0576C4;
	background-color:#006699;
	vertical-align:middle;
	display:table-cell;
}
.processmap_task_label___processmaker
{
	color:#FFF;
	text-align:center;
	/*position:absolute;*/
	margin:10;
	vertical-align:middle;
	cursor:move;
}
.processmap_text___processmaker
{
	position	: absolute;
	z-index		: 10;
	cursor		: move;
	font		: bold 8pt Tahoma,MiscFixed;
}
.processmap_title___processmaker
{
	position	:absolute;
	cursor		:move;
	font		:bold 13pt Tahoma,MiscFixed;
}
	/*theme processmap end*/
.processmap_toolbarItem___processmaker
{
	margin:0px;
	border:1px solid #CEC6B5;
	background:#EFEFEF url(/images/dynamicForm/toolbar.buttonbg.gif) repeat-x scroll 0%;
}
.processmap_toolbarItem___processmaker:hover
{
	margin:0px;
	background-color:buttonface;
	border-color:buttonhighlight buttonshadow buttonshadow buttonhighlight;
	border-style:solid;
	/*border-width:1px 0pt;*/
	border:1px solid #316AC5;
	background:#C1D2EE;
	background-color: #C1D2EE;


}
/* processmap Theme.Task end */

/* processmap Theme.Panels begin */
/* processmap Theme.Panels end */


/*	Theme leimnud.module.grid	BEGIN */

/*	Theme leimnud.module.grid	END */


/* Menues */


TD.mainMenu {
	background-image: url(/skins/green/images/bm.jpg);
	background-repeat: repeat-x;
	height: 25px;
	left: 46px;
	top: 72px;
}

A.mainMenu {
	font-family: Tahoma;
	font-size: 11px;
	color: #FFFFFF;
	font-weight: bold;
	padding-top: 6px;
	text-transform:uppercase;
  text-decoration: none;
}
A.mainMenu:hover {
  color: #D3D3D3;
  font-weight: bold;
  text-decoration: none;
}

TD.SelectedMenu{
	background-image: url(/skins/green/images/bsms.jpg);
	background-repeat: repeat-x;
	top: 72px;
	height: 26px;
}
A.SelectedMenu {
	vertical-align: middle;
	font-family: Tahoma;
	font-size: 11px;
	font-weight: bold;
	color: #000;
	text-align: center;
	padding-top: 6px;
	text-transform:uppercase;
  text-decoration: none;
}

.SelectedMenu:hover {
	vertical-align: middle;
	font-family: Tahoma;
	font-size: 11px;
	font-weight: bold;
	color: #005791;
	text-align: center;
	padding-top: 6px;
	text-transform:uppercase;
  text-decoration: none;
}


TD.subMenu {
	background-image: url('/skins/green/images/bsm.jpg');
	background-repeat: repeat-x;
	height: 25px;
}

A.subMenu {
	font-family: Tahoma;
	font-size: 10px;
	color: #005791;
	font-weight: bold;
	padding-top: 5px;
	text-transform:uppercase;
	text-decoration: none;
}

A.subMenu:hover {
  font-family: Tahoma;
	font-size: 10px;
	color: #092148;
	font-weight: bold;
	padding-top: 5px;
	text-transform:uppercase;
	text-decoration: none;
}

TD.selectedSubMenu {
	background-image: url('/images/silverBackgroundSubMenu.jpg');
	background-repeat: repeat-x;
	height: 26px;
	left: 46px;
	top: 98px;
}

A.selectedSubMenu {
	font-family: Tahoma;
	font-size: 10px;
	color: #005791;
	font-weight: bold;
	padding-top: 5px;
	text-transform:uppercase;
	text-decoration: none;
}

A.selectedSubMenu:hover {
	font-family: Tahoma;
	font-size: 10px;
	color: #005791;
	font-weight: bold;
	padding-top: 5px;
	text-transform:uppercase;
	text-decoration: none;
}


/* Menues END */

/* Box Top Model BEGIN */
.boxTop, .boxTopBlue
{
	height:9px;
	padding-left:8px;
	padding-right:8px;
	position:relative;
	overflow:hidden;
}
.boxTop div, .boxTopBlue div
{
	background-color:#FFF;
}
.boxTop div.a, .boxTop div.c, .boxTopBlue div.a, .boxTopBlue div.c
{
	position:absolute;
	width:9px;
	height:9px;
}
.boxTop div.a, .boxTopBlue div.a
{
	left:0px;
	top:0px;
	background-image:url(/skins/green/images/ftl.png);
	background-color:transparent;
}
.boxTop div.c, .boxTopBlue div.c
{
	top:0px;
	right:0px;
	background-image:url(/skins/green/images/ftr.png);
	background-color:transparent;
}
.boxTop div.b, .boxTopBlue div.b
{
	width:100%;
	height:9px;
	border-top:1px solid #DADADA;
	background-color:#FFF;
}
/* Box Top Model END */

/* Box Top Model Blue BEGIN */
.boxTopBlue div.c
{
	background-image:url(/skins/green/images/ftr.blue.gif);
	background-color:transparent;
}
.boxTopBlue div.a
{
	background-image:url(/skins/green/images/ftl.blue.gif);
	background-color:transparent;
}
.boxTopBlue div.b
{
	border-top:1px solid #99BBE8;
	background-color:#D0DEF0;
}

/* Box Top Model Blue END */

/* Box Bottom Model BEGIN */
.boxBottom, .boxBottomBlue
{
	height:15px;
	padding-left:24px;
	padding-right:24px;
	position:relative;
	overflow:hidden;
}
.boxBottom div.a, .boxBottom div.c, .boxBottomBlue div.a, .boxBottomBlue div.c
{
	position:absolute;
	width:25px;
	height:15px;
}
.boxBottom div.a, .boxBottomBlue div.a
{
	left:0px;
	top:0px;
	background-image:url(/skins/green/images/fbl.png);
	background-color:transparent;
}
.boxBottom div.c, .boxBottomBlue div.c
{
	top:0px;
	right:0px;
	background-image:url(/skins/green/images/fbr.png);
	background-color:transparent;
}
.boxBottom div.b, .boxBottomBlue div.b
{
	width:100%;
	height:16px;
	border-bottom:1px solid #DADADA;
	background: transparent url(/skins/green/images/fbc.png) repeat-x;
}
/* Box Bottom Model END */
/* Box Bottom Model Blue BEGIN */
.boxBottomBlue div.c
{
	background-image:url(/skins/green/images/fbr.blue.png);
	background-color:transparent;
}
.boxBottomBlue div.a
{
	background-image:url(/skins/green/images/fbl.blue.png);
	background-color:transparent;
}
.boxBottomBlue div.b
{
	width:100%;
	height:16px;
	border-bottom:1px solid #DADADA;
	background: transparent url(/skins/green/images/fbc.blue.png) repeat-x;
}
.boxContentBlue
{
	border-left:1px solid #99BBE8;
	border-right:1px solid #99BBE8;
	padding-right:5px;
	padding-left:5px;
	background-color:#D0DEF0;
}
a.linkInBlue
{
	font:normal 8pt Tahoma,MiscFixed;
	color:#006699;
	text-decoration:none;
}

a.linkInBlue:hover
{
	color:orange;
}
/* Box Bottom Model Blue END */

/* BoxPanel Bottom Model BEGIN */
.boxTopPanel
{
	height:15px;
	padding-left:24px;
	padding-right:24px;
	position:relative;
	overflow:hidden;
}
.boxTopPanel div.a, .boxTopPanel div.c
{
	position:absolute;
	width:25px;
	height:15px;
}
.boxTopPanel div.a
{
	left:0px;
	top:0px;
	background-image:url(/skins/green/images/ftlL.png);
	background-color:transparent;
}
.boxTopPanel div.c
{
	top:0px;
	right:0px;
	background-image:url(/skins/green/images/ftrL.png);
	background-color:transparent;
}
.boxTopPanel div.b
{
	width:100%;
	height:16px;
	background: transparent url(/skins/green/images/ftc.png) repeat-x;
}

/* BoxPanel Bottom Model END */



/* XmlForm BEGIN  */
	 /* form BEGIN  */
form{
	font:normal 11px sans-serif,MiscFixed;
	color:#808080;
}
form table{
	font:normal 11px sans-serif,MiscFixed;
	color:#808080;
}
form.formDefault select
{
	font:normal 11px sans-serif,MiscFixed;
	color:#000;
}
form.formDefault table
{
	font:normal 11px sans-serif,MiscFixed;
	color:#808080;
	line-height:180%;
}
form.formDefault td
{
	padding:2px;
}
form.formDefault .content
{
	background-color:#FFF;
	border-left:1px solid #dadada;
	border-right:1px solid #dadada;
}
form.formDefault input.FormField
{
	border: 1px solid #CCC;
	background: #FFFFFF url(/skins/green/images/input_back.gif) repeat-x;
	color:#333333;
	font:normal 11px Arial,Helvetica,sans-serif;
}
form.formDefault input.FormFieldInvalid
{
	border: 1px solid red;
}
form.formDefault input.FormFieldValid
{
	border: 1px solid green;
}
form.formDefault .FormLabel
{
	color:#808080;
	text-align:right;
	padding-right:10px;
}
form.formDefault .FormFieldContent
{
	color:#000;
	background-color:#EFEFEF;
	padding-left:5px;
}
form.formDefault textarea.FormTextArea
{
	border: 1px solid #CCC;
	background: #FFFFFF url(/skins/green/images/input_back.gif) repeat-x;
	color:#333333;
	font:normal 11px Arial,Helvetica,sans-serif;
	overflow:auto;
}
form.formDefault .FormTitle
{
	color:#000;
	padding-left:5px;
	font-weight:bold;
	background-color:#E0EFE6;
}
form.formDefault .FormSubTitle
{
	background-color:#D1DEDF;
	color:black;
}
form.formDefault .FormButton
{
	text-align:center;
}

form.formDefault a
{
	text-decoration:none;
	color:#006699;
}
form.formDefault a:hover
{
	color:orange;
}
form.formDefault td.withoutLabel, form.formDefault td.withoutLabel table td
{
	padding:0px;
	height:8px;
}

	 /* form END  */
	 /* formSearch BEGIN  */
form.formSearch input, input
{
	font-size:8pt;
}
form.formSearch a
{
	color:#006699;
	text-decoration:none;
}
form.formSearch a:hover
{
	color:orange;
}
form.formSearch
{
	padding-top:5px;
	padding-bottom:5px;
/*	width:100%;*/
	padding-left:10px;
	padding-right:10px;
/*	padding-left:10%;
	padding-right:10%;*/
}
form.formSearch .content
{
	border-left:1px solid #99BBE8;
	border-right:1px solid #99BBE8;
	background-color:#D0DEF0;
	padding:10px;
}
form.formSearch input.FormField
{
	border: 1px solid #99BBE8;
	color:#333333;
	font:normal 11px Arial,Helvetica,sans-serif;
	width:200px;
}
form.formSearch .FormLabel
{
	color:#000;
	text-align:center;
	padding-right:10px;
	width:40%;
}
form.formSearch .FormFieldContent
{
	color:#000;
	background-color:#EFEFEF;
	padding-left:5px;
	width:60%;
}

form.formSearch .Record
{
	width:80%;
}
	 /* formSearch END  */


	 /* pagedTable BEGIN  */
.pagedTableDefault
{
	border-left:1px solid #DADADA;
	border-right:1px solid #DADADA;
	background-color:#FFF;
	padding-left:5px;
	padding-right:5px;
}
.pagedTableDefault, .pagedTableDefault table
{
	font:normal 11px sans-serif,MiscFixed;
	color:#808080;
}
.pagedTableDefault, .pagedTableDefault .headerContent .tableOption a
{
	color:#2078A8;
	text-decoration:none;
}
.pagedTableDefault, .pagedTableDefault .headerContent .tableOption a:hover
{
	color:orange;
}
.pagedTableDefault td
{
	padding:0px;
}
.pagedTableDefault .pagedTable td
{
	padding:5px;
}
.pagedTableDefault .pagedTable
{
	border:1px solid #DFDFDF;
	border-collapse:collapse;
	color:#27373F;
}
.pagedTableDefault .pagedTable .Row1
{
	background-color:#FFF;
}
.pagedTableDefault .pagedTable .Row2
{
	background-color:#EEE;
}
.pagedTableDefault .pagedTable .RowPointer
{
	background-color:#E0EAEF;
}
.pagedTableDefault .cellSelected1
{
font-weight:bold;
}
.pagedTableDefault .cellSelected2
{
font-weight:bold;
}
.pagedTableDefault .pagedTable a
{
	color:#FFF;
}
.pagedTableDefault .pagedTable a:hover
{
	color:orange;
}
.pagedTableDefault .pagedTable .pagedTableHeader
{
	border-bottom:0px solid #DFDFDF;
	background-color:#E0E9EF;
	background-color:#6F7F75;
	color:#5B5B5B;
	font-weight:bold;
	background-image:url(/images/silverBackgroundTableTitle.jpg);
	background-repeat:repeat-x;
	height:26px;
	padding:0px;
	overflow:hidden;
}
.pagedTableDefault .pagedTable .pagedTableHeader a
{
	text-decoration:none;
	color:#5B5B5B;
	padding-left:5px;
	font:normal 8pt Tahoma, sans-serif,MiscFixed;
}
.pagedTableDefault .pagedTable .pagedTableHeader a:hover
{
	color:orange;
}
.pagedTableDefault .pagedTable .RowLink
{
	background-color:#6F7F75;
	text-align	:center;
}
A.firstPage {
	background-image: url('/images/firstPage.gif');
	background-repeat: no-repeat;
	background-position: center bottom;
	padding-left:21px;
	padding-top:20px;
	line-height:40px;
	text-decoration:none;
}
A.noFirstPage {
	background-image: url('/images/firstPage.gif');
	background-repeat: no-repeat;
	background-position: center bottom;
	padding-left:21px;
	padding-top:20px;
	line-height:40px;
	text-decoration:none;
}
A.previousPage {
	background-image: url('/images/previousPage.gif');
	background-repeat: no-repeat;
	background-position: center bottom;
	padding-left:21px;
	padding-top:20px;
	line-height:40px;
	text-decoration:none;
}
A.noPreviousPage {
	background-image: url('/images/previousPage.gif');
	background-repeat: no-repeat;
	background-position: center bottom;
	padding-left:21px;
	padding-top:20px;
	line-height:40px;
	text-decoration:none;
}
A.nextPage {
	background-image: url('/images/nextPage.gif');
	background-repeat: no-repeat;
	background-position: center bottom;
	padding-left:21px;
	padding-top:20px;
	line-height:40px;
	text-decoration:none;
}
A.noNextPage {
	background-image: url('/images/nextPage.gif');
	background-repeat: no-repeat;
	background-position: center bottom;
	padding-left:21px;
	padding-top:20px;
	line-height:40px;
	text-decoration:none;
}
A.lastPage {
	background-image: url('/images/lastPage.gif');
	background-repeat: no-repeat;
	background-position: center bottom;
	padding-left:21px;
	padding-top:20px;
	line-height:40px;
	text-decoration:none;
}
A.noLastPage {
	background-image: url('/images/lastPage.gif');
	background-repeat: no-repeat;
	background-position: center bottom;
	padding-left:21px;
	padding-top:20px;
	line-height:40px;
	text-decoration:none;
}

	 /* pagedTable END  */
 	 /* Grid BEGIN */
div.pattern .content
{
	padding-left:5px;
	padding-right:5px;
	border-left:1px solid #DADADA;
	border-right:1px solid #DADADA;
	background-color:#FFF;
}
div.pattern .FormTitle
{
	font-weight:bold;
	color: black;
	background-color:#E0EFE6;
	padding:2px;
}


div.grid
{
	font:normal 11px sans-serif,MiscFixed;
	padding-left:10px;
	padding-right:10px;
	margin-top:7px;
}

div.grid .content
{
	padding-left:5px;
	padding-right:5px;
	border-left:1px solid #DADADA;
	border-right:1px solid #DADADA;
	background-color:#FFF;
}

div.grid .tableGrid
{
	width:100%;
}
div.grid .tableGrid .vFormTitle
{
/*	color:#006699;*/
}
	 /* Grid END */
	 /* Tree BEGIN */

div.treeBase .content
{
	padding-left:5px;
	padding-right:5px;
	border-left:1px solid #DADADA;
	border-right:1px solid #DADADA;
	background-color:#FFF;
}
.treeNode
{
	padding-bottom:10px;
	font:normal 8pt Tahoma,sans-serif,MiscFixed;
	color:#808080;
}
div.treeBase table td.a
{
	width:16px;
	height:10px;
}
div.treeBase table td.b
{
	font:normal 8pt Tahoma,sans-serif,MiscFixed;
	color:black;
	text-align:left;
}
/* TreeParent BEGIN */
div.treeParent
{
	position:relative;
}
div.treeParent table
{
	font:normal 8pt Tahoma,sans-serif,MiscFixed;
	color:black;
}

div.treeParent .treeMinus
{
	width:16px;
	height:22px;
	display:block;
	position:relative;
	background-image:url(/images/minus.gif);
	background-repeat:no-repeat;
	overflow:hidden;
}
div.treeParent .treePlus
{
	width:16px;
	height:22px;
	background-image:url(/images/plus.gif);
	background-repeat:no-repeat;
	display:block;
}
div.treeParent .treePointer
{
	width:1px;
	height:22px;
	display:block;
}
div.treeParent td.c
{
	background-image:url(/images/ftv2vertline.gif);
	background-repeat:repeat-y;
}
div.treeParent td.d
{
	background-image:url(/images/ftv2node.gif);
	background-repeat:repeat-y;
}
div.treeParent .treeNode
{
	padding:2px;
	padding-bottom:1px;
	font-weight:bold;
}



div.treeParent .treeNode a, div.treeBase .treeNode a
{
	color:#006699;
	font-weight:normal;
	text-decoration:none;
}
div.treeParent .treeNode a:hover, div.treeBase .treeNode a:hover
{
	color:orange;
}
div.treeParent .FormField
{
	background-color:#FFF;
	padding-bottom:10px;
}
div.treeParent .FormField a
{
	font-size:8pt !important;
	color:red;
}
div.treeParent .FormTitle
{
/*	font:bold 8pt sans-serif,Tahoma,MiscFixed;
	font:normal 11px sans-serif,MiscFixed;*/
	font-weight:bold;
	color: black;
	background-color:#E0EFE6;
	padding:2px;
}
/* TreeParent END */

/* TreeChild BEGIN */
div.treeChild td.b
{
	padding-right:10px;
}
div.treeChild td.a
{
	width:10px;
	height:22px;
	background:none;
}
div.treeChild .c
{
	background-image:url(/images/ftv2vertline.gif);
	background-repeat:repeat-y;
}
/* TreeChild END */


div.treeChild
{
	padding-left:1px;
}
div.treeParent .content .treeNode a.selected
{
	color:white;
	background-color:#006699;
}

/*div.treeParent div.treeParent .content
{
	border-left:1px solid #99BBE8;
	border-right:1px solid #99BBE8;
	background-color:#D0DEF0;
}
div.treeParent div.treeParent .boxTop div.a
{
	background-image:url(/skins/green/images/ftl.blue.gif);
}
div.treeParent div.treeParent .boxTop div.b
{
	border-top:1px solid #99BBE8;
	background-color:#D0DEF0;
}
div.treeParent div.treeParent .boxTop div.c
{
	background-image:url(/skins/green/images/ftr.blue.gif);
}
div.treeParent div.treeParent .boxBottom div.a
{
	background-image:url(/skins/green/images/fbl.blue.png);
}
div.treeParent div.treeParent .boxBottom div.b
{
	background-image:url(/skins/green/images/fbc.blue.png);
}
div.treeParent div.treeParent .boxBottom div.c
{
	background-image:url(/skins/green/images/fbr.blue.png);
}*/




div.treeParent table
{

}
div.treeParent .subcontent
{
	padding-left:5px;
	padding-right:5px;
	border-left:1px solid #99BBE8;
	border-right:1px solid #99BBE8;
	background-color:#D0DEF0;
}
	 /* Tree END */
/* XmlForm END  */
