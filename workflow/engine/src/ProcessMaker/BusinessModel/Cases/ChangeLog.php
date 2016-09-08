<?php

namespace ProcessMaker\BusinessModel\Cases;

use Propel;
use StdClass;
use G;
use Cases;
use AppDocument;
use Dynaform;
use Exception;
use Task;

/**
 * Return the ChangeLog of a Dynaform
 */
class ChangeLog
{
    /**
     * List of variables that should not be considered
     * @var string[]
     */
    private $reserved = [
        'TASK',
        'INDEX',
        'DYN_CONTENT_HISTORY'
    ];

    /**
     * Map of variables and its values
     * @var mixed[]
     */
    private $values = [];

    /**
     * List of variables changes
     * @var object[]
     */
    private $tree;

    /**
     * List of assigned permissions
     * @var string[]
     */
    private $permissions = [];

    public function getChangeLog($appUid, $proUid, $tasUid, $start, $limit)
    {
        $this->loadPermissions($appUid, $proUid, $tasUid);
        $result = $this->getResultSet($appUid);
        $totalCount = $this->readRecords($result, $start, $limit);
        return ['data' => $this->tree, 'totalCount' => $totalCount];
    }

    private function getResultSet($appUid)
    {
        $conn = Propel::getConnection('workflow');
        $sql = 'SELECT APP_HISTORY.*, USERS.USR_USERNAME FROM APP_HISTORY'
            .' LEFT JOIN USERS ON(APP_HISTORY.USR_UID=USERS.USR_UID)'
            .' WHERE APP_UID=? ORDER BY HISTORY_DATE ASC';
        $stmt = $conn->prepareStatement($sql);
        $stmt->set(1, $appUid);
        if (!$stmt->executeQuery()) {
            throw Exception(G::LoadTranslation('ID_MSG_AJAX_FAILURE'));
        }
        return $stmt->getResultSet();
    }

    private function readRecords($result, $start = 0, $limit = 15)
    {
        $index = 0;
        while ($result->next()) {
            $row = $result->getRow();
            $data = unserialize($row['HISTORY_DATA']);
            if ($this->isEmpty($data)) {
                continue;
            }
            if ($index < $start) {
                $index += $this->updateData(
                    $data, $row, $this->hasPermission($row['DYN_UID']), false);
                continue;
            }
            $a = $this->updateData($data, $row,
                                   $this->hasPermission($row['DYN_UID']), true);
            $limit-= $a;
            $index+= $a;
        }
        return $index;
    }

    private function isEmpty($data)
    {
        foreach ($data as $key => $value) {
            if (array_search($key, $this->reserved) !== false) {
                continue;
            }
            return false;
        }
        return true;
    }

    private function updateData($data, $row, $hasPermission, $addToTree = false)
    {
        $i = 0;
        foreach ($data as $key => $value) {
            if (array_search($key, $this->reserved) !== false) {
                continue;
            }
            if ($hasPermission && (!isset($this->values[$key]) || $this->values[$key]
                !== $value)) {
                if ($addToTree) {
                    $node = new StdClass();
                    $node->field = $key;
                    $previousValue = !isset($this->values[$key]) ? null : $this->values[$key];
                    if(!is_array($previousValue)){
                        $node->previousValue = (string) $previousValue;
                    } else {
                        $node->previousValue = "<br />".nl2br(print_r($previousValue, true));
                    }
                    if(!is_array($value)){
                        $node->currentValue = (string) $value;
                    } else {
                        $node->currentValue = "<br />".nl2br(print_r($value, true));
                    }
                    $node->previousValueType = gettype($previousValue);
                    $node->currentValueType = gettype($value);
                    $node->record = $this->getHistoryTitle($row);
                    $this->tree[] = $node;
                }
                $i++;
            }
            $this->values[$key] = $value;
        }
        return $i;
    }

    private function getHistoryTitle($row)
    {
        return $this->getObjectTitle($row['TAS_UID'], 'TASK')
            .' / '.$this->getObjectTitle($row['DYN_UID'], $row['OBJ_TYPE'])
            .' / '.G::LoadTranslation('ID_LAN_UPDATE_DATE').': '.$row['HISTORY_DATE']
            .' / '.G::LoadTranslation('ID_USER').': '.$row['USR_USERNAME'];
    }

    private function getObjectTitle($uid, $objType)
    {
        switch ($objType) {
            case 'DYNAFORM':
                $obj = new Dynaform();
                $obj->Load($uid);
                $title = $obj->getDynTitle();
                break;
            case 'OUTPUT_DOCUMENT':
            case 'INPUT_DOCUMENT':
                $obj = new AppDocument();
                $obj->load($uid);
                $title = $obj->getDynTitle();
                break;
            case 'TASK':
                $obj = new Task();
                $obj->load($uid);
                $title = $obj->getTasTitle();
                break;
            default:
                $title = $uid;
        }
        return $title;
    }

    private function loadPermissions($APP_UID, $PRO_UID, $TAS_UID)
    {
        G::LoadClass('case');
        $oCase = new Cases();
        $oCase->verifyTable();
        $this->permissions = $oCase->getAllObjects(
            $PRO_UID, $APP_UID, $TAS_UID, $_SESSION['USER_LOGGED']
        );
    }

    private function hasPermission($uid)
    {
        foreach ($this->permissions as $type => $ids) {
            if (array_search($uid, $ids) !== false) {
                return true;
            }
        }
        return false;
    }
}