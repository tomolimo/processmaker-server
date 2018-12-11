<?php

namespace ProcessMaker\AuditLog;

use Bootstrap;
use Configurations;
use Exception;
use G;
use ProcessMaker\Core\System;
use Symfony\Component\Finder\Finder;

class AuditLog
{
    const READ_LOGGING_LEVEL = false;
    private $actions = [];
    private $columns;
    private $pageSizeDefault = 20;
    private $pathGlobalLog;
    private $userFullname = "";
    private $userLogged = "";

    /**
     * Class constructor.
     */
    function __construct()
    {
        $this->pathGlobalLog = PATH_DATA . 'log';
        $this->columns = ['date', 'workspace', 'ip', 'id', 'user', 'action', 'description'];

        $this->actions = [
            "CreateUser" => G::LoadTranslation("ID_CREATE_USER"),
            "UpdateUser" => G::LoadTranslation("ID_UPDATE_USER"),
            "DeleteUser" => G::LoadTranslation("ID_DELETE_USER"),
            "EnableUser" => G::LoadTranslation("ID_ENABLE_USER"),
            "DisableUser" => G::LoadTranslation("ID_DISABLE_USER"),
            "AssignAuthenticationSource" => G::LoadTranslation("ID_ASSIGN_AUTHENTICATION_SOURCE"),
            "AssignUserToGroup" => G::LoadTranslation("ID_ASSIGN_USER_TO_GROUP"),
            "CreateAuthSource" => G::LoadTranslation("ID_CREATE_AUTH_SOURCE"),
            "UpdateAuthSource" => G::LoadTranslation("ID_UPDATE_AUTH_SOURCE"),
            "DeleteAuthSource" => G::LoadTranslation("ID_DELETE_AUTH_SOURCE"),
            "CreateRole" => G::LoadTranslation("ID_CREATE_ROLE"),
            "UpdateRole" => G::LoadTranslation("ID_UPDATE_ROLE"),
            "DeleteRole" => G::LoadTranslation("ID_DELETE_ROLE"),
            "AssignUserToRole" => G::LoadTranslation("ID_ASSIGN_USER_TO_ROLE"),
            "DeleteUserToRole" => G::LoadTranslation("ID_DELETE_USER_TO_ROLE"),
            "AddPermissionToRole" => G::LoadTranslation("ID_ADD_PERMISSION_TO_ROLE"),
            "DeletePermissionToRole" => G::LoadTranslation("ID_DELETE_PERMISSION_TO_ROLE"),
            "CreateSkin" => G::LoadTranslation("ID_CREATE_SKIN"),
            "ImportSkin" => G::LoadTranslation("ID_IMPORT_SKIN"),
            "ExportSkin" => G::LoadTranslation("ID_EXPORT_SKIN"),
            "DeleteSkin" => G::LoadTranslation("ID_DELETE_SKIN"),
            "CreateGroup" => G::LoadTranslation("ID_CREATE_GROUP"),
            "UpdateGroup" => G::LoadTranslation("ID_UPDATE_GROUP"),
            "DeleteGroup" => G::LoadTranslation("ID_DELETE_GROUP"),
            "CreateCategory" => G::LoadTranslation("ID_CREATE_CATEGORY"),
            "UpdateCategory" => G::LoadTranslation("ID_UPDATE_CATEGORY"),
            "DeleteCategory" => G::LoadTranslation("ID_DELETE_CATEGORY"),
            "BuildCache" => G::LoadTranslation("ID_BUILD_CACHE"),
            "ClearCache" => G::LoadTranslation("ID_CLEAR_CACHE"),
            "ClearCron" => G::LoadTranslation("ID_CLEAR_CRON"),
            "UpdateEnvironmentSettings" => G::LoadTranslation("ID_UPDATE_ENVIRONMENT_SETTINGS"),
            "UpdateLoginSettings" => G::LoadTranslation("ID_UPDATE_LOGIN_SETTINGS"),
            "EnableHeartBeat" => G::LoadTranslation("ID_ENABLE_HEART_BEAT"),
            "DisableHeartBeat" => G::LoadTranslation("ID_DISABLE_HEART_BEAT"),
            "CreatePmtable" => G::LoadTranslation("ID_CREATE_PMTABLE"),
            "UpdatePmtable" => G::LoadTranslation("ID_UPDATE_PMTABLE"),
            "DeletePmtable" => G::LoadTranslation("ID_DELETE_PMTABLE"),
            "AddDataPmtable" => G::LoadTranslation("ID_ADD_DATA_PMTABLE"),
            "UpdateDataPmtable" => G::LoadTranslation("ID_UPDATE_DATA_PMTABLE"),
            "DeleteDataPmtable" => G::LoadTranslation("ID_DELETE_DATA_PMTABLE"),
            "ImportTable" => G::LoadTranslation("ID_IMPORT_TABLE"),
            "ExportTable" => G::LoadTranslation("ID_EXPORT_TABLE"),
            "CreateCalendar" => G::LoadTranslation("ID_CREATE_CALENDAR"),
            "UpdateCalendar" => G::LoadTranslation("ID_UPDATE_CALENDAR"),
            "DeleteCalendar" => G::LoadTranslation("ID_DELETE_CALENDAR"),
            "CreateDashletInstance" => G::LoadTranslation("ID_CREATE_DASHLET_INSTANCE"),
            "UpdateDashletInstance" => G::LoadTranslation("ID_UPDATE_DASHLET_INSTANCE"),
            "DeleteDashletInstance" => G::LoadTranslation("ID_DELETE_DASHLET_INSTANCE"),
            "CreateDepartament" => G::LoadTranslation("ID_CREATE_DEPARTAMENT"),
            "CreateSubDepartament" => G::LoadTranslation("ID_CREATE_SUB_DEPARTAMENT"),
            "UpdateDepartament" => G::LoadTranslation("ID_UPDATE_DEPARTAMENT"),
            "UpdateSubDepartament" => G::LoadTranslation("ID_UPDATE_SUB_DEPARTAMENT"),
            "DeleteDepartament" => G::LoadTranslation("ID_DELETE_DEPARTAMENT"),
            "AssignManagerToDepartament" => G::LoadTranslation("ID_ASSIGN_MANAGER_TO_DEPARTAMENT"),
            "AssignUserToDepartament" => G::LoadTranslation("ID_ASSIGN_USER_TO_DEPARTAMENT"),
            "RemoveUsersFromDepartament" => G::LoadTranslation("ID_REMOVE_USERS_FROM_DEPARTAMENT"),
            "AssignUserToGroup" => G::LoadTranslation("ID_ASSIGN_USER_TO_GROUP"),
            "UploadLanguage" => G::LoadTranslation("ID_UPLOAD_LANGUAGE"),
            "ExportLanguage" => G::LoadTranslation("ID_EXPORT_LANGUAGE"),
            "DeleteLanguage" => G::LoadTranslation("ID_DELETE_LAGUAGE"),
            "UploadSystemSettings" => G::LoadTranslation("ID_UPLOAD_SYSTEM_SETTINGS"),
            "UpdateEmailSettings" => G::LoadTranslation("ID_UPDATE_EMAIL_SETTINGS"),
            "CreateEmailSettings" => G::LoadTranslation("ID_CREATE_EMAIL_SETTINGS"),
            "UploadLogo" => G::LoadTranslation("ID_UPLOAD_LOGO"),
            "DeleteLogo" => G::LoadTranslation("ID_DELETE_LOGO"),
            "RestoreLogo" => G::LoadTranslation("ID_RESTORE_LOGO"),
            "ReplaceLogo" => G::LoadTranslation("ID_REPLACE_LOGO"),
            "InstallPlugin" => G::LoadTranslation("ID_INSTALL_PLUGIN"),
            "EnablePlugin" => G::LoadTranslation("ID_ENABLE_PLUGIN"),
            "DisablePlugin" => G::LoadTranslation("ID_DISABLE_PLUGIN"),
            "RemovePlugin" => G::LoadTranslation("ID_REMOVE_PLUGIN"),
            "SetColumns" => G::LoadTranslation("ID_SET_COLUMNS"),
            "EnableAuditLog" => G::LoadTranslation("ID_ENABLE_AUDIT_LOG"),
            "DisableAuditLog" => G::LoadTranslation("ID_DISABLE_AUDIT_LOG"),
            "EditProcess" => G::LoadTranslation("ID_EDIT_PROCESS"),
            "ExportProcess" => G::LoadTranslation("ID_EXPORT_PROCESS"),
            "WebEntry" => G::LoadTranslation("ID_WEB_ENTRY"),
            "AssignRole" => G::LoadTranslation("ID_ASSIGN_ROLE"),
            "RemoveUser" => G::LoadTranslation("ID_REMOVE_USER"),
            "AddTask" => G::LoadTranslation("ID_ADD_TASK"),
            "AddSubProcess" => G::LoadTranslation("ID_ADD_SUB_PROCESS"),
            "SaveTaskPosition" => G::LoadTranslation("ID_SAVE_TASK_POSITION"),
            "AddHorizontalLine" => G::LoadTranslation("ID_ADD_HORIZONTAL_LINE"),
            "AddVerticalLine" => G::LoadTranslation("ID_ADD_VERTICAL_LINE"),
            "SaveGuidePosition" => G::LoadTranslation("ID_SAVE_GUIDE_POSITION"),
            "DeleteLine" => G::LoadTranslation("ID_DELETE_LINE"),
            "DeleteLines" => G::LoadTranslation("ID_DELETE_LINES"),
            "AddText" => G::LoadTranslation("ID_ADD_TEXT"),
            "UpdateText" => G::LoadTranslation("ID_UPDATE_TEXT"),
            "SaveTextPosition" => G::LoadTranslation("ID_SAVE_TEXT_POSITION"),
            "DeleteText" => G::LoadTranslation("ID_DELETE_TEXT"),
            "ProcessFileManager" => G::LoadTranslation("ID_PROCESS_FILE_MANAGER"),
            "ProcessPermissions" => G::LoadTranslation("ID_PROCESS_PERMISSIONS"),
            "DeletePermissions" => G::LoadTranslation("ID_DELETE_PERMISSIONS"),
            "AssignSupervisorDynaform" => G::LoadTranslation("ID_ASSIGN_SUPERVISOR_DYNAFORM"),
            "RemoveSupervisorDynaform" => G::LoadTranslation("ID_REMOVE_SUPERVISOR_DYNAFORM"),
            "AssignSupervisorInput" => G::LoadTranslation("ID_ASSIGN_SUPERVISOR_INPUT"),
            "RemoveSupervisorInput" => G::LoadTranslation("ID_REMOVE_SUPERVISOR_INPUT"),
            "CaseTrackers" => G::LoadTranslation("ID_CASE_TRACKERS"),
            "EditEvent" => G::LoadTranslation("ID_EDIT_EVENT"),
            "DeleteEvent" => G::LoadTranslation("ID_EVENT_DELETED"),
            "CreateDynaform" => G::LoadTranslation("ID_CREATE_DYNAFORM"),
            "UpdateDynaform" => G::LoadTranslation("ID_UPDATE_DYNAFORM"),
            "DeleteDynaform" => G::LoadTranslation("ID_DELETE_DYNAFORM"),
            "ConditionsEditorDynaform" => G::LoadTranslation("ID_CONDITIONS_EDITOR_DYNAFORM"),
            "CreateCaseScheduler" => G::LoadTranslation("ID_CREATE_CASE_SCHEDULER"),
            "UpdateCaseScheduler" => G::LoadTranslation("ID_UPDATE_CASE_SCHEDULER"),
            "DeleteCaseScheduler" => G::LoadTranslation("ID_DELETE_CASE_SCHEDULER"),
            "CreateDatabaseConnection" => G::LoadTranslation("ID_CREATE_DATABASE_CONNECTION"),
            "UpdateDatabaseConnection" => G::LoadTranslation("ID_UPDATE_DATABASE_CONNECTION"),
            "DeleteDatabaseConnection" => G::LoadTranslation("ID_DELETE_DATABASE_CONNECTION"),
            "CreateInputDocument" => G::LoadTranslation("ID_CREATE_INPUT_DOCUMENT"),
            "UpdateInputDocument" => G::LoadTranslation("ID_UPDATE_INPUT_DOCUMENT"),
            "DeleteInputDocument" => G::LoadTranslation("ID_DELETE_INPUT_DOCUMENT"),
            "CreateOutputDocument" => G::LoadTranslation("ID_CREATE_OUTPUT_DOCUMENT"),
            "UpdateOutputDocument" => G::LoadTranslation("ID_UPDATE_OUTPUT_DOCUMENT"),
            "DeleteOutputDocument" => G::LoadTranslation("ID_DELETE_OUTPUT_DOCUMENT"),
            "CreateTrigger" => G::LoadTranslation("ID_CREATE_TRIGGER"),
            "UpdateTrigger" => G::LoadTranslation("ID_UPDATE_TRIGGER"),
            "DeleteTrigger" => G::LoadTranslation("ID_DELETE_TRIGGER"),
            "DerivationRule" => G::LoadTranslation("ID_DERIVATION_RULE"),
            "DeleteTask" => G::LoadTranslation("ID_DELETE_TASK"),
            "DeleteSubProcess" => G::LoadTranslation("ID_DELETE_SUB_PROCESS"),
            "OptionsMenuTask" => G::LoadTranslation("ID_OPTIONS_MENU_TASK"),
            "SaveTaskProperties" => G::LoadTranslation("ID_SAVE_TASK_PROPERTIES"),
            "DeleteRoutes" => G::LoadTranslation("ID_DELETE_ROUTES"),
            "NewConditionFromStep" => G::LoadTranslation("ID_NEW_CONDITION_FROM_STEP"),
            "AssignTrigger" => G::LoadTranslation("ID_ASSIGN_TRIGGER"),
            "UpTrigger" => G::LoadTranslation("ID_UP_TRIGGER"),
            "DownTrigger" => G::LoadTranslation("ID_DOWN_TRIGGER"),
            "StepDelete" => G::LoadTranslation("ID_STEP_DELETE"),
            "StepUp" => G::LoadTranslation("ID_STEP_UP"),
            "StepDown" => G::LoadTranslation("ID_STEP_DOWN"),
            "SaveNewStep" => G::LoadTranslation("ID_SAVE_NEW_STEP"),
            "AssignUserTask" => G::LoadTranslation("ID_ASSIGN_USER_TASK"),
            "AssignGroupTask" => G::LoadTranslation("ID_ASSIGN_GROUP_TASK"),
            "DeleteUserTask" => G::LoadTranslation("ID_DELETE_USER_TASK"),
            "DeleteGroupTask" => G::LoadTranslation("ID_DELETE_GROUP_TASK"),
            "ImportProcess" => G::LoadTranslation("ID_IMPORT_PROCESS"),
            "DeleteProcess" => G::LoadTranslation("ID_DELETE_PROCESS"),
            "GSuiteConfigurationSaved" => G::LoadTranslation("ID_G_SUITE_CONFIGURATION_SAVED"),
            "GSuiteConnect" => G::LoadTranslation("ID_G_SUITE_CONNECT"),
            "GSuiteDisconnect" => G::LoadTranslation("ID_G_SUITE_DISCONNECT"),
            "GSuiteLoadGroups" => G::LoadTranslation("ID_G_SUITE_LOAD_GROUPS"),
            "GSuiteSyncUsers" => G::LoadTranslation("ID_G_SUITE_SYNC_USERS")
        ];
    }

