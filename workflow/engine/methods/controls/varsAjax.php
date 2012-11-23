<?php
/**
 * varsAjax.php
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 */

$html = '<form action="uploader.php?'.$_SERVER["QUERY_STRING"].'&q=upload" method="post" enctype="multipart/form-data" onsubmit="">';

$html .= '<div id="d_variables">';

$html .= '<table width="90%" align="center">';
$html .= '<tr>';
$html .= '<td width="50%">';
$html .= '<label for="type_label">Type Variable</label>';
$html .= '</td>';

$html .= '<td width="25%">';
$html .= '<label for="prefix_label">Prefix</label>';
$html .= '</td>';

$html .= '<td width="25%">';
$html .= '<label for="variables_label">Search</label>';
$html .= '</td>';
$html .= '</tr>';

$html = '<form action="uploader.php?'.$_SERVER["QUERY_STRING"].'&q=upload" method="post" enctype="multipart/form-data" onsubmit="">';

$html .= '<div id="d_variables">';
$html .= '<table width="90%" align="center">';
$html .= '<tr>';
$html .= '<td width="50%">';
$html .= '<label for="type_label">Type Variable</label>';
$html .= '</td>';

$html .= '<tr>';
$html .= '<td width="25%">';
$html .= '<select name="type_variables" id="type_variables">';
$html .= '<option value="all">All Variables</option>';
$html .= '<option value="system">System Variables</option>';
$html .= '<option value="process">Process Variables</option>';
$html .= '</select> &nbsp;&nbsp;&nbsp;&nbsp;';
$html .= '</td>';

$html .= '<td width="25%">';
//$html .= '<select name="prefix" id="prefix" onChange="Seleccionar(this);">';
$html .= '<select name="prefix" id="prefix">';
$html .= '<option value="ID_TO_FLOAT">@#</option>';
$html .= '<option value="ID_TO_STRING">@@</option>';
$html .= '<option value="ID_TO_INTEGER">@%</option>';
$html .= '<option value="ID_TO_URL">@?</option>';
$html .= '<option value="ID_SQL_ESCAPE">@$</option>';
$html .= '<option value="ID_REPLACE_WITHOUT_CHANGES">@=</option>';
$html .= '</select> &nbsp;&nbsp;&nbsp;&nbsp;';
$html .= '</td>';

$html .= '<td width="20%" valign="top">';
$html .= '<input type="text" id="search" size="15">';
$html .= '</td>';
$html .= '</tr>';

$html .= '<br>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr>';

$html .= '<br>';
$html .= '<tr><td><label for="prefix_label">Variables</label></td></tr>';
$html .= '<tr>';
//onChange="Seleccionar(this);
$html .= '<td colspan="3">';

G::LoadClass( 'xmlfield_InputPM' );
$aFields = getDynaformsVars( $_POST['sProcess'], true, isset( $_POST['bIncMulSelFields'] ) ? $_POST['bIncMulSelFields'] : 0 );

//$html .= '<select name="_Var_Form_" id="_Var_Form_" size="' . count( $aFields ) . '" style="width:100%;' . (! isset( $_POST['sNoShowLeyend'] ) ? 'height:50%;' : '') . '" ondblclick="insertFormVar(\'' . $_POST['sFieldName'] . '\', this.value);">';

$html .= '<select name="_Var_Form_" id="_Var_Form_" size="4"  style="width:100%;' . (! isset( $_POST['sNoShowLeyend'] ) ? 'height:50%;' : '') . '" ondblclick="getValue(this);">';

foreach ($aFields as $aField) {
    $html .= '<option value="' . $_POST['sSymbol'] . $aField['sName'] . '">' . $_POST['sSymbol'] . $aField['sName'] . ' (' . $aField['sType'] . ')</option>';
}

