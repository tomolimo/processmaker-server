<?php
/**
 * main.php Cases List main processor
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

  $oHeadPublisher =& headPublisher::getSingleton(); 
  
  $oHeadPublisher->addExtJsScript('processes/main', false );    //adding a javascript file .js
  $oHeadPublisher->addContent('processes/main'); //adding a html file  .html.
  
  $translations = G::getTranslations(Array(
    'ID_NEW', 'ID_EDIT', 'ID_STATUS', 'ID_DELETE', 'ID_IMPORT', 'ID_BROWSE_LIBRARY', 'ID_CATEGORY', 'ID_SELECT',
    'ID_PRO_DESCRIPTION', 'ID_PRO_TITLE', 'ID_CATEGORY', 'ID_STATUS', 'ID_PRO_USER', 'ID_PRO_CREATE_DATE', 'ID_PRO_DEBUG', 'ID_INBOX', 'ID_DRAFT',
    'ID_COMPLETED', 'ID_CANCELLED', 'ID_TOTAL_CASES', 'ID_ENTER_SEARCH_TERM', 'ID_ACTIVATE', 'ID_DEACTIVATE',
    'ID_SELECT', 'ID_SEARCH', 'ID_NO_SELECTION_WARNING', 'ID_PROCESS_DELETE_LABEL', 'ID_PROCESS_DELETE_ALL_LABEL',
    'ID_PROCESS_CANT_DELETE'
  ));
  $oHeadPublisher->assign('TRANSLATIONS', $translations);
  G::RenderPage('publish', 'extJs');
  
  
  
  
  