    /**
     * Set the identifier of the logged user.
     * 
     * @param string $userLogged
     */
    function setUserLogged($userLogged)
    {
        $this->userLogged = $userLogged;
    }

    /**
     * Set the full name of the logged user.
     * 
     * @param string $userFullname
     */
    function setUserFullname($userFullname)
    {
        $this->userFullname = $userFullname;
    }

    /**
     * Get the configuration for the Audit Log.
     * 
     * @return array
     */
    public function getConfig()
    {
        $configurations = new Configurations();
        $configPage = $configurations->getConfiguration("auditLogList", "pageSize", null, $this->userLogged);

        $config = [];
        $config["pageSize"] = isset($configPage["pageSize"]) ? $configPage["pageSize"] : $this->pageSizeDefault;

        return $config;
    }

    /**
     * Get the actions for Audit Log.
     * 
     * @return array
     */
    public function getActions()
    {
        $actions = [];
        $actions[] = ["ALL", G::LoadTranslation("ID_ALL")];
        /**
         * We arrange the arrangement to create an ordered list and that the option 
         * 'All' be found at the beginning.
         */
        asort($this->actions);
        foreach ($this->actions as $key => $value) {
            $actions[] = [$key, $value];
        }
        return $actions;
    }

    /**
     * Get the data of the files registered by Audit Log.
     * 
     * @param array $filter
     * @param int $limit
     * @param int $start
     * @return array
     */
    public function getAuditLogData($filter, $limit, $start)
    {
        $result = [];
        $count = 0;

        $files = $this->getFiles($this->pathGlobalLog, 'audit*.log');
        foreach ($files as $file) {
            $lines = file($file->getPathname());
            foreach ($lines as $line) {
                if ($start <= $count && count($result) < $limit) {
                    $data = $this->lineToObject($line);
                    if ($this->validate($filter, $data)) {
                        $result[] = $this->lineToArray($data);
                    }
                }
                $count = $count + 1;
            }
        }

        //from workspace
        $system = System::getSystemConfiguration();
        $path = PATH_DATA . 'sites' . PATH_SEP . config('system.workspace') . PATH_SEP . 'log' . PATH_SEP;
        if (isset($system['logs_location']) && !empty($system['logs_location']) && is_dir($system['logs_location'])) {
            $path = $system['logs_location'];
        }
        $files = $this->getFiles($path, 'audit*.log');
        foreach ($files as $file) {
            $lines = file($file->getPathname());
            foreach ($lines as $line) {
                if ($start <= $count && count($result) < $limit) {
                    /**
                     * processmaker/gulliver/system/class.monologProvider.php
                     * "<%level%> %datetime% %channel% %level_name%: %message% %context% %extra%\n"
                     */
                    $data = $this->lineToObject($line, '/([A-Z][a-z][a-z]\s{1,2}\d{1,2}\s\d{2}[:]\d{2}[:]\d{2})\s([\w][\w\d\.@-]*)\s(.*)$/');
                    if ($this->validate($filter, $data)) {
                        $result[] = $this->lineToArray($data);
                    }
                }
                $count = $count + 1;
            }
        }

        return [$count, $result];
    }