$aRows[0] = Array ('fieldname' => 'char','variable' => 'char','type' => 'type','label' => 'char');
foreach ($aFields as $aField) {
    $aRows[] = Array ('fieldname' => $_POST['sFieldName'],'variable' => $_POST['sSymbol'] . $aField['sName'],'variable_label' => '<div class="pm__dynavars"> <a id="dynalink" href=# onclick="insertFormVar(\'' . $_POST['sFieldName'] . '\',\'' . $_POST['sSymbol'] . $aField['sName'] . '\');">' . $_POST['sSymbol'] . $aField['sName'] . '</a></div>','type' => $aField['sType'],'label' => $aField['sLabel']
    );
}
$html .= '</select>';

$html .= '</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</div>';

$html .= '<br>';
$html .= '<div id="desc_variables">';
$html .= '<table border="1" width="90%" align="center">';
$html .= '<tr width="40%">';
$html .= '<td>Result</td>';
$html .= '<td>@#SYS_LANG</td>';
$html .= '</tr>';
$html .= '<tr width="60%">';
$html .= '<td>Description</td>';
$html .= '<td>Description @#SYS_LANG</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</div>';
$html .= '<br>';
$html .= '<div id="desc_variables">';
$html .= '<table width="90%" align="center">';
$html .= '<tr><td>';
$html .= '<label for="desc_prefix">' . G::LoadTranslation( 'ID_TO_FLOAT' ) . '</label>';
$html .= '</td></tr>';
$html .= '</div>';

$html .= '</form>';


$G_PUBLISH = new Publisher();
$oHeadPublisher = & headPublisher::getSingleton();
$oHeadPublisher->addScriptFile('/jscore/controls/variablePicker.js');
echo $html;

G::RenderPage( 'publish', 'raw' );
/*
$alll = '<script type="text/javascript" language="javascript">';
$alll .= 'function Seleccionar(combo){';
$alll .= 'alert(combo.value);';
$alll .= '}';
$alll .= '</script>';

echo $alll;
*/



//echo var_dump($aFields);
/*
$sHTML = '<select name="_Var_Form_" id="_Var_Form_" size="' . count( $aFields ) . '" style="width:100%;' . (! isset( $_POST['sNoShowLeyend'] ) ? 'height:50%;' : '') . '" ondblclick="insertFormVar(\'' . $_POST['sFieldName'] . '\', this.value);">';
foreach ($aFields as $aField) {
    $html .= '<option value="' . $_POST['sSymbol'] . $aField['sName'] . '">' . $_POST['sSymbol'] . $aField['sName'] . ' (' . $aField['sType'] . ')</option>';
}

$aRows[0] = Array ('fieldname' => 'char','variable' => 'char','type' => 'type','label' => 'char'
);
foreach ($aFields as $aField) {
    $aRows[] = Array ('fieldname' => $_POST['sFieldName'],'variable' => $_POST['sSymbol'] . $aField['sName'],'variable_label' => '<div class="pm__dynavars"> <a id="dynalink" href=# onclick="insertFormVar(\'' . $_POST['sFieldName'] . '\',\'' . $_POST['sSymbol'] . $aField['sName'] . '\');">' . $_POST['sSymbol'] . $aField['sName'] . '</a></div>','type' => $aField['sType'],'label' => $aField['sLabel']
    );
}

$html .= '</select>';

$html .= '</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</div>';

$html .= '<br>';
$html .= '<div id="desc_variables">';
$html .= '<table border="1" width="90%" align="center">';
$html .= '<tr width="40%">';
$html .= '<td>Result</td>';
$html .= '<td>@#SYS_LANG</td>';
$html .= '</tr>';
$html .= '<tr width="60%">';
$html .= '<td>Description</td>';
$html .= '<td>Description @#SYS_LANG</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</div>';
$html .= '<br>';
$html .= '<div id="desc_variables">';
$html .= '<table width="90%" align="center">';
$html .= '<tr><td>';
$html .= '<label for="desc_prefix">' . G::LoadTranslation( 'ID_TO_FLOAT' ) . '</label>';
$html .= '</td></tr>';
$html .= '</div>';

$html .= '</form>';

echo $html;

G::RenderPage( 'publish', 'raw' );

/*$G_PUBLISH->AddContent( 'propeltable', 'paged-table', 'triggers/dynavars', $oCriteria );
G::RenderPage( 'publish', 'raw' );
*/
