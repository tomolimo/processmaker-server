<?php
require_once ("classes" . PATH_SEP . "model" . PATH_SEP . "om" . PATH_SEP . "BaseOauthAccessTokens.php");


/**
 * Skeleton subclass for representing a row from the 'OAUTH_ACCESS_TOKENS' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    classes.model
 */
class OauthAccessTokens extends BaseOauthAccessTokens
{
    public function load($oauthAccessTokenId)
    {
        try {
            $oatoken = OauthAccessTokensPeer::retrieveByPK($oauthAccessTokenId);

            if (!is_null($oatoken)) {
                $arrayField = $oatoken->toArray(BasePeer::TYPE_FIELDNAME);
                $this->fromArray($arrayField, BasePeer::TYPE_FIELDNAME);
                $this->setNew(false);

                return $arrayField;
            } else {
                throw (new Exception("The row \"$oauthAccessTokenId\" in table OAUTH_ACCESS_TOKENS doesn't exist!"));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update($arrayData)
    {
        $cnn = Propel::getConnection(OauthAccessTokensPeer::DATABASE_NAME);

        try {
            $cnn->begin();

            $this->load($arrayData["ACCESS_TOKEN"]);
            $this->fromArray($arrayData, BasePeer::TYPE_FIELDNAME);

            if ($this->validate()) {
                if (isset($arrayData["CLIENT_ID"])) {
                    $this->setClientId($arrayData["CLIENT_ID"]);
                }

                if (isset($arrayData["USER_ID"])) {
                    $this->setUserId($arrayData["USER_ID"]);
                }

                if (isset($arrayData["EXPIRES"])) {
                    $this->setExpires($arrayData["EXPIRES"]);
                }

                if (isset($arrayData["SCOPE"])) {
                    $this->setScope($arrayData["SCOPE"]);
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

    public function remove($oauthAccessTokenId)
    {
        $cnn = Propel::getConnection(OauthAccessTokensPeer::DATABASE_NAME);

        try {
            $oclient = OauthAccessTokensPeer::retrieveByPK($oauthAccessTokenId);

            if (!is_null($oclient)) {
                $cnn->begin();

                $result = $oclient->delete();
                $cnn->commit();

                return $result;
            } else {
                throw (new Exception("The row \"$oauthAccessTokenId\" in table OAUTH_ACCESS_TOKENS doesn't exist!"));
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

        $criteria->addSelectColumn(OauthAccessTokensPeer::ACCESS_TOKEN);
        $criteria->addSelectColumn(OauthAccessTokensPeer::CLIENT_ID);
        $criteria->addSelectColumn(OauthAccessTokensPeer::USER_ID);
        $criteria->addSelectColumn(OauthAccessTokensPeer::EXPIRES);
        $criteria->addSelectColumn(OauthAccessTokensPeer::SCOPE);
        $criteria->addSelectColumn(OauthClientsPeer::CLIENT_NAME);
        $criteria->addSelectColumn(OauthClientsPeer::CLIENT_DESCRIPTION);

        $criteria->addJoin(OauthAccessTokensPeer::CLIENT_ID, OauthClientsPeer::CLIENT_ID, Criteria::LEFT_JOIN);
        $criteria->add(OauthAccessTokensPeer::EXPIRES, date('Y-m-d H:i:s'), Criteria::GREATER_THAN);

        if ($arrayFilterData && isset($arrayFilterData["USER_ID"]) && $arrayFilterData["USER_ID"] != "") {
            $criteria->add(OauthAccessTokensPeer::USER_ID, $arrayFilterData["USER_ID"], Criteria::EQUAL);
        }

        if ($sortField && $sortField != "") {
            switch ($sortField) {
                case "CLIENT_NAME":
                case "CLIENT_DESCRIPTION":
                    $sortField = OauthClientsPeer::TABLE_NAME . "." . $sortField;
                    break;
                default:
                    $sortField = OauthAccessTokensPeer::TABLE_NAME . "." . $sortField;
                    break;
            }
        } else {
            $sortField = OauthClientsPeer::CLIENT_NAME;
        }

        if ($sortDir && $sortDir == "DESC") {
            $criteria->addDescendingOrderByColumn($sortField);
        } else {
            $criteria->addAscendingOrderByColumn($sortField);
        }

        //Number records total
        $criteriaCount = clone $criteria;
        $criteriaCount->clearSelectColumns();
        $criteriaCount->addSelectColumn("COUNT(" . OauthAccessTokensPeer::ACCESS_TOKEN . ") AS NUM_REC");

        $rsCriteriaCount = OauthAccessTokensPeer::doSelectRS($criteriaCount);
        $rsCriteriaCount->setFetchmode(ResultSet::FETCHMODE_ASSOC);

        $rsCriteriaCount->next();
        $row = $rsCriteriaCount->getRow();

        $numRecTotal = $row["NUM_REC"];

        //SQL
        if ($start && $limit && $limit > 0) {
            $criteria->setOffset($start);
            $criteria->setLimit($limit);
        }

        $rsCriteria = OauthAccessTokensPeer::doSelectRS($criteria);
        $rsCriteria->setFetchmode(ResultSet::FETCHMODE_ASSOC);

        $arrayData = array();

        while ($rsCriteria->next()) {
            $arrayData[] = $rsCriteria->getRow();
        }

        return array("numRecTotal" => $numRecTotal, "data" => $arrayData);
    }
}

// OauthAccessTokens

