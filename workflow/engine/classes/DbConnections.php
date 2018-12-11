<?php
use Illuminate\Support\Facades\Cache;

class DbConnections
{
    private $PRO_UID;
    public $connections;
    private $types;

    /*errors handle*/
    private $errno;
    private $errstr;

    private $encodesList;

    /**
     * construct of dbConnections
     *
     * @param string $pPRO_UID
     * @return void
     */
    public function __construct($pPRO_UID = null)
    {
        $this->errno = 0;
        $this->errstr = "";
        $this->PRO_UID = $pPRO_UID;

        $this->getAllConnections();
    }

    /**
     * getAllConnections
     *
     * @return Array $connections
     */
    public function getAllConnections()
    {
        if (isset($this->PRO_UID)) {
            $oDBSource = new DbSource();
            $oContent = new Content();
            $connections = array ();
            $types = array ();
            $this->have_any_connectios = false;

            $c = new Criteria();

            $c->clearSelectColumns();
            $c->addSelectColumn(DbSourcePeer::DBS_UID);
            $c->addSelectColumn(DbSourcePeer::PRO_UID);
            $c->addSelectColumn(DbSourcePeer::DBS_TYPE);
            $c->addSelectColumn(DbSourcePeer::DBS_SERVER);
            $c->addSelectColumn(DbSourcePeer::DBS_DATABASE_NAME);
            $c->addSelectColumn(DbSourcePeer::DBS_USERNAME);
            $c->addSelectColumn(DbSourcePeer::DBS_PASSWORD);
            $c->addSelectColumn(DbSourcePeer::DBS_PORT);
            $c->addSelectColumn(DbSourcePeer::DBS_ENCODE);
            $c->addSelectColumn(DbSourcePeer::DBS_CONNECTION_TYPE);
            $c->addSelectColumn(DbSourcePeer::DBS_TNS);
            $c->addSelectColumn(ContentPeer::CON_VALUE);

            $c->add(DbSourcePeer::PRO_UID, $this->PRO_UID);
            $c->add(ContentPeer::CON_CATEGORY, 'DBS_DESCRIPTION');
            $c->addJoin(DbSourcePeer::DBS_UID, ContentPeer::CON_ID);

            $result = DbSourcePeer::doSelectRS($c);
            $result->next();
            $row = $result->getRow();

            while ($row = $result->getRow()) {
                $connections[] = array (
                    "DBS_UID"             => $row[0],
                    "DBS_TYPE"            => $row[2],
                    "DBS_SERVER"          => $row[3],
                    "DBS_DATABASE_NAME"   => $row[4],
                    "DBS_USERNAME"        => $row[5],
                    "DBS_PASSWORD"        => $row[6],
                    "DBS_PORT"            => $row[7],
                    "DBS_ENCODE"          => $row[8],
                    "DBS_CONNECTION_TYPE" => $row[9],
                    "DBS_TNS"             => $row[10],
                    "CON_VALUE"           => $row[11]
                );

                $result->next();
            }
            if (! in_array($row[2], $types)) {
                $types[] = $row[2];
            }
            $this->connections = $connections;
            return $connections;
        }
    }

    /**
     * getConnections
     *
     * @param string $pType
     * @return Array $connections
     */
    public function getConnections($pType)
    {
        $connections = array ();
        foreach ($this->connections as $c) {
            if (trim($pType) == trim($c['DBS_TYPE'])) {
                $connections[] = $c;
            }
        }
        if (count($connections) > 0) {
            return $connections;
        } else {
            return false;
        }
    }

