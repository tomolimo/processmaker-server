<?php
/**
 * databases.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 *
 */

if (defined('PATH_DB') && !empty(config("system.workspace"))) {

    if (!file_exists(PATH_DB . config("system.workspace") . '/db.php')) {
        throw new Exception("Could not find db.php in current workspace " . config("system.workspace"));
    }

    require_once(PATH_DB . config("system.workspace") . '/db.php');
    //to do: enable for other databases
    $dbType = DB_ADAPTER;
    $dsn = DB_ADAPTER . '://' . DB_USER . ':' . urlencode(DB_PASS) . '@' . DB_HOST . '/' . DB_NAME;

    //to do: enable a mechanism to select RBAC Database
    $dsnRbac = DB_ADAPTER . '://' . DB_RBAC_USER . ':' . urlencode(DB_RBAC_PASS) . '@' . DB_RBAC_HOST . '/' . DB_RBAC_NAME;

    //to do: enable a mechanism to select report Database
    $dsnReport = DB_ADAPTER . '://' . DB_REPORT_USER . ':' . urlencode(DB_REPORT_PASS) . '@' . DB_REPORT_HOST . '/' . DB_REPORT_NAME;

    switch (DB_ADAPTER) {
        case 'mysql':
            $dsn       .= '?encoding=utf8';
            $dsnRbac   .= '?encoding=utf8';
            $dsnReport .= '?encoding=utf8';
            break;
        case 'mssql':
        case 'sqlsrv':
            //$dsn       .= '?sendStringAsUnicode=false';
            //$dsnRbac   .= '?sendStringAsUnicode=false';
            //$dsnReport .= '?sendStringAsUnicode=false';
            break;
        default:
            break;
    }

    $pro ['datasources']['workflow']['connection'] = $dsn;
    $pro ['datasources']['workflow']['adapter'] = DB_ADAPTER;

    $pro ['datasources']['rbac']['connection'] = $dsnRbac;
    $pro ['datasources']['rbac']['adapter'] = DB_ADAPTER;

    $pro ['datasources']['rp']['connection'] = $dsnReport;
    $pro ['datasources']['rp']['adapter'] = DB_ADAPTER;
    
    $dbHost = explode(':', DB_HOST);
    config(['database.connections.workflow.host' => $dbHost[0]]);
    config(['database.connections.workflow.database' => DB_NAME]);
    config(['database.connections.workflow.username' => DB_USER]);
    config(['database.connections.workflow.password' => DB_PASS]);
    if (count($dbHost) > 1) {
        config(['database.connections.workflow.port' => $dbHost[1]]);
    }
}

$pro ['datasources']['dbarray']['connection'] = 'dbarray://user:pass@localhost/pm_os';
$pro ['datasources']['dbarray']['adapter']    = 'dbarray';

return $pro;
