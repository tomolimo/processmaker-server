<?php
class ConfigurationPeer extends BaseConfigurationPeer
{
    private static $arrayRecord = array();

    public static function retrieveByPK($cfg_uid, $obj_uid, $pro_uid, $usr_uid, $app_uid, $con = null)
    {
        try {
            $record = null;

            switch ($cfg_uid) {
                case "ENVIRONMENT_SETTINGS":
                    if (!isset(self::$arrayRecord["ENVIRONMENT_SETTINGS"])) {
                        self::$arrayRecord["ENVIRONMENT_SETTINGS"] = parent::retrieveByPK($cfg_uid, $obj_uid, $pro_uid, $usr_uid, $app_uid, $con);
                    }

                    $record = self::$arrayRecord["ENVIRONMENT_SETTINGS"];
                    break;
                default:
                    $record = parent::retrieveByPK($cfg_uid, $obj_uid, $pro_uid, $usr_uid, $app_uid, $con);
                    break;
            }

            //Return
            return $record;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

