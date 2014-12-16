<?php
namespace ProcessMaker\BusinessModel;

use \G;

class FilesManager
{
    /**
     * Return the Process Files Manager
     *
     * @param string $sProcessUID {@min 32} {@max 32}
     *
     * return array
     *
     * @access public
     */
    public function getProcessFilesManager($sProcessUID)
    {
        try {
            $aDirectories[] = array('name' => "templates",
                                    'type' => "folder",
                                    'path' => "/",
                                    'editable' => false);
            $aDirectories[] = array('name' => "public",
                                    'type' => "folder",
                                    'path' => "/",
                                    'editable' => false);
            return $aDirectories;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Return the Process Files Manager Path
     *
     * @param string $sProcessUID {@min 32} {@max 32}
     * @param string $path
     *
     * return array
     *
     * @access public
     */
    public function getProcessFilesManagerPath($sProcessUID, $path)
    {
        try {
            $checkPath = substr($path, -1);
            if ($checkPath == '/') {
                $path = substr($path, 0, -1);
            }
            $sMainDirectory = current(explode("/", $path));
            if (strstr($path,'/')) {
                $sSubDirectory = substr($path, strpos($path, "/")+1). PATH_SEP ;
            } else {
                $sSubDirectory = '';
            }
            switch ($sMainDirectory) {
                case 'templates':
                    $sDirectory = PATH_DATA_MAILTEMPLATES . $sProcessUID . PATH_SEP . $sSubDirectory;
                    break;
                case 'public':
                    $sDirectory = PATH_DATA_PUBLIC . $sProcessUID . PATH_SEP . $sSubDirectory;
                    break;
                default:
                    throw new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_FOR", array('path')));
                    break;
            }
            \G::verifyPath($sDirectory, true);
            $aTheFiles = array();
            $aFiles = array();
            $oDirectory = dir($sDirectory);
            while ($sObject = $oDirectory->read()) {
                if (($sObject !== '.') && ($sObject !== '..')) {
                    $sPath = $sDirectory . $sObject;
                    if (is_dir($sPath)) {
                        $aTheFiles[] = array('prf_name' => $sObject,
                                             'prf_type' => "folder",
                                             'prf_path' => $sMainDirectory);
                    } else {
                        $aAux = pathinfo($sPath);
                        $aAux['extension'] = (isset($aAux['extension'])?$aAux['extension']:'');
                        $aFiles[] = array('FILE' => $sObject, 'EXT' => $aAux['extension'] );
                    }
                }
            }
            foreach ($aFiles as $aFile) {
                $arrayFileUid = $this->getFileManagerUid($sDirectory.$aFile['FILE']);
                $fcontent = file_get_contents($sDirectory.$aFile['FILE']);
                $fileUid =  $arrayFileUid["PRF_UID"];
                if ($fileUid != null) {
                    $oProcessFiles = \ProcessFilesPeer::retrieveByPK($fileUid);
                    $editable = $oProcessFiles->getPrfEditable();
                    if ($editable == '1') {
                        $editable = 'true';
                    } else {
                        $editable = 'false';
                    }
                    $aTheFiles[] = array( 'prf_uid' => $oProcessFiles->getPrfUid(),
                                          'prf_filename' => $aFile['FILE'],
                                          'usr_uid' => $oProcessFiles->getUsrUid(),
                                          'prf_update_usr_uid' => $oProcessFiles->getPrfUpdateUsrUid(),
                                          'prf_path' => $sMainDirectory. PATH_SEP .$sSubDirectory,
                                          'prf_type' => $oProcessFiles->getPrfType(),
                                          'prf_editable' => $editable,
                                          'prf_create_date' => $oProcessFiles->getPrfCreateDate(),
                                          'prf_update_date' => $oProcessFiles->getPrfUpdateDate(),
                                          'prf_content' => $fcontent);
                } else {
                    $extention = end(explode(".", $aFile['FILE']));
                    if ($extention == 'docx' || $extention == 'doc' || $extention == 'html' || $extention == 'php' || $extention == 'jsp'
                        || $extention == 'xlsx' || $extention == 'xls' || $extention == 'js' || $extention == 'css' || $extention == 'txt') {
                        $editable = 'true';
                    } else {
                        $editable = 'false';
                    }
                    $aTheFiles[] = array('prf_uid' => '',
                                         'prf_filename' => $aFile['FILE'],
                                         'usr_uid' => '',
                                         'prf_update_usr_uid' => '',
                                         'prf_path' => $sMainDirectory. PATH_SEP .$sSubDirectory,
                                         'prf_type' => 'file',
                                         'prf_editable' => $editable,
                                         'prf_create_date' => '',
                                         'prf_update_date' => '',
                                         'prf_content' => $fcontent);
                }
            }
            return $aTheFiles;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Return the Process File Manager
     *
     * @param string $sProcessUID {@min 32} {@max 32}
     * @param string $userUID {@min 32} {@max 32}
     * @param array  $aData
     *
     * return array
     *
     * @access public
     */
    public function addProcessFilesManager($sProcessUID, $userUID, $aData)
    {
        try {
            $aData['prf_path'] = rtrim($aData['prf_path'], '/') . '/';
            if (!$aData['prf_filename']) {
                throw new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_FOR", array('prf_filename')));
            }
            $extention = strstr($aData['prf_filename'], '.');
            if (!$extention) {
                $extention = '.html';
                $aData['prf_filename'] = $aData['prf_filename'].$extention;
            }
            if ($extention == '.docx' || $extention == '.doc' || $extention == '.html' || $extention == '.php' || $extention == '.jsp' ||
                $extention == '.xlsx' || $extention == '.xls' || $extention == '.js' || $extention == '.css' || $extention == '.txt') {
                $sEditable = true;
            } else {
                $sEditable = false;
            }
            $sMainDirectory = current(explode("/", $aData['prf_path']));
            if ($sMainDirectory != 'public' && $sMainDirectory != 'templates') {
                throw new \Exception(\G::LoadTranslation("ID_INVALID_PRF_PATH"));
            }
            if (strstr($aData['prf_path'],'/')) {
                $sSubDirectory = substr($aData['prf_path'], strpos($aData['prf_path'], "/")+1) ;
            } else {
                $sSubDirectory = '';
            }
            switch ($sMainDirectory) {
                case 'templates':
                    $sDirectory = PATH_DATA_MAILTEMPLATES . $sProcessUID . PATH_SEP . $sSubDirectory . $aData['prf_filename'];
                    $sCheckDirectory = PATH_DATA_MAILTEMPLATES . $sProcessUID . PATH_SEP . $sSubDirectory;
                    if ($extention != '.html') {
                        throw new \Exception(\G::LoadTranslation('ID_FILE_UPLOAD_INCORRECT_EXTENSION'));
                    }
                    break;
                case 'public':
                    $sDirectory = PATH_DATA_PUBLIC . $sProcessUID . PATH_SEP . $sSubDirectory . $aData['prf_filename'];
                    $sCheckDirectory = PATH_DATA_PUBLIC . $sProcessUID . PATH_SEP . $sSubDirectory;
                    $sEditable = false;
                    if ($extention == '.exe') {
                        throw new \Exception(\G::LoadTranslation('ID_FILE_UPLOAD_INCORRECT_EXTENSION'));
                    }
                    break;
                default:
                    $sDirectory = PATH_DATA_MAILTEMPLATES . $sProcessUID . PATH_SEP . $sSubDirectory . $aData['prf_filename'];
                    break;
            }
            $content = $aData['prf_content'];
            if (is_string($content)) {
                if (file_exists($sDirectory)) {
                    $directory = $sMainDirectory. PATH_SEP . $sSubDirectory . $aData['prf_filename'];
                    throw new \Exception(\G::LoadTranslation("ID_EXISTS_FILE", array($directory)));
                }
            }
            if (!file_exists($sCheckDirectory)) {
                $sPkProcessFiles = \G::generateUniqueID();
                $oProcessFiles = new \ProcessFiles();
                $sDate = date('Y-m-d H:i:s');
                $oProcessFiles->setPrfUid($sPkProcessFiles);
                $oProcessFiles->setProUid($sProcessUID);
                $oProcessFiles->setUsrUid($userUID);
                $oProcessFiles->setPrfUpdateUsrUid('');
                $oProcessFiles->setPrfPath($sCheckDirectory);
                $oProcessFiles->setPrfType('folder');
                $oProcessFiles->setPrfEditable('');
                $oProcessFiles->setPrfCreateDate($sDate);
                $oProcessFiles->save();
            }
            \G::verifyPath($sCheckDirectory, true);
            $sPkProcessFiles = \G::generateUniqueID();
            $oProcessFiles = new \ProcessFiles();
            $sDate = date('Y-m-d H:i:s');
            $oProcessFiles->setPrfUid($sPkProcessFiles);
            $oProcessFiles->setProUid($sProcessUID);
            $oProcessFiles->setUsrUid($userUID);
            $oProcessFiles->setPrfUpdateUsrUid('');
            $oProcessFiles->setPrfPath($sDirectory);
            $oProcessFiles->setPrfType('file');
            $oProcessFiles->setPrfEditable($sEditable);
            $oProcessFiles->setPrfCreateDate($sDate);
            $oProcessFiles->save();
            $fp = fopen($sDirectory, 'w');
            $content = $aData['prf_content'];
            fwrite($fp, $content);
            fclose($fp);
            $oProcessFile = array('prf_uid' => $oProcessFiles->getPrfUid(),
                                  'prf_filename' => $aData['prf_filename'],
                                  'usr_uid' => $oProcessFiles->getUsrUid(),
                                  'prf_update_usr_uid' => $oProcessFiles->getPrfUpdateUsrUid(),
                                  'prf_path' => $sMainDirectory. PATH_SEP . $sSubDirectory,
                                  'prf_type' => $oProcessFiles->getPrfType(),
                                  'prf_editable' => $oProcessFiles->getPrfEditable(),
                                  'prf_create_date' => $oProcessFiles->getPrfCreateDate(),
                                  'prf_update_date' => $oProcessFiles->getPrfUpdateDate(),
                                  'prf_content' => $content);
            return $oProcessFile;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Return the Process Files Manager
     *
     * @param string $prjUid {@min 32} {@max 32}
     * @param string $prfUid {@min 32} {@max 32}
     *
     *
     * @access public
     */
    public function uploadProcessFilesManager($prjUid, $prfUid)
    {
        try {
            $path = '';
            $criteria = new \Criteria("workflow");
            $criteria->addSelectColumn(\ProcessFilesPeer::PRF_PATH);
            $criteria->add(\ProcessFilesPeer::PRF_UID, $prfUid, \Criteria::EQUAL);
            $rsCriteria = \ProcessFilesPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $rsCriteria->next();
            while ($aRow = $rsCriteria->getRow()) {
                $path = $aRow['PRF_PATH'];
                $rsCriteria->next();
            }
            if ($path == '') {
                throw new \Exception(\G::LoadTranslation('ID_PMTABLE_UPLOADING_FILE_PROBLEM'));
            }
            $extention = strstr($_FILES['prf_file']['name'], '.');
            if (!$extention) {
                $extention = '.html';
                $_FILES['prf_file']['name'] = $_FILES['prf_file']['name'].$extention;
            }
            $file = end(explode("/",$path));
            $path = str_replace($file,'',$path);
            if ($file == $_FILES['prf_file']['name']) {
                if ($_FILES['prf_file']['error'] != 1) {
                    if ($_FILES['prf_file']['tmp_name'] != '') {
                        \G::uploadFile($_FILES['prf_file']['tmp_name'], $path, $_FILES['prf_file']['name']);
                    }
                }
            } else {
                throw new \Exception(\G::LoadTranslation('ID_PMTABLE_UPLOADING_FILE_PROBLEM'));
            }
            $oProcessFile = array('prf_uid' => $prfUid);
            return $oProcessFile;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get data of unique ids of a file
     *
     * @param string $path
     *
     * return array
     */
    public function getFileManagerUid($path)
    {
        try {
            $criteria = new \Criteria("workflow");
            $criteria->addSelectColumn(\ProcessFilesPeer::PRF_UID);
            $criteria->add(\ProcessFilesPeer::PRF_PATH, $path, \Criteria::EQUAL);
            $rsCriteria = \ProcessFilesPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $rsCriteria->next();
            return $rsCriteria->getRow();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Return the Process Files Manager
     *
     * @param string $sProcessUID {@min 32} {@max 32}
     * @param string $userUID {@min 32} {@max 32}
     * @param array  $aData
     * @param string $prfUid {@min 32} {@max 32}
     *
     * return array
     *
     * @access public
     */
    public function updateProcessFilesManager($sProcessUID, $userUID, $aData, $prfUid)
    {
        try {
            $path = '';
            $criteria = new \Criteria("workflow");
            $criteria->addSelectColumn(\ProcessFilesPeer::PRF_PATH);
            $criteria->add(\ProcessFilesPeer::PRF_UID, $prfUid, \Criteria::EQUAL);
            $rsCriteria = \ProcessFilesPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $rsCriteria->next();
            while ($aRow = $rsCriteria->getRow()) {
                $path = $aRow['PRF_PATH'];
                $rsCriteria->next();
            }
            if ($path == '') {
                throw new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_FOR", array('prf_uid')));
            }
            $sFile = end(explode("/",$path));
            $sPath = str_replace($sFile,'',$path);
            $sSubDirectory = substr(str_replace($sProcessUID,'',substr($sPath,(strpos($sPath, $sProcessUID)))),0,-1);
            $sMainDirectory = str_replace(substr($sPath, strpos($sPath, $sProcessUID)),'', $sPath);
            if ($sMainDirectory == PATH_DATA_MAILTEMPLATES) {
                $sMainDirectory = 'mailTemplates';
            } else {
                $sMainDirectory = 'public';
            }
            $extention = end(explode(".", $sFile));
            if ($extention == 'docx' || $extention == 'doc' || $extention == 'html' || $extention == 'php' || $extention == 'jsp' ||
                $extention == 'xlsx' || $extention == 'xls' || $extention == 'js' || $extention == 'css' || $extention == 'txt') {
                $sEditable = true;
            } else {
                $sEditable = false;
            }
            if ($sEditable == false) {
                throw new \Exception(\G::LoadTranslation("ID_UNABLE_TO_EDIT"));
            }
            $oProcessFiles = \ProcessFilesPeer::retrieveByPK($prfUid);
            $sDate = date('Y-m-d H:i:s');
            $oProcessFiles->setPrfUpdateUsrUid($userUID);
            $oProcessFiles->setPrfUpdateDate($sDate);
            $oProcessFiles->save();
            $fp = fopen($path, 'w');
            $content = $aData['prf_content'];
            fwrite($fp, $content);
            fclose($fp);
            $oProcessFile = array('prf_uid' => $oProcessFiles->getPrfUid(),
                                  'prf_filename' => $sFile,
                                  'usr_uid' => $oProcessFiles->getUsrUid(),
                                  'prf_update_usr_uid' => $oProcessFiles->getPrfUpdateUsrUid(),
                                  'prf_path' => $sMainDirectory.$sSubDirectory,
                                  'prf_type' => $oProcessFiles->getPrfType(),
                                  'prf_editable' => $sEditable,
                                  'prf_create_date' => $oProcessFiles->getPrfCreateDate(),
                                  'prf_update_date' => $oProcessFiles->getPrfUpdateDate(),
                                  'prf_content' => $content);
            return $oProcessFile;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @param string $sProcessUID {@min 32} {@max 32}
     * @param string $prfUid {@min 32} {@max 32}
     *
     *
     * @access public
     */
    public function deleteProcessFilesManager($sProcessUID, $prfUid)
    {
        try {
            $path = '';
            $criteria = new \Criteria("workflow");
            $criteria->addSelectColumn(\ProcessFilesPeer::PRF_PATH);
            $criteria->add(\ProcessFilesPeer::PRF_UID, $prfUid, \Criteria::EQUAL);
            $rsCriteria = \ProcessFilesPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $rsCriteria->next();
            while ($aRow = $rsCriteria->getRow()) {
                $path = $aRow['PRF_PATH'];
                $rsCriteria->next();
            }
            if ($path == '') {
                throw new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_FOR", array('prf_uid')));
            }

            if (file_exists($path)) {
                unlink($path);
            }

            $rs = \ProcessFilesPeer::doDelete($criteria);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @param string $sProcessUID {@min 32} {@max 32}
     * @param string $prfUid {@min 32} {@max 32}
     *
     *
     * @access public
     */
    public function downloadProcessFilesManager($sProcessUID, $prfUid)
    {
        try {
            $path = '';
            $criteria = new \Criteria("workflow");
            $criteria->addSelectColumn(\ProcessFilesPeer::PRF_PATH);
            $criteria->add(\ProcessFilesPeer::PRF_UID, $prfUid, \Criteria::EQUAL);
            $rsCriteria = \ProcessFilesPeer::doSelectRS($criteria);
            $rsCriteria->setFetchmode(\ResultSet::FETCHMODE_ASSOC);
            $rsCriteria->next();
            while ($aRow = $rsCriteria->getRow()) {
                $path = $aRow['PRF_PATH'];
                $rsCriteria->next();
            }
            if ($path == '') {
                throw new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_FOR", array('prf_uid')));
            }
            $sFile = end(explode("/",$path));
            $sPath = str_replace($sFile,'',$path);
            $sSubDirectory = substr(str_replace($sProcessUID,'',substr($sPath,(strpos($sPath, $sProcessUID)))),0,-1);
            $sMainDirectory = str_replace(substr($sPath, strpos($sPath, $sProcessUID)),'', $sPath);
            if ($sMainDirectory == PATH_DATA_MAILTEMPLATES) {
                $sMainDirectory = 'mailTemplates';
            } else {
                $sMainDirectory = 'public';
            }
            if (file_exists($path)) {
                $oProcessMap = new \processMap(new \DBConnection());
                $oProcessMap->downloadFile($sProcessUID,$sMainDirectory,$sSubDirectory,$sFile);
                die();
            } else {
                throw (new \Exception( 'Invalid value specified for path.'));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @param string $sProcessUID {@min 32} {@max 32}
     * @param array  $path
     *
     * @access public
     */
    public function deleteFolderProcessFilesManager($sProcessUID, $path)
    {
        try {
            $sDirToDelete = end(explode("/",$path));
            $sPath = str_replace($sDirToDelete,'',$path);
            $sSubDirectory = substr(str_replace($sProcessUID,'',substr($sPath,(strpos($sPath, $sProcessUID)))),0,-1);
            $sMainDirectory = current(explode("/", $path));
            $sSubDirectory = substr(str_replace($sMainDirectory,'',$sSubDirectory),1);
            switch ($sMainDirectory) {
                case 'templates':
                    $sDirectory = PATH_DATA_MAILTEMPLATES . $sProcessUID . PATH_SEP . ($sSubDirectory != '' ? $sSubDirectory . PATH_SEP : '');
                    break;
                case 'public':
                    $sDirectory = PATH_DATA_PUBLIC . $sProcessUID . PATH_SEP . ($sSubDirectory != '' ? $sSubDirectory . PATH_SEP : '');
                    break;
                default:
                    die();
                    break;
            }
            if (file_exists($sDirectory.$sDirToDelete)) {
                \G::rm_dir($sDirectory.$sDirToDelete);
            } else {
                throw new \Exception(\G::LoadTranslation("ID_INVALID_VALUE_FOR", array('path')));
            }
            $criteria = new \Criteria("workflow");
            $criteria->addSelectColumn(\ProcessFilesPeer::PRF_PATH);
            $criteria->add( \ProcessFilesPeer::PRF_PATH, '%' . $sDirectory.$sDirToDelete. PATH_SEP . '%', \Criteria::LIKE );
            $rs = \ProcessFilesPeer::doDelete($criteria);
            return $sDirectory.$sDirToDelete;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     *
     * @param string $sProcessUID {@min 32} {@max 32}
     * @param string $prfUid {@min 32} {@max 32}
     *
     *
     * @access public
     */
    public function getProcessFileManager($sProcessUID, $prfUid)
    {
        try {
            $oProcessFiles = \ProcessFilesPeer::retrieveByPK($prfUid);
            $fcontent = file_get_contents($oProcessFiles->getPrfPath());
            $sFile = end(explode("/",$oProcessFiles->getPrfPath()));
            $path = $oProcessFiles->getPrfPath();
            $sPath = str_replace($sFile,'',$path);
            $sSubDirectory = substr(str_replace($sProcessUID,'',substr($sPath,(strpos($sPath, $sProcessUID)))),0,-1);
            $sMainDirectory = str_replace(substr($sPath, strpos($sPath, $sProcessUID)),'', $sPath);
            if ($sMainDirectory == PATH_DATA_MAILTEMPLATES) {
                $sMainDirectory = 'templates';
            } else {
                $sMainDirectory = 'public';
            }
            $oProcessFile = array('prf_uid' => $oProcessFiles->getPrfUid(),
                                  'prf_filename' => $sFile,
                                  'usr_uid' => $oProcessFiles->getUsrUid(),
                                  'prf_update_usr_uid' => $oProcessFiles->getPrfUpdateUsrUid(),
                                  'prf_path' => $sMainDirectory.$sSubDirectory,
                                  'prf_type' => $oProcessFiles->getPrfType(),
                                  'prf_editable' => $oProcessFiles->getPrfEditable(),
                                  'prf_create_date' => $oProcessFiles->getPrfCreateDate(),
                                  'prf_update_date' => $oProcessFiles->getPrfUpdateDate(),
                                  'prf_content' => $fcontent);
            return $oProcessFile;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

