<?php
/**
 * emails.php
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

require_once 'classes/model/Configuration.php';
$oConfiguration = new Configuration();
$aFields['MESS_ENABLED']             = isset($_POST['form']['MESS_ENABLED']) ? $_POST['form']['MESS_ENABLED'] : '';
$aFields['MESS_ENGINE']              = $_POST['form']['MESS_ENGINE'];
$aFields['MESS_SERVER']              = trim($_POST['form']['MESS_SERVER']);
$aFields['MESS_RAUTH']               = isset($_POST['form']['MESS_RAUTH']) ? $_POST['form']['MESS_RAUTH'] : '';
$aFields['MESS_PORT']                = $_POST['form']['MESS_PORT'];
$aFields['MESS_ACCOUNT']             = $_POST['form']['MESS_ACCOUNT'];
$aFields['MESS_PASSWORD']            = $_POST['form']['MESS_PASSWORD'];
$aFields['MESS_BACKGROUND']          = isset($_POST['form']['MESS_BACKGROUND']) ? $_POST['form']['MESS_BACKGROUND'] : '';
$aFields['MESS_EXECUTE_EVERY']       = $_POST['form']['MESS_EXECUTE_EVERY'];
$aFields['MESS_SEND_MAX']            = $_POST['form']['MESS_SEND_MAX'];
$aFields['SMTPSecure']               = $_POST['form']['SMTPSecure'];
$aFields['MESS_TRY_SEND_INMEDIATLY'] = isset($_POST['form']['MESS_TRY_SEND_INMEDIATLY']) ? $_POST['form']['MESS_TRY_SEND_INMEDIATLY'] : '';
$oConfiguration->update(array(
  'CFG_UID'   => 'Emails',
  'OBJ_UID'   => '',
  'CFG_VALUE' => serialize($aFields),
  'PRO_UID'   => '',
  'USR_UID'   => '',
  'APP_UID'   => '')
);
G::SendTemporalMessage('ID_CHANGES_SAVED', 'TMP-INFO', 'label', 4, '100%');
G::header('location: emails');
