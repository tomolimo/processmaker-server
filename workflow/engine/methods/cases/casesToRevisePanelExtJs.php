<?php
//  $oHeadPublisher =& headPublisher::getSingleton();
  $TRANSLATIONS['LABEL_GRID_LOADING']          = G::LoadTranslation('ID_CASES_LIST_GRID_LOADING');
  $TRANSLATIONS['LABEL_REFRESH']               = G::LoadTranslation('ID_REFRESH_LABEL');
  $TRANSLATIONS['MESSAGE_REFRESH']             = G::LoadTranslation('ID_REFRESH_MESSAGE');
  $TRANSLATIONS['LABEL_OPT_READ']              = G::LoadTranslation('ID_OPT_READ');
  $TRANSLATIONS['LABEL_OPT_UNREAD']            = G::LoadTranslation('ID_OPT_UNREAD');
  $TRANSLATIONS['LABEL_OPT_ALL']               = G::LoadTranslation('ID_OPT_ALL');
  $TRANSLATIONS['LABEL_OPT_STARTED']           = G::LoadTranslation('ID_OPT_STARTED');
  $TRANSLATIONS['LABEL_OPT_COMPLETED']         = G::LoadTranslation('ID_OPT_COMPLETED');
  $TRANSLATIONS['LABEL_EMPTY_PROCESSES']       = G::LoadTranslation('ID_EMPTY_PROCESSES');
  $TRANSLATIONS['LABEL_EMPTY_USERS']           = G::LoadTranslation('ID_EMPTY_USERS');
  $TRANSLATIONS['LABEL_EMPTY_SEARCH']          = G::LoadTranslation('ID_EMPTY_SEARCH');
  $TRANSLATIONS['LABEL_EMPTY_CASE']            = G::LoadTranslation('ID_EMPTY_CASE');
  $TRANSLATIONS['LABEL_SEARCH']                = G::LoadTranslation('ID_SEARCH');
  $TRANSLATIONS['LABEL_OPT_JUMP']              = G::LoadTranslation('ID_OPT_JUMP');
  $TRANSLATIONS['LABEL_DISPLAY_ITEMS']         = G::LoadTranslation('ID_DISPLAY_ITEMS');
  $TRANSLATIONS['LABEL_DISPLAY_EMPTY']         = G::LoadTranslation('ID_DISPLAY_EMPTY');
  $TRANSLATIONS['LABEL_OPEN_CASE']             = G::LoadTranslation('ID_OPEN_CASE');
  
 
  $TRANSLATIONS2 = G::getTranslations(Array(
    'ID_CASESLIST_APP_UID', 'ID_CONFIRM', 'ID_MSG_CONFIRM_DELETE_CASES', 'ID_DELETE', 'ID_REASSIGN', 
    'ID_VIEW', 'ID_UNPAUSE', 'ID_PROCESSING', 'ID_CONFIRM_UNPAUSE_CASE',
    'ID_PROCESS', 'ID_STATUS', 'ID_USER', 'ID_DELEGATE_DATE_FROM', 'ID_TO', 'ID_FILTER_BY_DELEGATED_DATE',
    'ID_TO_DO', 'ID_DRAFT', 'ID_COMPLETED', 'ID_CANCELLED', 'ID_PAUSED',
    'ID_PRO_DESCRIPTION', 'ID_PRO_TITLE', 'ID_CATEGORY', 'ID_STATUS', 'ID_PRO_USER', 'ID_PRO_CREATE_DATE', 'ID_PRO_DEBUG', 'ID_INBOX', 'ID_DRAFT',
    'ID_COMPLETED', 'ID_CANCELLED', 'ID_TOTAL_CASES', 'ID_ENTER_SEARCH_TERM', 'ID_ACTIVATE', 'ID_DEACTIVATE',
    'ID_SELECT', 'ID_SEARCH', 'ID_NO_SELECTION_WARNING', 'ID_SELECT_ONE_AT_LEAST', 'ID_MSG_CONFIRM_DELETE_CASES2',
    'ID_PAUSE_CASE_TO_DATE', 'ID_DELETING_ELEMENTS', 'ID_MSG_CONFIRM_CANCEL_CASE', 'ID_MSG_CONFIRM_CANCEL_CASES',
    'ID_OPEN_CASE', 'ID_PAUSE_CASE', 'ID_REASSIGN', 'ID_DELETE', 'ID_CANCEL', 'ID_UNPAUSE_CASE','ID_MSG_CONFIRM_DELETE_CASE'
  ));

//  $TRANSLATIONS = array_merge($TRANSLATIONS, $TRANSLATIONS2);
  $delIndex = $_GET['DEL_INDEX'];
  $appUid   = $_GET['APP_UID'];
//  $oHeadPublisher->assign( 'TRANSLATIONS',   $TRANSLATIONS); //translations
  $casesPanelUrl = 'casesToReviseTreeContent?APP_UID='.$appUid.'&DEL_INDEX='.$delIndex;
  $oHeadPublisher->assign( 'casesPanelUrl',   $casesPanelUrl); //translations
  $oHeadPublisher->addExtJsScript('cases/casesToRevisePanel', false );    //adding a javascript file .js
  $oHeadPublisher->addContent( 'cases/casesToRevisePanel'); //adding a html file  .html.

  G::RenderPage('publish', 'extJs');
 