<?php 
  G::loadClass('configuration');
  $oConf = new Configurations;
  $oConf->loadConfig($obj, 'ENVIRONMENT_SETTINGS','');

  //translations array
  $translations = G::getTranslations(Array(
    'ID_PM_ENV_SETTINGS_TITLE', 'ID_PM_ENV_SETTINGS_USERFIELDSET_TITLE', 'IS_USER_NAME_DISPLAY_FORMAT', 'ID_SAVE_SETTINGS',
    'ID_LAN_UPDATE_DATE', 'ID_SAVING_ENVIRONMENT_SETTINGS', 'ID_ENVIRONMENT_SETTINGS_MSG_1'
  ));
  $defaultOption = isset($oConf->aConfig['format'])? $oConf->aConfig['format']: '';
  
  $oHeadPublisher =& headPublisher::getSingleton();
  $oHeadPublisher->addExtJsScript('setup/environmentSettings', true);
  $oHeadPublisher->assign('default_format', $defaultOption);
  $oHeadPublisher->assign('TRANSLATIONS', $translations);
  G::RenderPage('publish', 'extJs');