    /**
     * getConnectionsProUid
     *
     * Parameter $only list of items displayed, everything else is ignored.
     *
     * @param string $pProUid
     * @param string $only
     * @return Array $connections
     */
    public function getConnectionsProUid($pProUid, $only = array())
    {
        $connections = array ();
        $c = new Criteria();
        $c->clearSelectColumns();

        $c->addSelectColumn(DbSourcePeer::DBS_UID);
        $c->addSelectColumn(DbSourcePeer::PRO_UID);
        $c->addSelectColumn(DbSourcePeer::DBS_TYPE);
        $c->addSelectColumn(DbSourcePeer::DBS_SERVER);
        $c->addSelectColumn(DbSourcePeer::DBS_DATABASE_NAME);
        $c->addSelectColumn(DbSourcePeer::DBS_CONNECTION_TYPE);
        $c->addSelectColumn(DbSourcePeer::DBS_TNS);

        $result = DbSourcePeer::doSelectRS($c);
        $result->next();
        $row = $result->getRow();

        $sw = count($only) > 0;
        while ($row = $result->getRow()) {
            if ((trim($pProUid) == trim($row[1])) && ( $sw ? in_array($row[2], $only) : true )) {
                $dbUid = $row[0];

                $dbDescription = '';

                $criteria2 = new Criteria('workflow');

                $criteria2->addSelectColumn(ContentPeer::CON_VALUE);
                $criteria2->add(ContentPeer::CON_ID, $dbUid, Criteria::EQUAL);

                $rsCriteria2 = ContentPeer::doSelectRS($criteria2);
                $rsCriteria2->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                if ($rsCriteria2->next()) {
                    $row2 = $rsCriteria2->getRow();

                    if ($row2['CON_VALUE'] != '') {
                        $dbDescription = ' - [' . $row2['CON_VALUE'] . ']';
                    }
                }

                if ($row[5] == 'NORMAL') {
                    $connections[] = array('DBS_UID' => $row[0], 'DBS_NAME' => '[' . $row[3] . '] ' . $row[2] . ': ' . $row[4] . $dbDescription);
                } else {
                    $connections[] = array('DBS_UID' => $row[0], 'DBS_NAME' => '[' . $row[6] . '] ' . $row[2] . ': ' . $row[6] . $dbDescription);
                }
            }
            $result->next();
        }

        if (count($connections) > 0) {
            return $connections;
        } else {
            return array ();
        }
    }

    /**
     * loadAdditionalConnections
     *
     * @param $force boolean Determines if we should force load additional connections, even if lastProcessId is the same as active process
     * @return void
     */
    public static function loadAdditionalConnections($force = false)
    {
        // $lastProcessId determines what was the last process ID we processed.  If it was the same, we'll continue to
        static $lastProcessId = null;
        // We need to rebuild the Propel configuration, but this time, bring in any additional configuration for the active process
        if ($force || (isset($_SESSION['PROCESS']) && $_SESSION['PROCESS'] != $lastProcessId)) {
            // Get our current configuration
            $conf = Propel::getConfiguration();
            // Iterate through the datasources of configuration, and only care about workflow, rbac or rp. Remove anything else.
            foreach ($conf['datasources'] as $key => $val) {
                if (!in_array($key, ['workflow', 'rbac', 'rp'])) {
                    unset($conf['datasources'][$key]);
                }
            }
            // Now, let's fetch all external database connections for this process from cache
            $externalDbs = Cache::get('proc-' . $_SESSION['PROCESS'] . '-extdbs', function () {
                // Use explicit DbSource in the model namespace to ignore the global DbSource which is propel built
                // @todo Eventually remove the global DbSource and remove explicit namespace path
                return \ProcessMaker\Model\DbSource::where('PRO_UID', $_SESSION['PROCESS'])->get();
            });
            foreach ($externalDbs as $externalDb) {
                $conf['datasources'][$externalDb->DBS_UID] = [];
                $flagTns = ($externalDb->DBS_TYPE == "oracle" && $externalDb->DBS_CONNECTION_TYPE == "TNS")? 1 : 0;
                // Build the appropriate items to add to our Propel configuration
                // Let's grab the decrypted password
                $passw = '';
                if ($externalDb->DBS_PASSWORD != '') {
                    $aPassw = explode('_', $externalDb->DBS_PASSWORD);
                    $passw = $aPassw[0];

                    if (sizeof($aPassw) > 1 && $flagTns === 0) {
                        $passw = ($passw == "none")? "" : G::decrypt($passw, $externalDb->DBS_DATABASE_NAME);
                    } else {
                        $passw = ($passw == "none")? "" : G::decrypt($passw, $externalDb->DBS_TNS);
                    }
                }
                // Check for TNS for Oracle
                if ($flagTns == 0) {
                    // Not TNS, build a standard configuration
                    $dbsPort = ($externalDb->DBS_PORT == '') ? ('') : (':' . $externalDb->DBS_PORT);
                    $encoding = (trim($externalDb->DBS_ENCODE) == '') ? '' : '?encoding=' . $externalDb->DBS_ENCODE;
                    if (strpos($externalDb->DBS_SERVER, "\\") && $externalDb->DBS_TYPE == 'mssql') {
                        // This is a microsoft SQL server which is using a netbios connection string
                        $conf['datasources'][$externalDb->DBS_UID]['connection'] = $externalDb->DBS_TYPE . '://'
                            . $externalDb->DBS_USERNAME . ':' . $passw . '@' . $externalDb->DBS_SERVER . '/'
                            . $externalDb->DBS_DATABASE_NAME . $encoding;
                    } else {
                        $conf['datasources'][$externalDb->DBS_UID]['connection'] = $externalDb->DBS_TYPE . '://'
                            . $externalDb->DBS_USERNAME . ':' . $passw . '@' . $externalDb->DBS_SERVER . $dbsPort . '/'
                            . $externalDb->DBS_DATABASE_NAME . $encoding;
                    }
                } else {
                    // Is oracle and TNS, let's provide a TNS based DSN
                    $conf["datasources"][$externalDb->DBS_UID]["connection"] = $externalDb->DBS_TYPE . "://"
                        . $externalDb->DBS_USERNAME . ":" . $passw . "@" . $externalDb->DBS_TNS;
                }
                $conf['datasources'][$externalDb->DBS_UID]['adapter'] = $externalDb->DBS_TYPE;
            }
            Propel::initConfiguration($conf);
            $lastProcessId = $_SESSION['PROCESS'];
        }
    }

