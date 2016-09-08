<?php

namespace ProcessMaker\BusinessModel;

class BpmnProject
{
    public function update($prj_uid, $data)
    {
        $project = \BpmnProjectPeer::retrieveByPK($prj_uid);
        $project->fromArray($data, \BasePeer::TYPE_FIELDNAME);
        $project->save();
    }
}
