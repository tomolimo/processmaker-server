<?php



class featuresDetail

{

    public $featureName;

    public $description = null;

    public $enabled = false;

    public $workspaces = null;



    /**

     * This function is the constructor of the featuresDetail class

     *

     * @param string $featureName

     * @param string $name

     * @param string $description

     * @return void

     */

    public function __construct ($featureName, $description = '')

    {

        $this->featureName = $featureName;

        $this->description = $description;

    }

}





class PMLicensedFeatures

{

    private $featuresDetails = array ();

    private $features = array ();

    private $newFeatures = array(

            0 => array(

                "description" => "Enables de Actions By Email feature.",

                "enabled" => false,

                "id" => "actionsByEmail",

                "latest_version" => "",

                "log" => null,

                "name" => "actionsByEmail",

                "nick" => "actionsByEmail",

                "progress" => 0,

                "publisher" => "Colosa",

                "release_type" => "localRegistry",

                "status" => "ready",

                "store" => "00000000000000000000000000010004",

                "type" => "features",

                "url" => "",

                "version" => ""

            ),

            1 => array(

                "description" => "Enables de Batch Routing feature.",

                "enabled" => false,

                "id" => "pmConsolidatedCL",

                "latest_version" => "",

                "log" => null,

                "name" => "pmConsolidatedCL",

                "nick" => "pmConsolidatedCL",

                "progress" => 0,

                "publisher" => "Colosa",

                "release_type" => "localRegistry",

                "status" => "ready",

                "store" => "00000000000000000000000000010005",

                "type" => "features",

                "url" => "",

                "version" => ""

            ),

            2 => array(

                "description" => "Dashboard with improved charting graphics and optimized to show strategic information like Process Efficiency and User Efficiency indicators.",

                "enabled" => false,

                "id" => "strategicDashboards",

                "latest_version" => "",

                "log" => null,

                "name" => "strategicDashboards",

                "nick" => "Strategic Dashboards",

                "progress" => 0,

                "publisher" => "Colosa",

                "release_type" => "localRegistry",

                "status" => "ready",

                "store" => "00000000000000000000000000010006",

                "type" => "features",

                "url" => "",

                "version" => ""

            ),

            3 => array(

                "description" => "Enables the configuration of a second database connection in order to divide the database requests in read and write operations. This features is used with database clusters to improve the application performance.",

                "enabled" => false,

                "id" => "secondDatabaseConnection",

                "latest_version" => "",

                "log" => null,

                "name" => "secondDatabaseConnection",

                "nick" => "secondDatabaseConnection",

                "progress" => 0,

                "publisher" => "Colosa",

                "release_type" => "localRegistry",

                "status" => "ready",

                "store" => "00000000000000000000000000010000",

                "type" => "features",

                "url" => "",

                "version" => ""

            ),

            4 => array(

                "description" => "Registers every admin action in a log. The actions in administration settings options are registered in the log.",

                "enabled" => false,

                "id" => "auditLog",

                "latest_version" => "",

                "log" => null,

                "name" => "auditLog",

                "nick" => "auditLog",

                "progress" => 0,

                "publisher" => "Colosa",

                "release_type" => "localRegistry",

                "status" => "ready",

                "store" => "00000000000000000000000000010001",

                "type" => "features",

                "url" => "",

                "version" => ""

            ),

            5 => array(

                "description" => "A more secure option to store user passwords in ProcessMaker. The modern algorithm SHA-2 is used to store the passwords.",

                "enabled" => false,

                "id" => "secureUserPasswordHash",

                "latest_version" => "",

                "log" => null,

                "name" => "secureUserPasswordHash",

                "nick" => "secureUserPasswordHash",

                "progress" => 0,

                "publisher" => "Colosa",

                "release_type" => "localRegistry",

                "status" => "ready",

                "store" => "00000000000000000000000000010002",

                "type" => "features",

                "url" => "",

                "version" => ""

            ),

            6 => array(

                "description" => "This functionality enables the flexibility to send mails from different email servers or configurations.",

                "enabled" => false,

                "id" => "sendEmailFromDifferentEmailServers",

                "latest_version" => "",

                "log" => null,

                "name" => "sendEmailFromDifferentEmailServers",

                "nick" => "sendEmailFromDifferentEmailServers",

                "progress" => 0,

                "publisher" => "Colosa",

                "release_type" => "localRegistry",

                "status" => "ready",

                "store" => "00000000000000000000000000010003",

                "type" => "features",

                "url" => "",

                "version" => ""

            ),

            7 => array(

                "description"    => "Enables the code scanner feature.",

                "enabled"        => false,

                "id"             => "codeScanner",

                "latest_version" => "",

                "log"            => null,

                "name"           => "codeScanner",

                "nick"           => "codeScanner",

                "progress"       => 0,

                "publisher"      => "Colosa",

                "release_type"   => "localRegistry",

                "status"         => "ready",

                "store"          => "00000000000000000000000000010007",

                "type"           => "features",

                "url"            => "",

                "version"        => ""

            ),

            8 => array(

                "description"    => "Enables the multiple email configuration feature.",

                "enabled"        => false,

                "id"             => "multipleEmailServers",

                "latest_version" => "",

                "log"            => null,

                "name"           => "multipleEmailServers",

                "nick"           => "multipleEmailServers",

                "progress"       => 0,

                "publisher"      => "Colosa",

                "release_type"   => "localRegistry",

                "status"         => "ready",

                "store"          => "00000000000000000000000000010009",

                "type"           => "features",

                "url"            => "",

                "version"        => ""

            ),

            9 => array(

                "description"    => "Enables the mobile fields.",

                "enabled"        => false,

                "id"             => "mobileFields",

                "latest_version" => "",

                "log"            => null,

                "name"           => "mobileFields",

                "nick"           => "mobileFields",

                "progress"       => 0,

                "publisher"      => "Colosa",

                "release_type"   => "localRegistry",

                "status"         => "ready",

                "store"          => "00000000000000000000000000010008",

                "type"           => "features",

                "url"            => "",

                "version"        => ""

            )

        );



    private static $instancefeature = null;



    /**

     * This function is the constructor of the PMLicensedFeatures class

     * param

     *

     * @return void

     */

    public function __construct ()

    {

        $criteria = new Criteria();

        $criteria->addAscendingOrderByColumn(AddonsManagerPeer::ADDON_ID);

        $criteria->add(AddonsManagerPeer::ADDON_TYPE, 'feature', Criteria::EQUAL);

        $addons = AddonsManagerPeer::doSelect($criteria);

        foreach ($addons as $addon) {

            $this->features[] = $addon->getAddonId();

            $detail = new featuresDetail($addon->getAddonNick(), $addon->getAddonDescription());

            $this->featuresDetails[$addon->getAddonId()] = $detail;

        }

    }



    /**

     * This function is instancing to this class

     * param

     *

     * @return object

     */

    public static function getSingleton ()

    {

        if (self::$instancefeature == null) {

            self::$instancefeature = new PMLicensedFeatures();

        }

        return self::$instancefeature;

    }

    /*----------------------------------********---------------------------------*/

}


