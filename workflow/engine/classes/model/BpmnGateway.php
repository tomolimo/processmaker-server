<?php

require_once 'classes/model/om/BaseBpmnGateway.php';


/**
 * Skeleton subclass for representing a row from the 'BPMN_GATEWAY' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class BpmnGateway extends BaseBpmnGateway
{
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
        $this->bound->setBouElement('pm_canvas');
        $this->bound->setBouContainer('bpmnDiagram');

        $this->bound->setPrjUid($this->getPrjUid());
        $this->bound->setElementUid($this->getGatUid());

        $process = BpmnProcessPeer::retrieveByPK($this->getProUid());

        if (is_object($process)) {
            $this->bound->setDiaUid($process->getDiaUid());
        }
    }

    /**
     * @param $field
     * @param $value
     * @return \BpmnGateway|null
     */
    public static function findOneBy($field, $value)
    {
        $rows = self::findAllBy($field, $value);

        return empty($rows) ? null : $rows[0];
    }

    public static function findAllBy($field, $value)
    {
        $c = new Criteria('workflow');
        $c->add($field, $value, Criteria::EQUAL);

        return BpmnGatewayPeer::doSelect($c);
    }

    public static function getAll($prjUid = null, $start = null, $limit = null, $filter = '', $changeCaseTo = CASE_UPPER)
    {
        $c = new Criteria('workflow');
        $c->addSelectColumn("BPMN_GATEWAY.*");
        $c->addSelectColumn("BPMN_BOUND.*");
        $c->addJoin(BpmnGatewayPeer::GAT_UID, BpmnBoundPeer::ELEMENT_UID, Criteria::LEFT_JOIN);

        if (! is_null($prjUid)) {
            $c->add(BpmnGatewayPeer::PRJ_UID, $prjUid, Criteria::EQUAL);
        }

        $rs = BpmnGatewayPeer::doSelectRS($c);
        $rs->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

        $events = array();

        while ($rs->next()) {
            $events[] = $changeCaseTo !== CASE_UPPER ? array_change_key_case($rs->getRow(), CASE_LOWER) : $rs->getRow();
        }

        return $events;
    }

    // OVERRIDES

    public function setActUid($actUid)
    {
        parent::setGatUid($actUid);
        $this->bound->setElementUid($this->getGatUid());
    }

    public function setPrjUid($prjUid)
    {
        parent::setPrjUid($prjUid);
        $this->bound->setPrjUid($this->getPrjUid());
    }

    public function setProUid($proUid)
    {
        parent::setProUid($proUid);

        $process = BpmnProcessPeer::retrieveByPK($this->getProUid());
        $this->bound->setDiaUid($process->getDiaUid());
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
            $this->bound = BpmnBound::findByElement('Gateway', $this->getGatUid());
        }

        if (is_object($this->bound)) {
            $this->bound->delete($con);
        }

        parent::delete($con);
    }

    public function fromArray($data, $type = BasePeer::TYPE_FIELDNAME)
    {
        parent::fromArray($data, $type);

        $bound = BpmnBound::findByElement('Gateway', $this->getGatUid());

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
            $bound = BpmnBound::findByElement('Gateway', $this->getGatUid());

            if (is_object($bound)) {
                $this->bound = $bound;
            }
        }

        $data = array_merge($data, $this->bound->toArray($type));

        return $data;
    }

    public static function exists($gatUid)
    {
        $c = new Criteria("workflow");
        $c->add(BpmnGatewayPeer::GAT_UID, $gatUid);

        return BpmnGatewayPeer::doCount($c) > 0 ? true : false;
    }

} // BpmnGateway