    /**
     * Register an action for Audit Log.
     * 
     * @param string $action
     * @param string $value
     */
    public function register($action, $value = '')
    {
        $context = Bootstrap::getDefaultContextLog();
        $context['usrUid'] = $this->userLogged;
        $context['usrName'] = $this->userFullname;
        $context['action'] = $action;
        $context['description'] = $value;
        Bootstrap::registerMonolog(
            $action,
            200,
            $action,
            $context,
            $context['workspace'],
            'audit.log',
            self::READ_LOGGING_LEVEL
        );
    }

    /**
     * Get the Audit Log files.
     * 
     * @param string $path
     * @param string $pattern
     * @param string $dir
     * @return array
     */
    private function getFiles($path, $pattern = '', $dir = 'ASC')
    {
        $finder = new Finder();
        $finder->files()
                ->in($path)
                ->name($pattern);
        $files = iterator_to_array($finder->getIterator());
        uasort($files, function ($a, $b) use ($dir) {
            $name1 = $a->getFilename();
            $name2 = $b->getFilename();
            if ($dir === 'ASC') {
                return strcmp($name1, $name2);
            } else {
                return strcmp($name2, $name1);
            }
        });
        return $files;
    }

    /**
     * Transforms a line of content from the file into an object.
     * 
     * @param string $line
     * @param string $pattern
     * @return object
     */
    private function lineToObject($line, $pattern = '|')
    {
        $result = [];
        $data = [];
        if ($pattern === '|') {
            $data = explode("|", $line);
        } else {
            $data = $this->getDataFromJson($line, $pattern);
        }

        foreach ($this->columns as $index => $column) {
            $result[$column] = isset($data[$index]) ? trim($data[$index]) : '';
        }
        return (object) $result;
    }

