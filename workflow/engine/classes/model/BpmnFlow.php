<?php

require_once 'classes/model/om/BaseBpmnFlow.php';


/**
 * Skeleton subclass for representing a row from the 'BPMN_FLOW' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class BpmnFlow extends BaseBpmnFlow
{
    public static function removeAllRelated($elementUid)
    {
        $c = new Criteria('workflow');

        $c1 = $c->getNewCriterion(BpmnFlowPeer::FLO_ELEMENT_ORIGIN, $elementUid);
        $c2 = $c->getNewCriterion(BpmnFlowPeer::FLO_ELEMENT_DEST, $elementUid);

        $c1->addOr($c2);
        $c->add($c1);

        $flows = BpmnFlowPeer::doSelect($c);

        foreach ($flows as $flow) {
            $flow->delete();
        }
    }


    /**
     * @param $field string coming from \BpmnFlowPeer::<FIELD_NAME>
     * @param $value string
     * @return \BpmnFlow|null
     */
    public static function findOneBy($field, $value = null)
    {
        $rows = self::findAllBy($field, $value);

        return empty($rows) ? null : $rows[0];
    }

    /**
     * @param $field
     * @param null $value
     * @return \BpmnFlow[]
     */
    public static function findAllBy($field, $value = null)
    {
        $field = is_array($field) ? $field : array($field => $value);

        $c = new Criteria('workflow');

        foreach ($field as $key => $value) {
            if (is_array($value)) {
                $c->add($key, $value[0], $value[1]);
            } else {
                $c->add($key, $value, Criteria::EQUAL);
            }
        }

        return BpmnFlowPeer::doSelect($c);
    }

    public static function getAll($prjUid = null, $start = null, $limit = null, $filter = '', $changeCaseTo = CASE_UPPER, $decodeState = true)
    {
        //TODO implement $start, $limit and $filter
        $c = new Criteria('workflow');

        if (! is_null($prjUid)) {
            $c->add(BpmnFlowPeer::PRJ_UID, $prjUid, Criteria::EQUAL);
        }
        $c->addAscendingOrderByColumn(BpmnFlowPeer::FLO_POSITION);
        $rs = BpmnFlowPeer::doSelectRS($c);
        $rs->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

        $flows = array();

        while ($rs->next()) {
            $flow = $rs->getRow();
            if ($decodeState) {
                $flow["FLO_STATE"] = @json_decode($flow["FLO_STATE"], true);
            }
            //$flow["FLO_IS_INMEDIATE"] = $flow["FLO_IS_INMEDIATE"] == 1 ? true : false;
            $flow = $changeCaseTo !== CASE_UPPER ? array_change_key_case($flow, CASE_LOWER) : $flow;

            $flows[] = $flow;
        }

        return $flows;
    }

    public static function exists($floUid)
    {
        $c = new Criteria('workflow');
        $c->add(BpmnFlowPeer::FLO_UID, $floUid);

        return BpmnFlowPeer::doCount($c) > 0 ? true : false;
    }

    public function fromArray($data, $type = BasePeer::TYPE_FIELDNAME)
    {
        parent::fromArray($data, $type);
    }

    public function toArray($type = BasePeer::TYPE_FIELDNAME)
    {
        $flow = parent::toArray($type);
        $flow["FLO_STATE"] = @json_decode($flow["FLO_STATE"], true);

        return $flow;
    }

    /*public static function select($select, $where = array())
    {
        $data = array();

        $c = new Criteria('workflow');
        if ($select !== '*') {
            if (is_array($select)) {
                foreach ($select as $column) {
                    $c->addSelectColumn($column);
                }
            } else {
                $c->addSelectColumn($select);
            }
        }

        if (! empty($where)) {
            foreach ($where as $column => $value) {
                $c->add($column, $value);
            }
        }

        $rs = BpmnFlowPeer::doSelectRS($c);
        $rs->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

        while ($rs->next()) {
            $data[] = $rs->getRow();
        }

        return $data;
    }*/

} // BpmnFlow

