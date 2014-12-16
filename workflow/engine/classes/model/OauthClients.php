<?php
require_once ("classes" . PATH_SEP . "model" . PATH_SEP . "om" . PATH_SEP . "BaseOauthClients.php");


/**
 * Skeleton subclass for representing a row from the 'OAUTH_CLIENTS' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class OauthClients extends BaseOauthClients
{
    public function load($oauthClientId)
    {
        try {
            $oclient = OauthClientsPeer::retrieveByPK($oauthClientId);

            if (!is_null($oclient)) {
                $arrayField = $oclient->toArray(BasePeer::TYPE_FIELDNAME);
                $this->fromArray($arrayField, BasePeer::TYPE_FIELDNAME);
                $this->setNew(false);

                return $arrayField;
            } else {
                throw (new Exception("The row \"$oauthClientId\" in table OAUTH_CLIENTS doesn't exist!"));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function create($arrayData)
    {
        $cnn = Propel::getConnection(OauthClientsPeer::DATABASE_NAME);

        try {
            $cnn->begin();

            $id = G::generateCode(32, "ALPHA");
            $secret = G::generateUniqueID();

            $this->setClientId($id);
            $this->setClientSecret($secret);
            $this->setClientName($arrayData["CLIENT_NAME"]);
            $this->setClientDescription($arrayData["CLIENT_DESCRIPTION"]);
            $this->setClientWebsite($arrayData["CLIENT_WEBSITE"]);
            $this->setRedirectUri($arrayData["REDIRECT_URI"]);
            $this->setUsrUid($arrayData["USR_UID"]);

            if ($this->validate()) {
                $result = $this->save();
                $cnn->commit();

                return array("CLIENT_ID" => $id, "CLIENT_SECRET" => $secret);
            } else {
                $cnn->rollback();

                throw (new Exception("Failed Validation in class \"" . get_class($this) . "\"."));
            }
        } catch (Exception $e) {
            $cnn->rollback();

            throw $e;
        }
    }

    public function update($arrayData)
    {
        $cnn = Propel::getConnection(OauthClientsPeer::DATABASE_NAME);

        try {
            $cnn->begin();

            $this->load($arrayData["CLIENT_ID"]);
            $this->fromArray($arrayData, BasePeer::TYPE_FIELDNAME);

            if ($this->validate()) {
                if (isset($arrayData["CLIENT_NAME"])) {
                    $this->setClientName($arrayData["CLIENT_NAME"]);
                }

                if (isset($arrayData["CLIENT_DESCRIPTION"])) {
                    $this->setClientDescription($arrayData["CLIENT_DESCRIPTION"]);
                }

                if (isset($arrayData["CLIENT_WEBSITE"])) {
                    $this->setClientWebsite($arrayData["CLIENT_WEBSITE"]);
                }

                if (isset($arrayData["REDIRECT_URI"])) {
                    $this->setRedirectUri($arrayData["REDIRECT_URI"]);
                }

                $result = $this->save();
                $result = ($result == 0)? (($contentResult > 0)? 1 : 0) : $result;

                $cnn->commit();

                return $result;
            } else {
                $cnn->rollback();

                throw (new Exception("Failed Validation in class \"" . get_class($this) . "\"."));
            }
        } catch (Exception $e) {
            $cnn->rollback();

            throw $e;
        }
    }

    public function remove($oauthClientId)
    {
        $cnn = Propel::getConnection(OauthClientsPeer::DATABASE_NAME);

        try {
            $oclient = OauthClientsPeer::retrieveByPK($oauthClientId);

            if (!is_null($oclient)) {
                $cnn->begin();

                $result = $oclient->delete();
                $cnn->commit();

                return $result;
            } else {
                throw (new Exception("The row \"$oauthClientId\" in table OAUTH_CLIENTS doesn't exist!"));
            }
        } catch (Exception $e) {
            $cnn->rollback();

            throw $e;
        }
    }

    public function getAll($arrayFilterData = array(), $sortField = "", $sortDir = "", $start = 0, $limit = 25)
    {
        //SQL
        $criteria = new Criteria("workflow");

        $criteria->addSelectColumn(OauthClientsPeer::CLIENT_ID);
        $criteria->addSelectColumn(OauthClientsPeer::CLIENT_SECRET);
        $criteria->addSelectColumn(OauthClientsPeer::CLIENT_NAME);
        $criteria->addSelectColumn(OauthClientsPeer::CLIENT_DESCRIPTION);
        $criteria->addSelectColumn(OauthClientsPeer::CLIENT_WEBSITE);
        $criteria->addSelectColumn(OauthClientsPeer::REDIRECT_URI);
        $criteria->addSelectColumn(OauthClientsPeer::USR_UID);

        $criteria->add(OauthClientsPeer::CLIENT_ID, 'x-pm-local-client', Criteria::NOT_EQUAL);

        if ($arrayFilterData && isset($arrayFilterData["USR_UID"]) && $arrayFilterData["USR_UID"] != "") {
            $criteria->add(OauthClientsPeer::USR_UID, $arrayFilterData["USR_UID"], Criteria::EQUAL);
        }

        if ($arrayFilterData && isset($arrayFilterData["SEARCH"]) && $arrayFilterData["SEARCH"] != "") {
            //$criteria->add(
            //    $criteria->getNewCriterion(OauthClientsPeer::CLIENT_NAME, "%" . $arrayFilterData["SEARCH"] . "%", Criteria::LIKE)->addOr(
            //    $criteria->getNewCriterion(OauthClientsPeer::CLIENT_DESCRIPTION, "%" . $arrayFilterData["SEARCH"] . "%", Criteria::LIKE))->addOr(
            //    $criteria->getNewCriterion(OauthClientsPeer::CLIENT_WEBSITE, "%" . $arrayFilterData["SEARCH"] . "%", Criteria::LIKE))->addOr(
            //    $criteria->getNewCriterion(OauthClientsPeer::REDIRECT_URI, "%" . $arrayFilterData["SEARCH"] . "%", Criteria::LIKE))
            //);
            $criteria->add(
                $criteria->getNewCriterion(OauthClientsPeer::CLIENT_NAME, "%" . $arrayFilterData["SEARCH"] . "%", Criteria::LIKE)->addOr(
                $criteria->getNewCriterion(OauthClientsPeer::CLIENT_DESCRIPTION, "%" . $arrayFilterData["SEARCH"] . "%", Criteria::LIKE))
            );
        }

        $sortField = ($sortField && $sortField != "")? OauthClientsPeer::TABLE_NAME . "." . $sortField : OauthClientsPeer::CLIENT_NAME;

        if ($sortDir && $sortDir == "DESC") {
            $criteria->addDescendingOrderByColumn($sortField);
        } else {
            $criteria->addAscendingOrderByColumn($sortField);
        }

        //Number records total
        $criteriaCount = clone $criteria;
        $criteriaCount->clearSelectColumns();
        $criteriaCount->addSelectColumn("COUNT(" . OauthClientsPeer::CLIENT_ID . ") AS NUM_REC");

        $rsCriteriaCount = OauthClientsPeer::doSelectRS($criteriaCount);
        $rsCriteriaCount->setFetchmode(ResultSet::FETCHMODE_ASSOC);

        $rsCriteriaCount->next();
        $row = $rsCriteriaCount->getRow();

        $numRecTotal = $row["NUM_REC"];

        //SQL
        if ($start && $limit && $limit > 0) {
            $criteria->setOffset($start);
            $criteria->setLimit($limit);
        }

        $rsCriteria = OauthClientsPeer::doSelectRS($criteria);
        $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);

        $arrayData = array();

        while ($rsCriteria->next()) {
            $arrayData[] = $rsCriteria->getRow();
        }

        return array("numRecTotal" => $numRecTotal, "data" => $arrayData);
    }

}

// OauthClients