    /**
     * Gets the Json data stored from a line of contents of Audit Log files.
     * 
     * @param string $line
     * @param string $pattern
     * @return array
     */
    private function getDataFromJson($line, $pattern)
    {
        /**
         * $matches[0]: datetime
         * $matches[1]: channel
         * $matches[2]: level_name + message + context
         */
        preg_match($pattern, $line, $matches);
        array_shift($matches);

        if (!isset($matches[2])) {
            return [];
        }

        $data = $matches[2];
        $position = strpos($data, ' {');
        if ($position === false) {
            return [];
        }

        $data = substr($data, $position);
        $data = str_replace('} {', '}, {', $data);
        $data = '[' . $data . ']';
        try {
            $data = G::json_decode($data);
        } catch (Exception $e) {
            return [];
        }

        $join = [];
        foreach ($data as $value) {
            $value = (array) $value;
            $join = array_merge($join, $value);
        }
        $join = (object) $join;

        return [
            empty($join->timeZone) ? '' : $join->timeZone,
            empty($join->workspace) ? '' : $join->workspace,
            empty($join->ip) ? '' : $join->ip,
            empty($join->usrUid) ? '' : $join->usrUid,
            empty($join->usrName) ? '' : $join->usrName,
            empty($join->action) ? '' : $join->action,
            empty($join->description) ? '' : $join->description
        ];
    }

