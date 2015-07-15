<?php

require_once 'classes/model/om/BaseBpmnParticipant.php';


/**
 * Skeleton subclass for representing a row from the 'BPMN_PARTICIPANT' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class BpmnParticipant extends BaseBpmnParticipant
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

        $this->bound->setPrjUid($this->getPrjUid());
        $this->bound->setElementUid($this->getParUid());

        $process = BpmnProcessPeer::retrieveByPK($this->getProUid());

        if (is_object($process)) {
            $this->bound->setDiaUid($process->getDiaUid());
            if ($this->bound->getBouElement()) {
                $lane = BpmnLanePeer::retrieveByPK($this->bound->getBouElement());
                $laneset = BpmnLanesetPeer::retrieveByPK($this->bound->getBouElement());
                if (is_object($lane)) {
                    $this->bound->setBouContainer('bpmnLane');
                } elseif (is_object($laneset)) {
                    $this->bound->setBouContainer('bpmnPool');
                }
            } else {
                $this->bound->setBouContainer('bpmnDiagram');
                $this->bound->setBouElement($process->getDiaUid());
            }
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

        return BpmnParticipantPeer::doSelect($c);
    }

    public static function getAll($prjUid = null, $start = null, $limit = null, $filter = '', $changeCaseTo = CASE_UPPER)
    {
        $c = new Criteria('workflow');
        $c->addSelectColumn("BPMN_PARTICIPANT.*");
        $c->addSelectColumn("BPMN_BOUND.*");
        $c->addJoin(BpmnParticipantPeer::PAR_UID, BpmnBoundPeer::ELEMENT_UID, Criteria::LEFT_JOIN);

        if (! is_null($prjUid)) {
            $c->add(BpmnParticipantPeer::PRJ_UID, $prjUid, Criteria::EQUAL);
        }

        $rs = BpmnParticipantPeer::doSelectRS($c);
        $rs->setFetchmode(\ResultSet::FETCHMODE_ASSOC);

        $participants = array();

        while ($rs->next()) {
            $participants[] = $changeCaseTo !== CASE_UPPER ? array_change_key_case($rs->getRow(), CASE_LOWER) : $rs->getRow();
        }

        return $participants;
    }

    // OVERRIDES

    public function setParUid($actUid)
    {
        parent::setParUid($actUid);
        $this->bound->setElementUid($this->getParUid());
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
            $this->bound = BpmnBound::findByElement('Participant', $this->getParUid());
        }

        if (is_object($this->bound)) {
            $this->bound->delete($con);
        }

        parent::delete($con);
    }

    public function fromArray($data, $type = BasePeer::TYPE_FIELDNAME)
    {
        parent::fromArray($data, $type);

        $bound = BpmnBound::findByElement('Participant', $this->getParUid());

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
            $bound = BpmnBound::findByElement('Participant', $this->getParUid());

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
        $c->add(BpmnParticipantPeer::PAR_UID, $actUid);

        return BpmnParticipantPeer::doCount($c) > 0 ? true : false;
    }
} // BpmnParticipant
