<?php

require_once 'classes/model/om/BaseBpmnLane.php';


/**
 * Skeleton subclass for representing a row from the 'BPMN_LANE' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class BpmnLane extends BaseBpmnLane {

    private $bound;

    public function __construct($generateUid = true)
    {
        $this->bound = new BpmnBound();
        $this->setBoundDefaults();
    }

    public function getBound()
    {
        return $this->bound;
    }

    private function setBoundDefaults()
    {
        $this->bound->setBouElementType(lcfirst(str_replace(__NAMESPACE__, '', __CLASS__)));

        $this->bound->setPrjUid($this->getPrjUid());
        $this->bound->setElementUid($this->getLanUid());

        $project = BpmnProjectPeer::retrieveByPK($this->getPrjUid());

        if (is_object($project)) {

            $criteria = new Criteria('workflow');
            $criteria->addSelectColumn(BpmnProcessPeer::DIA_UID);
            $criteria->add(BpmnProcessPeer::PRJ_UID, $this->getPrjUid(), \Criteria::EQUAL);
            $rsCriteria = BpmnProcessPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);
            $rsCriteria->next();
            $row = $rsCriteria->getRow();
            $this->bound->setDiaUid($row["DIA_UID"]);
            $this->bound->setBouContainer('bpmnPool');
            $this->bound->setBouElement($this->getLnsUid());
        }
    }

    public static function findOneBy($field, $value)
    {
        $rows = self::findAllBy($field, $value);

        return empty($rows) ? null : $rows[0];
    }

    public static function findAllBy($field, $value)
    {
        $c = new Criteria('workflow');
        $c->add($field, $value, Criteria::EQUAL);

        return BpmnLanePeer::doSelect($c);
    }

    public static function getAll($prjUid = null, $start = null, $limit = null, $filter = '', $changeCaseTo = CASE_UPPER)
    {
        $c = new Criteria('workflow');
        $c->addSelectColumn("BPMN_LANE.*");
        $c->addSelectColumn("BPMN_BOUND.*");
        $c->addJoin(BpmnLanePeer::LAN_UID, BpmnBoundPeer::ELEMENT_UID, Criteria::LEFT_JOIN);

        if (! is_null($prjUid)) {
            $c->add(BpmnLanePeer::PRJ_UID, $prjUid, Criteria::EQUAL);
        }
        $c->addAscendingOrderByColumn(BpmnBoundPeer::BOU_REL_POSITION);
        $rs = BpmnLanePeer::doSelectRS($c);
        $rs->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

        $lanes = array();

        while ($rs->next()) {
            $lanes[] = $changeCaseTo !== CASE_UPPER ? array_change_key_case($rs->getRow(), CASE_LOWER) : $rs->getRow();
        }

        return $lanes;
    }

    // OVERRIDES

    public function setLanUid($actUid)
    {
        parent::setLanUid($actUid);
        $this->bound->setElementUid($this->getLanUid());
    }

    public function setPrjUid($prjUid)
    {
        parent::setPrjUid($prjUid);
        $this->bound->setPrjUid($this->getPrjUid());
    }

    public function save($con = null)
    {
        parent::save($con);

        $this->setBoundDefaults();

        if ($this->bound->getBouUid() == "") {
            $this->bound->setBouUid(\ProcessMaker\Util\Common::generateUID());
        }

        $this->bound->save($con);
    }

    public function delete($con = null)
    {
        // first, delete the related bound object
        if (! is_object($this->bound) || $this->bound->getBouUid() == "") {
            $this->bound = BpmnBound::findByElement('Lane', $this->getLanUid());
        }

        if (is_object($this->bound)) {
            $this->bound->delete($con);
        }

        parent::delete($con);
    }

    public function fromArray($data, $type = BasePeer::TYPE_FIELDNAME)
    {
        parent::fromArray($data, $type);

        $bound = BpmnBound::findByElement('Lane', $this->getLanUid());

        if (is_object($bound)) {
            $this->bound = $bound;
        } else {
            $this->bound = new BpmnBound();
            $this->bound->setBouUid(ProcessMaker\Util\Common::generateUID());
        }

        $this->bound->fromArray($data, BasePeer::TYPE_FIELDNAME);
    }

    public function toArray($type = BasePeer::TYPE_FIELDNAME)
    {
        $data = parent::toArray($type);
        $bouUid = $this->bound->getBouUid();

        if (empty($bouUid)) {
            $bound = BpmnBound::findByElement('Lane', $this->getLanUid());

            if (is_object($bound)) {
                $this->bound = $bound;
            }
        }

        $data = array_merge($data, $this->bound->toArray($type));

        return $data;
    }

    public static function exists($actUid)
    {
        $c = new Criteria("workflow");
        $c->add(BpmnlanesPeer::LAN_UID, $actUid);

        return BpmnlanesPeer::doCount($c) > 0 ? true : false;
    }

} // BpmnLane
