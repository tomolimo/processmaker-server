<?php

namespace ProcessMaker\BusinessModel;

class BpmnProcess
{
    /**
     * Update all processes data by project uid
     * @param $prjUid
     * @param $data
     * @throws \PropelException
     */
    public function updateAllProcessesByProject($prjUid, $data)
    {
        $oCriteria = new \Criteria();
        $oCriteria->addSelectColumn(\BpmnProcessPeer::PRO_UID);
        $oCriteria->add(\BpmnProcessPeer::PRJ_UID, $prjUid);
        $rs = \BpmnProcessPeer::doSelectRS($oCriteria);
        $rs->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
        if (!empty($data['PRO_UID'])) {
            unset($data['PRO_UID']);
        }
        while ($rs->next()) {
            $row = $rs->getRow();
            $project = \BpmnProcessPeer::retrieveByPK($row['PRO_UID']);
            if (!empty($project)) {
                $project->fromArray($data, \BasePeer::TYPE_FIELDNAME);
                $project->save();
            }
        }
    }
}