    /**
     * getDbServicesAvailables
     *
     * @return array $servicesAvailables
     */
    public function getDbServicesAvailables()
    {
        $servicesAvailables = array ();

        $dbServices = array ('mysql' => array ('id' => 'mysql','command' => 'mysqli_connect','name' => 'MySql'
        ),'pgsql' => array ('id' => 'pgsql','command' => 'pg_connect','name' => 'PostgreSql'
        ),'mssql' => array ('id' => 'mssql','command' => 'mssql_connect','name' => 'Microsoft SQL Server (mssql extension)'
        ),'sqlsrv' => array ('id' => 'mssql','command' => 'sqlsrv_connect','name' => 'Microsoft SQL Server (sqlsrv extension)'
        ),'oracle' => array ('id' => 'oracle','command' => 'oci_connect','name' => 'Oracle'
        ));

        foreach ($dbServices as $service) {
            if (@function_exists($service['command'])) {
                $servicesAvailables[] = $service;
            }
        }
        return $servicesAvailables;
    }

    /**
     * showMsg
     *
     * @return void
     */
    public function showMsg()
    {
        if ($this->errno != 0) {
            $msg = "
        <center>
        <fieldset style='width:90%'><legend>Class NET</legend>
          <div align=left>
          <font color='red'>
            <b>NET::ERROR NO -> $this->errno<br/>
            NET::ERROR MSG -> $this->errstr</b>
          </font>
          </div>
        </fieldset>
        <center>";
            print ($msg) ;
        }
    }

