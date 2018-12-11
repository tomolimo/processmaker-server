<?php

use Cases as ClassesCases;
use ProcessMaker\Util\DateTime;

$actionAjax = isset($_REQUEST['actionAjax']) ? $_REQUEST['actionAjax'] : null;

switch ($actionAjax) {
    case 'historyGridList_JXP':
        global $G_PUBLISH;
        $appUid = $_SESSION['APPLICATION'];
        $case = new ClassesCases();
        $fields = $case->loadCase($appUid);
        $criteria = Cases::getTransferHistoryCriteria($fields['APP_NUMBER']);

        $dataSet = GulliverBasePeer::doSelectRs($criteria);
        $totalCount = $dataSet->getRecordCount();

        $start = $_REQUEST['start'];
        $limit = $_REQUEST['limit'];

        $criteria->setLimit($limit);
        $criteria->setOffset($start);

        $dataSet = GulliverBasePeer::doSelectRs($criteria);
        $dataSet->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $result = new stdClass();
        $process = [];
        while ($dataSet->next()) {
            $result = $dataSet->getRow();
            $result['ID_HISTORY'] = $result['PRO_UID'] . '_' . $result['APP_UID'] . '_' . $result['TAS_UID'];
            $process[] = $result;
        }

        $response = new stdclass();
        $response->data = DateTime::convertUtcToTimeZone($process);
        $response->totalCount = $totalCount;

        echo G::json_encode($response);
        break;
    case '':
        //!dataInput
        $idHistory = $_REQUEST['idHistory'];

        //!dataSytem
        $idHistoryArray = explode('*', $idHistory);
        $_REQUEST['PRO_UID'] = $idHistoryArray[0];
        $_REQUEST['APP_UID'] = $idHistoryArray[1];
        $_REQUEST['TAS_UID'] = $idHistoryArray[2];
        $_REQUEST['DYN_UID'] = '';

        ?>

        <table bgcolor="white" height=100% width=100%>
            <tr>
                <td height=99%>
                    <div style="width: 100%; overflow-y: scroll; overflow-x: hidden; max-height: 310px; _height: 310px; height: 310px; visibility: inherit;">
                        <?php
                        require_once 'classes/model/AppHistory.php';
                        $G_PUBLISH = new Publisher();
                        $G_PUBLISH->AddContent('view', 'cases/cases_DynaformHistory');
                        G::RenderPage('publish', 'raw');
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td height=30 valign=top>

                    <table align=center cellspacing="0" class="x-btn x-btn-noicon"
                           id="ext-comp-1043" style="width: 75px; margin-top: 0px;">
                        <tbody class="x-btn-small x-btn-icon-small-left">
                        <tr>
                            <td class="x-btn-tl"><i>&nbsp;</i></td>
                            <td class="x-btn-tc"></td>
                            <td class="x-btn-tr"><i>&nbsp;</i></td>
                        </tr>
                        <tr>
                            <td class="x-btn-ml"><i>&nbsp;</i></td>
                            <td class="x-btn-mc"><em unselectable="on" class="">
                                    <button type="button" id="ext-gen105" class=" x-btn-text">OK</button>
                                </em></td>
                            <td class="x-btn-mr"><i>&nbsp;</i></td>
                        </tr>
                        <tr>
                            <td class="x-btn-bl"><i>&nbsp;</i></td>
                            <td class="x-btn-bc"></td>
                            <td class="x-btn-br"><i>&nbsp;</i></td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <?php
        break;
    case 'showDynaformHistoryGetNomDynaform_JXP':
        //!dataInput
        $idDin = $_REQUEST['idDin'];
        $dynDate = $_REQUEST["dynDate"];

        //!dataOuput

        $dynaform = new Dynaform();
        $row = $dynaform->Load($idDin);

        $title = '';
        if ($row) {
            $title = $row['DYN_TITLE'];
        }

        //assign task
        $result = new stdClass();
        $result->dynTitle = $title;
        $result->md5Hash = G::encryptOld($idDin . $dynDate);

        echo G::json_encode($result);
        break;
}
