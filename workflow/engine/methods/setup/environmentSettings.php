<?php 
  G::loadClass('configuration');
  $oConf = new Configurations;
  $oConf->loadConfig($obj, 'ENVIRONMENT_SETTINGS','');

  //translations array
  $translations = G::getTranslations(Array(
    'ID_PM_ENV_SETTINGS_TITLE', 'ID_PM_ENV_SETTINGS_USERFIELDSET_TITLE', 'IS_USER_NAME_DISPLAY_FORMAT', 'ID_SAVE_SETTINGS',
    'ID_LAN_UPDATE_DATE', 'ID_SAVING_ENVIRONMENT_SETTINGS', 'ID_ENVIRONMENT_SETTINGS_MSG_1',
    'ID_PM_ENV_SETTINGS_REGIONFIELDSET_TITLE', 'ID_GLOBAL_DATE_FORMAT', 'ID_PM_ENV_SETTINGS_CASESLIST_TITLE', 'ID_CASES_ROW_NUMBER', 'ID_CASES_DATE_MASK'
  ));
  $defaultOption = isset($oConf->aConfig['format'])? $oConf->aConfig['format']: '';
  $defaultDateOption = isset($oConf->aConfig['dateFormat'])? $oConf->aConfig['dateFormat']: '';
  $defaultCasesListDateOption = isset($oConf->aConfig['casesListDateFormat'])? $oConf->aConfig['casesListDateFormat']: '';
  $defaultCasesListRowOption  = isset($oConf->aConfig['casesListRowNumber']) ? $oConf->aConfig['casesListRowNumber'] : '';
  
  $oHeadPublisher =& headPublisher::getSingleton();
  $oHeadPublisher->addExtJsScript('setup/environmentSettings', true);

  $oHeadPublisher->assign('default_format', $defaultOption);
  $oHeadPublisher->assign('default_date_format', $defaultDateOption);
  $oHeadPublisher->assign('default_caseslist_date_format', $defaultCasesListDateOption);
  $oHeadPublisher->assign('default_caseslist_row_number', $defaultCasesListRowOption);
  
  
  
  $oHeadPublisher->assign('dateFormatsList', Configurations::getDateFormats());

  $oHeadPublisher->assign('TRANSLATIONS', $translations);
  G::RenderPage('publish', 'extJs');