    /**
     * Apply filters to an Audit Log record.
     * 
     * @param array $filter
     * @param object $data
     * @return boolean
     */
    private function validate($filter, $data)
    {
        $result = true;
        $date = !empty($data->date) ? $this->mktimeDate($data->date) : 0;
        if ($filter["workspace"] != $data->workspace) {
            $result = false;
        }

        if ($filter["action"] != "ALL") {
            if ($data->action != $filter["action"]) {
                $result = false;
            }
        }

        if ($filter["dateFrom"] && $date > 0) {
            if (!($this->mktimeDate($filter["dateFrom"]) <= $date)) {
                $result = false;
            }
        }

        if ($filter["dateTo"] && $date > 0) {
            if (!($date <= $this->mktimeDate($filter["dateTo"] . " 23:59:59"))) {
                $result = false;
            }
        }

        if ($filter["description"]) {
            $result = false;
            $string = $filter["description"];

            if ((stristr($data->date, $string) !== false) ||
                    (stristr($data->ip, $string) !== false) ||
                    (stristr($data->user, $string) !== false) ||
                    (stristr($data->action, $string) !== false) ||
                    (stristr($data->description, $string) !== false)) {
                $result = true;
            }
        }
        return $result;
    }

    /**
     * Get the timestamp of the date given.
     * 
     * @param string $date
     * @return int
     */
    private function mktimeDate($date)
    {
        $array = getdate(strtotime($date));
        $mktime = mktime($array["hours"], $array["minutes"], $array["seconds"], $array["mon"], $array["mday"], $array["year"]);
        return $mktime;
    }

    /**
     * Obtain the corresponding arrangement for the columns of the Audit Log user 
     * interface.
     * 
     * @param array $data
     * @return array
     */
    private function lineToArray($data)
    {
        $action = $data->action;
        $action = preg_replace('/([A-Z])/', '_$1', $data->action);
        $action = "ID" . strtoupper($action);
        $action = G::LoadTranslation($action);
        return [
            "DATE" => $data->date,
            "USER" => $data->user,
            "IP" => $data->ip,
            "ACTION" => $action,
            "DESCRIPTION" => $data->description
        ];
    }
}
