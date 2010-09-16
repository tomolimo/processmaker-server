<?php
/**
 * languages_Export.php
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
require_once 'classes/model/IsoCountry.php';
require_once 'classes/model/Language.php';
require_once 'classes/model/Translation.php';

//export translation
$aLabels = array();
$aMsgids = array();
$oCriteria = new Criteria('workflow');
$oCriteria->addSelectColumn(TranslationPeer::TRN_CATEGORY);
$oCriteria->addSelectColumn(TranslationPeer::TRN_ID);
$oCriteria->addSelectColumn(TranslationPeer::TRN_VALUE);
$oCriteria->add(TranslationPeer::TRN_LANG, 'en');
$oDataset = TranslationPeer::doSelectRS($oCriteria);
$oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
$oDataset->next();

while ($aRow1 = $oDataset->getRow()) {
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(TranslationPeer::TRN_VALUE);
    $oCriteria->add(TranslationPeer::TRN_CATEGORY, $aRow1['TRN_CATEGORY']);
    $oCriteria->add(TranslationPeer::TRN_ID, $aRow1['TRN_ID']);
    $oCriteria->add(TranslationPeer::TRN_LANG, $_GET['LAN_ID']);
    
    $oDataset2 = TranslationPeer::doSelectRS($oCriteria);
    $oDataset2->setFetchmode(ResultSet::FETCHMODE_ASSOC);

    $oDataset2->next();
    $aRow2 = $oDataset2->getRow();

    $aRow1['TRN_CATEGORY'] = trim($aRow1['TRN_CATEGORY']);

    # Validation
    # implemented to validate that the TRN_CATEGORY field into TRANSLALTION table is valid
    # By Erik A. Ortiz <erik@colosa.com> on Thu Feb 4, 2010
    preg_match("/^[0-9a-zA-Z_-]+/", $aRow1['TRN_CATEGORY'], $sTestResult);
    
    if( $sTestResult[0] === $aRow1['TRN_CATEGORY']){ #the regular expr. evaluated ()$sTestResult) for $aRow1['TRN_CATEGORY'] must be the same
        $msgid = $aRow1['TRN_VALUE'];
        
        if (in_array($msgid, $aMsgids)) {
            $msgid = '[' . $aRow1['TRN_CATEGORY'] . '/' . $aRow1['TRN_ID'] . '] ' . $msgid;
        }
        $aLabels[] = array(
            0 => '# TRANSLATION',
            1 => '# ' . $aRow1['TRN_CATEGORY'] . '/' . $aRow1['TRN_ID'],
            2 => '#: ' . $aRow1['TRN_CATEGORY'] . '/' . $aRow1['TRN_ID'],
            3 => 'msgid "' . str_replace('"', '\"', $msgid) . '"',
            4 => 'msgstr "' . str_replace('"', '\"', ($aRow2 ? ($aRow2['TRN_VALUE'] != '' ? $aRow2['TRN_VALUE'] : $aRow1['TRN_VALUE']) : $aRow1['TRN_VALUE'])) . '"'
        );
        $aMsgids[] = $msgid;
    } else { #Autocleaning, delete the inconsistant data 
        $oTranslation = new Translation;
        $oTranslation->remove($aRow1['TRN_CATEGORY'], $aRow1['TRN_ID'], $_GET['LAN_ID']);
    }
    $oDataset->next();
}

//now find labels in xmlforms
G::LoadThirdParty('pake', 'pakeFinder.class');
$aExceptionFields = array('', 'javascript', 'hidden', 'phpvariable', 'private', 'toolbar', 'xmlmenu', 'toolbutton', 'cellmark', 'grid');
$aXMLForms        = pakeFinder::type('file')->name( '*.xml' )->in(substr(PATH_XMLFORM, 0, -1));
$aEnglishLabel    = array();
$aOptions         = array();

foreach ($aXMLForms as $sXmlForm) {
	$sXmlForm = str_replace( chr(92), '/', $sXmlForm);
	$sXmlForm = str_replace( PATH_XMLFORM, '', $sXmlForm);
	$oForm = new Form( $sXmlForm, '', 'en');
	
	//get all fields of each xmlform
	foreach ($oForm->fields as $sNodeName => $oNode) {
		if (is_object($oNode)) {
		    if (trim($oNode->label) != '') {
                $aEnglishLabel[$oNode->name] = str_replace('"', '\"', stripslashes(ltrim(str_replace(chr(10), '', $oNode->label))));
                $aOptions[$sXmlForm . '?' . $oNode->name] = $aEnglishLabel[$oNode->name];
            }
            if (isset($oNode->options)) {
                if (!empty($oNode->options)) {
                    foreach ($oNode->options as $sKey => $sValue) {
                        $aEnglishLabel[$oNode->name . '-' . $sKey] = str_replace('"', '\"', stripslashes(ltrim(str_replace(chr(10), '', $sValue))));
                        if (isset($aOptions[$sXmlForm . '?' . $oNode->name])) {
                            if (!is_array($aOptions[$sXmlForm . '?' . $oNode->name])) {
                                $sAux = $aOptions[$sXmlForm . '?' . $oNode->name];
                                $aOptions[$sXmlForm . '?' . $oNode->name] = array();
                            }
                        }
                        $aOptions[$sXmlForm . '?' . $oNode->name][$sKey] = $aEnglishLabel[$oNode->name . '-' . $sKey];
                    }
                }
            }
        }
	} //end foreach
    unset($oForm->fields);
    unset($oForm->tree);
    unset($oForm);
  
    //now go to the fields array
    $oForm = new Form($sXmlForm, '', $_GET['LAN_ID']);
    $i = 1;
    $iNumberOfFields = count($oForm->fields);
    foreach ($oForm->fields as $sNodeName => $oNode) {
        if (is_object($oNode)) {
            if ( isset($aEnglishLabel[$oNode->name]) ) {
                $msgid = $aEnglishLabel[$oNode->name];
                $oNode->label = str_replace('"', '\"', stripslashes(ltrim(str_replace(chr(10), '', $oNode->label))));
            }
            else {
                $msgid = '';
            }
            
            if ( !in_array(strtolower($oNode->type), $aExceptionFields)) {
                if ((strpos($msgid, '@G::LoadTranslation') === false) && (strpos($oNode->label, '@G::LoadTranslation') === false)) {
                    if (in_array($msgid, $aMsgids)) {
                        $msgid = trim ( '[' . $sXmlForm . '?' . $oNode->name . '] ' . $oNode->label );
                    }
                    $aLabels[] = array(
                        0 => '# ' . $sXmlForm . '?' . $sNodeName,
                        1 => '# ' . $sXmlForm,
                        2 => '#: ' . $oNode->type . ' - ' . $sNodeName,
                        3 => 'msgid "' . $msgid . '"',
                        4 => 'msgstr "' . trim($oNode->label) . '"'
                    );
                    
                    $aMsgids[] = $msgid;
                    if (isset($oNode->options)) {
                        if (!empty($oNode->options)) {
                            foreach ($oNode->options as $sKey => $sValue) {
                                if ($sKey === '') {
                                    $sKey = "''";
                                }
                                $msgid = '[' . $sXmlForm . '?' . $oNode->name  . '-' . $sKey . ']';
                                $aLabels[] = array(
                                    0 => '# ' . $sXmlForm . '?' . $sNodeName . '-'. $sKey,
                                    1 => '# ' . $sXmlForm,
                                    2 => '#: ' . $oNode->type . ' - ' . $sNodeName . ' - ' . $sKey,
                                    3 => 'msgid "' . $msgid . '"',
                                    4 => 'msgstr "' . $sValue . '"'
                                );
                                $aMsgids[] = $msgid;
                            }
                        } else {
                            if (isset($aOptions[$sXmlForm . '?' . $sNodeName])) {
                                if (is_array($aOptions[$sXmlForm . '?' . $sNodeName])) {
                                    foreach ($aOptions[$sXmlForm . '?' . $sNodeName] as $sKey => $sValue) {
                                        if ($sKey === '') {
                                            $sKey = "''";
                                        }
                                        $msgid = '[' . $sXmlForm . '?' . $oNode->name  . '-' . $sKey . ']';
                                        $aLabels[] = array(
                                            0 => '# ' . $sXmlForm . '?' . $sNodeName . '-'. $sKey,
                                            1 => '# ' . $sXmlForm,
                                            2 => '#: ' . $oNode->type . ' - ' . $sNodeName . ' - ' . $sKey,
                                            3 => 'msgid "' . $msgid . '"',
                                            4 => 'msgstr "' . $sValue . '"'
                                        );
                                        $aMsgids[] = $msgid;
                                    }
                                }
                            }
                        }
                    } else {
                        if (isset($aOptions[$sXmlForm . '?' . $sNodeName])) {
                            if (is_array($aOptions[$sXmlForm . '?' . $sNodeName])) {
                                foreach ($aOptions[$sXmlForm . '?' . $sNodeName] as $sKey => $sValue) {
                                    if ($sKey === '') {
                                        $sKey = "''";
                                    }
                                    $msgid = '[' . $sXmlForm . '?' . $oNode->name  . '-' . $sKey . ']';
                                    $aLabels[] = array(
                                        0 => '# ' . $sXmlForm . '?' . $sNodeName . '-'. $sKey,
                                        1 => '# ' . $sXmlForm,
                                        2 => '#: ' . $oNode->type . ' - ' . $sNodeName . ' - ' . $sKey,
                                        3 => 'msgid "' . $msgid . '"',
                                        4 => 'msgstr "' . $sValue . '"'
                                    );
                                    $aMsgids[] = $msgid;
                                }
                            }
                        }
                    }
                }
            }
        }
        $i++;
    }
    unset($oForm->fields);
    unset($oForm->tree);
    unset($oForm);
}

$oCriteria = new Criteria('workflow');
$oCriteria->addSelectColumn(LanguagePeer::LAN_NAME);
$oCriteria->add(LanguagePeer::LAN_ID, $_GET['LAN_ID']);
$oDataset = LanguagePeer::doSelectRS($oCriteria);
$oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
$oDataset->next();
$aRow = $oDataset->getRow();
$sLanguage = $aRow['LAN_NAME'];
if ($_GET['LAN_ID'] != 'en') {
    $oCriteria = new Criteria('workflow');
    $oCriteria->addSelectColumn(IsoCountryPeer::IC_NAME);
    $oCriteria->add(IsoCountryPeer::IC_UID, strtoupper($_GET['LAN_ID']));
    $oDataset = IsoCountryPeer::doSelectRS($oCriteria);
    $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
    $oDataset->next();
    if ($aRow = $oDataset->getRow()) {
        $sCountry = $aRow['IC_NAME'];
    }
    else {
        $sCountry = '';
    }
}
else {
	$sCountry = 'United States';
}

$sPOFile = PATH_CORE . 'content' . PATH_SEP . 'translations' . PATH_SEP . MAIN_POFILE . '.' . $_GET['LAN_ID'] . '.po';
$oFile = fopen($sPOFile, 'w');
fprintf($oFile, "msgid \"\" \n");
fprintf($oFile, "msgstr \"\" \n");
fprintf($oFile, "\"Project-Id-Version: %s\\n\"\n", PO_SYSTEM_VERSION);
fprintf($oFile, "\"POT-Creation-Date: \\n\"\n");
fprintf($oFile, "\"PO-Revision-Date: %s \\n\"\n", date('Y-m-d H:i+0100'));
fprintf($oFile, "\"Last-Translator: Fernando Ontiveros<fernando@colosa.com>\\n\"\n");
fprintf($oFile, "\"Language-Team: Colosa Developers Team <developers@colosa.com>\\n\"\n");
fprintf($oFile, "\"MIME-Version: 1.0\\n\"\n");
fprintf($oFile, "\"Content-Type: text/plain; charset=utf-8\\n\"\n");
fprintf($oFile, "\"Content-Transfer_Encoding: 8bit\\n\"\n");
fprintf($oFile, "\"X-Poedit-Language: %s\\n\"\n", ucwords($sLanguage));
fprintf($oFile, "\"X-Poedit-Country: %s\\n\"\n", ucwords($sCountry));
fprintf($oFile, "\"X-Poedit-SourceCharset: utf-8\\n\"\n");
fprintf($oFile, "\"Content-Transfer-Encoding: 8bit\\n\"\n\n");
foreach ($aLabels as $aLabel) {
	fwrite($oFile, $aLabel[0] . "\n");
	fwrite($oFile, $aLabel[1] . "\n");
    fwrite($oFile, $aLabel[2] . "\n");
	fwrite($oFile, str_replace("\n", '', $aLabel[3]) . "\n");
	fwrite($oFile, str_replace("\n", '', $aLabel[4]) . "\n\n");
}

fclose($oFile);
G::streamFile($sPOFile, true);

