<?php

/**
 * List basic methods
 *
 */
trait ListBaseTrait
{
    private $additionalClassName = '';
    private $userDisplayFormat = '';

    /**
     * Get the $additionalClassName value.
     *
     * @return string
     */
    public function getAdditionalClassName()
    {
        return $this->additionalClassName;
    }

    /**
     * Set the value of $additionalClassName.
     *
     * @param string $v new value
     * @return void
     */
    public function setAdditionalClassName($v)
    {
        $this->additionalClassName = $v;
    }

    /**
     * Get the $userDisplayFormat value.
     *
     * @return string
     */
    public function getUserDisplayFormat()
    {
        return $this->userDisplayFormat;
    }

    /**
     * Set the value of $userDisplayFormat.
     *
     * @param string $v new value
     * @return void
     */
    public function setUserDisplayFormat($v)
    {
        $this->userDisplayFormat = $v;
    }

    /**
     * Returns the number of cases by class and user.
     *
     * @param type $peerClass
     * @param type $usrUid
     * @param type $filters
     *
     * @return type
     */
    protected function getCountListFromPeer($peerClass, $usrUid, $filters = [])
    {
        $criteria = new Criteria();
        $criteria->addSelectColumn('COUNT(*) AS TOTAL');
        $criteria->add($peerClass::USR_UID, $usrUid, Criteria::EQUAL);
        if (count($filters)) {
            self::loadFilters($criteria, $filters);
        }
        $dataset = $peerClass::doSelectRS($criteria);
        $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
        $dataset->next();
        $row = $dataset->getRow();
        return (int) $row['TOTAL'];
    }
}
