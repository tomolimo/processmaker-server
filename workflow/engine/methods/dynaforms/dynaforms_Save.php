<?php
/**
 * dynaforms_Save.php
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
if (($RBAC_Response = $RBAC->userCanAccess( "PM_FACTORY" )) != 1) {
    return $RBAC_Response;
}
//G::genericForceLogin( 'WF_MYINFO' , 'login/noViewPage', $urlLogin = 'login/login' );


require_once ("classes" . PATH_SEP . "model" . PATH_SEP . "Dynaform.php");
require_once ("classes" . PATH_SEP . "model" . PATH_SEP . "FieldCondition.php");
require_once ("classes" . PATH_SEP . "model" . PATH_SEP . "Content.php");

if (isset( $_POST['function'] )) {
    $sfunction = $_POST['function'];
} elseif (isset( $_POST['functions'] )) {
    $sfunction = $_POST['functions'];
}

if (isset( $sfunction ) && $sfunction == 'lookforNameDynaform') {
    $oDynaform = new Dynaform();
    print $oDynaform->verifyExistingName($_POST['NAMEDYNAFORM'], $_POST['proUid']);

} else {
    if (isset( $_POST['form'] )) {
        $aData = $_POST['form']; //For old process map form
        if ($aData['DYN_UID'] === '') {
            unset( $aData['DYN_UID'] );
        }
    } else {
        $aData = $_POST; //For Extjs (Since we are not using form in ExtJS)
        $aFields = array ();
        $aVariables = array ();
        if (isset( $aData['FIELDS'] )) {
            $aFields = G::json_decode( $_POST['FIELDS'] );
            $aVariables = G::json_decode( $_POST['VARIABLES'] );
        }
        $aData['FIELDS'] = array ();
        for ($i = 0; $i < count( $aFields ); $i ++) {
            $aData['FIELDS'][$i + 1]['FLD_NAME'] = $aFields[$i];
            $aData['FIELDS'][$i + 1]['PRO_VARIABLE'] = $aVariables[$i];
        }
    }
    //if ($aData['DYN_UID']==='') unset($aData['DYN_UID']);

    $dynaform = new Dynaform();
    $dynaFormAux = new ProcessMaker\BusinessModel\DynaForm();

    if (isset($aData["DYN_UID"])) {
        $dynaform->Save($aData);
    } else {
        switch ($aData["ACTION"]) {
            case "copy":
                $aData["DYN_TYPE"] = $aData["COPY_TYPE"];
                $aData["DYN_TITLE"] = $aData["COPY_DYNAFORM_TITLE"];
                $aData["DYN_DESCRIPTION"] = $aData["COPY_DYNAFORM_DESCRIPTION"];

                $aFields = $dynaform->create($aData);

                $dynaformUid = $dynaform->getDynUid();

                //Copy files of the dynaform
                $umaskOld = umask(0);

                $fileXml = PATH_DYNAFORM . $aData["COPY_PROCESS_UID"] . PATH_SEP . $aData["COPY_DYNAFORM_UID"] . ".xml";

                if (file_exists($fileXml)) {
                    $fileXmlCopy = PATH_DYNAFORM . $aData["PRO_UID"] . PATH_SEP . $dynaformUid . ".xml";

                    $fhXml = fopen($fileXml, "r");
                    $fhXmlCopy = fopen($fileXmlCopy, "w");

                    while (!feof($fhXml)) {
                        $strLine = fgets($fhXml, 4096);
                        $strLine = str_replace($aData["COPY_PROCESS_UID"] . "/" . $aData["COPY_DYNAFORM_UID"], $aData["PRO_UID"] . "/" . $dynaformUid, $strLine);

                        //Dynaform grid
                        preg_match_all("/<.*type\s*=\s*[\"\']grid[\"\'].*xmlgrid\s*=\s*[\"\']\w{32}\/(\w{32})[\"\'].*\/>/", $strLine, $arrayMatch, PREG_SET_ORDER);

                        foreach ($arrayMatch as $value) {
                            $copyDynaformGridUid = $value[1];

                            //Get data
                            $dynaFormData = new \Dynaform();
                            $row = $dynaFormData->Load($copyDynaformGridUid);
                            $copyDynGrdTitle = $row["DYN_TITLE"];
                            $copyDynGrdDescription = $row["DYN_DESCRIPTION"];

                            //Create grid
                            $dynaformGrid = new Dynaform();

                            $aDataAux = $aData;
                            $aDataAux["DYN_TYPE"] = "grid";
                            $aDataAux["DYN_TITLE"] = $copyDynGrdTitle . (($dynaFormAux->existsTitle($dynaform->getProUid(), $copyDynGrdTitle))? " (" . $dynaform->getDynTitle() . ")" : "");
                            $aDataAux["DYN_DESCRIPTION"] = $copyDynGrdDescription;

                            $aFields = $dynaformGrid->create($aDataAux);

                            $dynaformGridUid = $dynaformGrid->getDynUid();

                            $aDataAux["DYN_UID"] = $dynaformGridUid;

                            $dynaformGrid->update($aDataAux);

                            //Copy files of the dynaform grid
                            $fileGridXml = PATH_DYNAFORM . $aData["COPY_PROCESS_UID"] . PATH_SEP . $copyDynaformGridUid . ".xml";

                            if (file_exists($fileGridXml)) {
                                $fileGridXmlCopy = PATH_DYNAFORM . $aData["PRO_UID"] . PATH_SEP . $dynaformGridUid . ".xml";

                                $fhGridXml = fopen($fileGridXml, "r");
                                $fhGridXmlCopy = fopen($fileGridXmlCopy, "w");

                                while (!feof($fhGridXml)) {
                                    $strLineAux = fgets($fhGridXml, 4096);
                                    $strLineAux = str_replace($aData["COPY_PROCESS_UID"] . "/" . $copyDynaformGridUid, $aData["PRO_UID"] . "/" . $dynaformGridUid, $strLineAux);

                                    fwrite($fhGridXmlCopy, $strLineAux);
                                }

                                fclose($fhGridXmlCopy);
                                fclose($fhGridXml);

                                chmod($fileGridXmlCopy, 0777);
                            }

                            $fileGridHtml = PATH_DYNAFORM . $aData["COPY_PROCESS_UID"] . PATH_SEP . $copyDynaformGridUid . ".html";

                            if (file_exists($fileGridHtml)) {
                                $fileGridHtmlCopy = PATH_DYNAFORM . $aData["PRO_UID"] . PATH_SEP . $dynaformGridUid . ".html";

                                copy($fileGridHtml, $fileGridHtmlCopy);

                                chmod($fileGridHtmlCopy, 0777);
                            }

                            $strLine = str_replace($aData["COPY_PROCESS_UID"] . "/" . $copyDynaformGridUid, $aData["PRO_UID"] . "/" . $dynaformGridUid, $strLine);
                        }

                        fwrite($fhXmlCopy, $strLine);
                    }

                    fclose($fhXmlCopy);
                    fclose($fhXml);

                    chmod($fileXmlCopy, 0777);
                }

                $fileHtml = PATH_DYNAFORM . $aData["COPY_PROCESS_UID"] . PATH_SEP . $aData["COPY_DYNAFORM_UID"] . ".html";

                if (file_exists($fileHtml)) {
                    $fileHtmlCopy = PATH_DYNAFORM . $aData["PRO_UID"] . PATH_SEP . $dynaformUid . ".html";

                    copy($fileHtml, $fileHtmlCopy);

                    chmod($fileHtmlCopy, 0777);
                }

                //Copy if there are conditions attached to the dynaform
                $fieldCondition = new FieldCondition();
                $arrayCondition = $fieldCondition->getAllByDynUid($aData["COPY_DYNAFORM_UID"]);

                foreach ($arrayCondition as $condition) {
                    $condition["FCD_UID"] = "";
                    $condition["FCD_DYN_UID"] = $dynaformUid;

                    $fieldCondition->quickSave($condition);
                }

                umask($umaskOld);
                break;
            default:
                //normal
                //pmtable
                if (!isset($aData["ADD_TABLE"]) || $aData["ADD_TABLE"] == "") {
                    $aFields = $dynaform->create($aData);
                } else {
                    $aFields = $dynaform->createFromPMTable($aData, $aData["ADD_TABLE"]);
                }
                break;
        }

        $aData["DYN_UID"] = $dynaform->getDynUid();

        $dynaform->update($aData);
    }

    echo $dynaform->getDynUid();
}

