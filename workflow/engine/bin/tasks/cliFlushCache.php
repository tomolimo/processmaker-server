<?php
/**
 * cliMafe.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2011 Colosa Inc.
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
 * @package workflow-engine-bin-tasks
 */
G::LoadSystem("g");

CLI::taskName('flush-cache');
CLI::taskDescription(<<<EOT
    Flush cache of all workspaces of a given workspace
EOT
);

CLI::taskArg('workspace', true, true);
CLI::taskRun('run_flush_cache');

function run_flush_cache($args, $opts)
{
    $rootDir = realpath(__DIR__."/../../../../");
    $app = new Maveriks\WebApplication();
    $app->setRootDir($rootDir);
    $loadConstants = false;
    $workspaces = get_workspaces_from_args($args);

    if (! defined("PATH_C")) {
        die("ERROR: seems processmaker is not properly installed (System constants are missing).".PHP_EOL);
    }

    CLI::logging("Flush ".pakeColor::colorize("system", "INFO")." cache ... ");
    G::rm_dir(PATH_C);
    G::mk_dir(PATH_C, 0777);
    echo "DONE" . PHP_EOL;

    foreach ($workspaces as $workspace) {
        echo "Flush workspace " . pakeColor::colorize($workspace->name, "INFO") . " cache ... ";

        G::rm_dir($workspace->path . "/cache");
        G::mk_dir($workspace->path . "/cache", 0777);
        G::rm_dir($workspace->path . "/cachefiles");
        G::mk_dir($workspace->path . "/cachefiles", 0777);
        echo "DONE" . PHP_EOL;
    }
}