    /**
     * getEncondeList
     *
     * @param string $engine
     * @return $this->ordx($this->encodesList);
     */
    public function getEncondeList($engine = '')
    {
        switch ($engine) {
            default:
            case 'mysql':
                $encodes = array (array ('big5','big5 - Big5 Traditional Chinese'
                ),array ('dec8','dec8 - DEC West European'
                ),array ('cp850','cp850 - DOS West European'
                ),array ('hp8','hp8 - HP West European'
                ),array ('koi8r','koi8r - KOI8-R Relcom Russian'
                ),array ('latin1','latin1 - cp1252 West European'
                ),array ('latin2','latin2 - ISO 8859-2 Central European'
                ),array ('swe7','swe7 - 7bit Swedish'
                ),array ('ascii','ascii - US ASCII'
                ),array ('ujis','ujis - EUC-JP Japanese'
                ),array ('sjis','sjis - Shift-JIS Japanese'
                ),array ('hebrew','hebrew - ISO 8859-8 Hebrew'
                ),array ('tis620','tis620 - TIS620 Thai'
                ),array ('euckr','euckr - EUC-KR Korean'
                ),array ('koi8u','koi8u - KOI8-U Ukrainian'
                ),array ('gb2312','gb2312 - GB2312 Simplified Chinese'
                ),array ('greek','greek - ISO 8859-7 Greek'
                ),array ('cp1250','cp1250 - Windows Central European'
                ),array ('gbk','gbk - GBK Simplified Chinese'
                ),array ('latin5','latin5 - ISO 8859-9 Turkish'
                ),array ('armscii8','armscii8 - ARMSCII-8 Armenian'
                ),array ('utf8','utf8 - UTF-8 Unicode'
                ),array ('ucs2','ucs2 - UCS-2 Unicode'
                ),array ('cp866','cp866 - DOS Russian'
                ),array ('keybcs2','keybcs2 - DOS Kamenicky Czech-Slovak'
                ),array ('macce','macce - Mac Central European'
                ),array ('macroman','macroman - Mac West European'
                ),array ('cp852','cp852 - DOS Central European'
                ),array ('latin7','atin7 - ISO 8859-13 Baltic'
                ),array ('cp1251','cp1251 - Windows Cyrillic'
                ),array ('cp1256','cp1256  - Windows Arabic'
                ),array ('cp1257','cp1257  - Windows Baltic'
                ),array ('binary','binary  - Binary pseudo charset'
                ),array ('geostd8','geostd8 - GEOSTD8 Georgian'
                ),array ('cp932','cp932] - SJIS for Windows Japanese'
                ),array ('eucjpms','eucjpms - UJIS for Windows Japanese'
                )
                );

                break;
            case 'pgsql':
                $encodes = array (array ("BIG5","BIG5"
                ),array ("EUC_CN","EUC_CN"
                ),array ("EUC_JP","EUC_JP"
                ),array ("EUC_KR","EUC_KR"
                ),array ("EUC_TW","EUC_TW"
                ),array ("GB18030","GB18030"
                ),array ("GBK","GBK"
                ),array ("ISO_8859_5","ISO_8859_5"
                ),array ("ISO_8859_6","ISO_8859_6"
                ),array ("ISO_8859_7","ISO_8859_7"
                ),array ("ISO_8859_8","ISO_8859_8"
                ),array ("JOHAB","JOHAB"
                ),array ("KOI8","KOI8"
                ),array ("selected","LATIN1"
                ),array ("LATIN2","LATIN2"
                ),array ("LATIN3","LATIN3"
                ),array ("LATIN4","LATIN4"
                ),array ("LATIN5","LATIN5"
                ),array ("LATIN6","LATIN6"
                ),array ("LATIN7","LATIN7"
                ),array ("LATIN8","LATIN8"
                ),array ("LATIN9","LATIN9"
                ),array ("LATIN10","LATIN10"
                ),array ("SJIS","SJIS"
                ),array ("SQL_ASCII","SQL_ASCII"
                ),array ("UHC","UHC"
                ),array ("UTF8","UTF8"
                ),array ("WIN866","WIN866"
                ),array ("WIN874","WIN874"
                ),array ("WIN1250","WIN1250"
                ),array ("WIN1251","WIN1251"
                ),array ("WIN1252","WIN1252"
                ),array ("WIN1256","WIN1256"
                ),array ("WIN1258","WIN1258"
                )
                );
                break;
            case 'mssql':
                $encodes = array (array ('utf8','utf8 - UTF-8 Unicode'
                )
                );
                break;
            case 'oracle':
                $encodes = array (
                    array ("UTF8",      "UTF8 - Unicode 3.0 UTF-8 Universal character set CESU-8 compliant"),
                    array ("UTFE",      "UTFE - EBCDIC form of Unicode 3.0 UTF-8 Universal character set"),
                    array ("AL16UTF16", "AL16UTF16 - Unicode 3.1 UTF-16 Universal character set"),
                    array ("AL32UTF8",  "AL32UTF8 - Unicode 3.1 UTF-8 Universal character set")
                );
                break;
        }

        $this->encodesList = $encodes;
        return $this->ordx($this->encodesList);
    }

