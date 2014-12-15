<?php

require_once 'classes/model/om/BaseBpmnProject.php';


/**
 * Skeleton subclass for representing a row from the 'BPMN_PROJECT' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class BpmnProject extends BaseBpmnProject
{
    public static function getAll($start = null, $limit = null, $filter = "", $changeCaseTo = CASE_UPPER)
    {
        $c = new Criteria("workflow");
        $bpmnProjects = array();

        $rs = BpmnProjectPeer::doSelectRS($c);
        $rs->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

        while ($rs->next()) {
            $bpmnProjects[] = $changeCaseTo !== CASE_UPPER ? array_change_key_case($rs->getRow(), CASE_LOWER) : $rs->getRow();
        }

        return $bpmnProjects;
    }

    public static function exists($prjUid)
    {
        $c = new Criteria("workflow");
        $c->add(BpmnProjectPeer::PRJ_UID, $prjUid);

        return BpmnProjectPeer::doCount($c) > 0;
    }

    // Overrides

    public function toArray($type = BasePeer::TYPE_FIELDNAME)
    {
        return parent::toArray($type);
    }

} // BpmnProject
