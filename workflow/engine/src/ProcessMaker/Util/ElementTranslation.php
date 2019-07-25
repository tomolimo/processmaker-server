<?php
namespace ProcessMaker\Util;

class ElementTranslation
{
    protected $map = [
        'APP_DESCRIPTION' => ['table' => 'APPLICATION', 'uid' => 'APP_UID', 'ownedByProcess' => true, 'className' => 'Application'],
        'APP_DOC_COMMENT' => ['table' => 'APPLICATION', 'uid' => 'APP_UID', 'ownedByProcess' => true, 'className' => 'Application'],
        'APP_DOC_FILENAME' => ['table' => 'APPLICATION', 'uid' => 'APP_UID', 'ownedByProcess' => true, 'className' => 'Application'],
        'APP_DOC_TITLE' => ['table' => 'APPLICATION', 'uid' => 'APP_UID', 'ownedByProcess' => true, 'className' => 'Application'],
        'APP_TITLE' => ['table' => 'APPLICATION', 'uid' => 'APP_UID', 'ownedByProcess' => true, 'className' => 'Application'],
        'PRO_TITLE' => ['table' => 'PROCESS', 'uid' => 'PRO_UID', 'ownedByProcess' => false, 'className' => 'Process'],
        'PRO_DESCRIPTION' => ['table'=>'PROCESS', 'uid'=>'PRO_UID', 'ownedByProcess'=>false, 'className'=>'Process'],
        'TAS_DEF_MESSAGE' => ['table' => 'TASK', 'uid' => 'TAS_UID', 'ownedByProcess' => true, 'className' => 'Task'],
        'TAS_TITLE' => ['table' => 'TASK', 'uid' => 'TAS_UID', 'ownedByProcess' => true, 'className' => 'Task'],
        'TAS_DEF_DESCRIPTION' => ['table'=>'TASK', 'uid'=>'TAS_UID', 'ownedByProcess'=>true, 'className'=>'Task'],
        'TAS_DEF_PROC_CODE' => ['table'=>'TASK', 'uid'=>'TAS_UID', 'ownedByProcess'=>true, 'className'=>'Task'],
        'TAS_DEF_SUBJECT_MESSAGE' => ['table'=>'TASK', 'uid'=>'TAS_UID', 'ownedByProcess'=>true, 'className'=>'Task'],
        'TAS_DEF_TITLE' => ['table'=>'TASK', 'uid'=>'TAS_UID', 'ownedByProcess'=>true, 'className'=>'Task'],
        'TAS_DESCRIPTION' => ['table'=>'TASK', 'uid'=>'TAS_UID', 'ownedByProcess'=>true, 'className'=>'Task'],
        'DYN_TITLE' => ['table'=>'DYNAFORM', 'uid'=>'DYN_UID', 'ownedByProcess'=>true, 'className'=>'Dynaform'],
        'DYN_DESCRIPTION' => ['table'=>'DYNAFORM', 'uid'=>'DYN_UID', 'ownedByProcess'=>true, 'className'=>'Dynaform'],
        'GRP_TITLE' => ['table'=>'GROUPWF', 'uid'=>'GRP_UID', 'ownedByProcess'=>false, 'className'=>'Groupwf'],
        'DEPO_TITLE' => ['table'=>'DEPARTMENT', 'uid'=>'DEP_UID', 'ownedByProcess'=>false, 'className'=>'Department'],
        'INP_DOC_DESCRIPTION' => ['table'=>'INPUT_DOCUMENT', 'uid'=>'INP_DOC_UID', 'ownedByProcess'=>true, 'className'=>'InputDocument'],
        'INP_DOC_TITLE' => ['table'=>'INPUT_DOCUMENT', 'uid'=>'INP_DOC_UID', 'ownedByProcess'=>true, 'className'=>'InputDocument'],
        'OUT_DOC_DESCRIPTION' => ['table'=>'OUTPUT_DOCUMENT', 'uid'=>'OUT_DOC_UID', 'ownedByProcess'=>true, 'className'=>'OutputDocument'],
        'OUT_DOC_FILENAME' => ['table'=>'OUTPUT_DOCUMENT', 'uid'=>'OUT_DOC_UID', 'ownedByProcess'=>true, 'className'=>'OutputDocument'],
        'OUT_DOC_TEMPLATE' => ['table'=>'OUTPUT_DOCUMENT', 'uid'=>'OUT_DOC_UID', 'ownedByProcess'=>true, 'className'=>'OutputDocument'],
        'OUT_DOC_TITLE' => ['table'=>'OUTPUT_DOCUMENT', 'uid'=>'OUT_DOC_UID', 'ownedByProcess'=>true, 'className'=>'OutputDocument'],
        'PER_NAME' => ['table'=>'RBAC_PERMISSIONS', 'uid'=>'PER_UID', 'ownedByProcess'=>false, 'className'=>'Permissions'],
        'ROL_NAME' => ['table'=>'RBAC_ROLES', 'uid'=>'ROL_UID', 'ownedByProcess'=>false, 'className'=>'Roles'],
        'TRI_TITLE' => ['table' => 'TRIGGERS', 'uid' => 'TRI_UID', 'ownedByProcess' => true, 'className' => 'Triggers'],
        'TRI_DESCRIPTION' => ['table' => 'TRIGGERS', 'uid' => 'TRI_UID', 'ownedByProcess' => true, 'className' => 'Triggers'],
    ];

    protected function getClassNameFrom($category)
    {
        return $this->map[$category]["className"];
    }

    protected function getTableFrom($category)
    {
        return $this->map[$category]["table"];
    }

    protected function getUidFieldFrom($category)
    {
        return $this->map[$category]["uid"];
    }

    protected function getProUidFrom($category)
    {
        return 'PRO_UID';
    }

    protected function isOwnedByProcess($category)
    {
        return $this->map[$category]["ownedByProcess"];
    }

    /**
     * @param $text
     * @param $category
     * @param null $proUid
     * @param string $lang
     * @return array
     */
    public function getUidFromTextI18n($text, $category, $proUid = null, $lang = SYS_LANG)
    {
        if (empty($text) || empty($category)){
            return array();
        }
        $uids = array();
        $className = $this->getClassNameFrom($category) . 'Peer';
        $uidField = $this->getUidFieldFrom($category);
        $proUidField = $this->getProUidFrom($category);
        $ownedByProcess = $this->isOwnedByProcess($category);
        require_once("classes/model/$className.php");
        $oCriteria = new \Criteria('workflow');
        if ($ownedByProcess) {
            $oCriteria->addSelectColumn(\ContentPeer::CON_ID);
            $oCriteria->addJoin(\ContentPeer::CON_ID, constant("$className::$uidField"));
            if (!empty($proUid)) {
                $oCriteria->add(constant("$className::$proUidField"), $proUid);
            }
        } else {
            $oCriteria->addSelectColumn(\ContentPeer::CON_ID);
        }
        $oCriteria->add(\ContentPeer::CON_CATEGORY, $category);
        $oCriteria->add(\ContentPeer::CON_VALUE, $text);
        $oCriteria->add(\ContentPeer::CON_LANG, $lang);
        $oDataset = \ContentPeer::doSelectRS($oCriteria);
        $oDataset->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
        $oDataset->next();
        while ($row = $oDataset->getRow()) {
            $uids[] = $row['CON_ID'];
            $oDataset->next();
        }
        return $uids;
    }
}