    /**
     * getErrno
     *
     * @return integer $errno
     */
    public function getErrno()
    {
        return $this->errno;
    }

    /**
     * getErrmsg
     *
     * @return string errstr
     */
    public function getErrmsg()
    {
        return $this->errstr;
    }

    /**
     * getErrmsg
     *
     * @param array $m
     * @return array $aRet
     */
    public function ordx($m)
    {
        $aTmp = array ();
        $aRet = array ();
        for ($i = 0; $i < count($m); $i ++) {
            array_push($aTmp, $m[$i][0] . '|' . $m[$i][1]);
        }
        usort($aTmp, "strnatcasecmp");

        for ($i = 0; $i < count($aTmp); $i ++) {
            $x = explode('|', $aTmp[$i]);
            array_push($aRet, array ($x[0],$x[1]
            ));
        }
        return $aRet;
    }

    /**
     * Function encryptThepassw
     *
     * @author krlos Pacha C. <carlos@colosa.com>
     * @access public
     * @param string proUid
     * @return void
     */
    public function encryptThepassw($proUid)
    {
        $oDBSource = new DbSource();

        $c = new Criteria();
        $c->clearSelectColumns();
        $c->addSelectColumn(DbSourcePeer::DBS_UID);
        $c->addSelectColumn(DbSourcePeer::DBS_DATABASE_NAME);
        $c->addSelectColumn(DbSourcePeer::DBS_PASSWORD);
        $c->add(DbSourcePeer::PRO_UID, $proUid);
        $result = DbSourcePeer::doSelectRS($c);
        $result->next();
        $row = $result->getRow();
        while ($row = $result->getRow()) {
            if ($row[2] != '') {
                $aPass = explode('_', $row[2]);
                if (count($aPass) == 1) {
                    $passEncrypt = G::encrypt($row[2], $row[1]);
                    $passEncrypt .= "_2NnV3ujj3w";
                    $c2 = new Criteria('workflow');
                    $c2->add(DbSourcePeer::DBS_PASSWORD, $passEncrypt);
                    $c3 = new Criteria('workflow');
                    $c3->add(DbSourcePeer::DBS_UID, $row[0]);
                    BasePeer::doUpdate($c3, $c2, Propel::getConnection('workflow'));
                }
            }
            $result->next();
        }
        return 1;
    }

    /**
     * Function getPassWithoutEncrypt
     *
     * @author krlos Pacha C. <carlos@colosa.com>
     * @access public
     * @param string passw
     * @return string
     */
    public function getPassWithoutEncrypt($aInfoCon)
    {
        $passw = '';
        if ($aInfoCon['DBS_PASSWORD'] != '') {
            $aPassw = explode('_', $aInfoCon['DBS_PASSWORD']);
            $passw = $aPassw[0];

            $flagTns = ($aInfoCon["DBS_TYPE"] == "oracle" && $aInfoCon["DBS_CONNECTION_TYPE"] == "TNS")? 1 : 0;

            if (sizeof($aPassw) > 1 && $flagTns == 0) {
                $passw = ($passw == "none")? "" : G::decrypt($passw, $aInfoCon["DBS_DATABASE_NAME"]);
            } else {
                $passw = ($passw == "none")? "" : G::decrypt($passw, $aInfoCon["DBS_TNS"]);
            }
        }
        return $passw;
    }
}
