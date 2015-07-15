<?php
require_once ("classes/model/Configuration.php");

$option = (isset($_POST["option"]))? $_POST["option"] : null;
$response = array();

switch ($option) {
    case "SETUP":
        $swInternetConnection = intval($_POST["internetConnection"]);

        $status = 1;

        try {
            $confEeUid = "enterpriseConfiguration";

            $criteria = new Criteria("workflow");

            $criteria->addSelectColumn(ConfigurationPeer::CFG_VALUE);
            $criteria->add(ConfigurationPeer::CFG_UID, "EE");
            $criteria->add(ConfigurationPeer::OBJ_UID, $confEeUid);

            $rsCriteria = ConfigurationPeer::doSelectRS($criteria);

            if ($rsCriteria->next()) {
                $row = $rsCriteria->getRow();

                $data = unserialize($row[0]);

                $data["internetConnection"] = $swInternetConnection;

                //Update values
                $criteria1 = new Criteria("workflow");

                $criteria1->add(ConfigurationPeer::CFG_UID, "EE");
                $criteria1->add(ConfigurationPeer::OBJ_UID, $confEeUid);

                //Update set
                $criteria2 = new Criteria("workflow");

                $criteria2->add(ConfigurationPeer::CFG_VALUE, serialize($data));

                BasePeer::doUpdate($criteria1, $criteria2, Propel::getConnection("workflow"));
            } else {
                $conf = new Configuration();
                $data = array("internetConnection" => $swInternetConnection);
                $conf->create(
                    array(
                        "CFG_UID"   => "EE",
                        "OBJ_UID"   => $confEeUid,
                        "CFG_VALUE" => serialize($data),
                        "PRO_UID"   => "",
                        "USR_UID"   => "",
                        "APP_UID"   => ""
                    )
                );
            }

            $response["status"] = "OK";
        } catch (Exception $e) {
            $response["message"] = $e->getMessage();
            $status = 0;
        }

        if ($status == 0) {
            $response["status"] = "ERROR";
        }
        break;
}
echo G::json_encode($response);

