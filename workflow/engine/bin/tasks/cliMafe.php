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
G::LoadClass("system");
G::LoadClass("wsTools");


CLI::taskName('mafe-translation');
CLI::taskDescription(<<<EOT
    Creates labels of mafe for the database 

EOT
);

CLI::taskArg('workspace', true, true);
CLI::taskOpt("lang", "languaje", "l:","lang=");
CLI::taskRun('run_create_translation');

function run_create_translation($args, $opts)
{
    $rootDir = realpath(__DIR__."/../../../../");
    $app = new Maveriks\WebApplication();
    $app->setRootDir($rootDir);
    $loadConstants = false;

    $workspaces = get_workspaces_from_args($args);
    $lang = array_key_exists("lang", $opts) ? $opts['lang'] : 'en';

    $translation = new Translation();
    CLI::logging("Updating labels Mafe ...\n");
    foreach ($workspaces as $workspace) {
        try {
            echo "Updating labels for workspace " . pakeColor::colorize($workspace->name, "INFO") . "\n";
            $app->loadEnvironment($workspace->name, $loadConstants);
            $translation->generateTransaltionMafe($lang);
        } catch (Exception $e) {
            echo "Errors upgrading labels for workspace " . CLI::info($workspace->name) . ": " . CLI::error($e->getMessage()) . "\n";
        }
    }

    CLI::logging("Create successful\n");

}
