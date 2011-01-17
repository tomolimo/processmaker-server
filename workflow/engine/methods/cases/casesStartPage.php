<?php
  unset($_SESSION['__currentTabDashboard']);
  if(isset($_GET['t'])){
	  $_SESSION['__currentTabDashboard']=$_GET['t'];
  }
  
  $labels = G::getTranslations(Array(
      'ID_FIND_A_PROCESS',
      'ID_PROCESS_INFORMATION', 'ID_PROCESS', 'ID_TASK', 'ID_DESCRIPTION', 'ID_CATEGORY',
      'ID_GENERAL_PROCESS_NUMBERS', 'ID_INBOX', 'ID_DRAFT', 'ID_COMPLETED', 'ID_CANCELLED', 'ID_TOTAL_CASES',
      'ID_CALENDAR', 'ID_CALENDAR_DESCRIPTION', 'ID_WORKING_DAYS', 'ID_DEBUG_MODE',
      'ID_SUN', 'ID_MON', 'ID_TUE', 'ID_WEN', 'ID_THU', 'ID_FRI', 'ID_SAT',
      'ID_TITLE_START_CASE', 'ID_STARTING_NEW_CASE', 'ID_ERROR_CREATING_NEW_CASE',
      'ID_ERROR', 'ID_UNABLE_START_CASE'
      ));
  $oHeadPublisher->assign('TRANSLATIONS', $labels);

  $oHeadPublisher =& headPublisher::getSingleton();
  $oHeadPublisher->addExtJsScript('cases/casesStartPage', false);    //adding a javascript file .js
  $oHeadPublisher->addContent( 'cases/casesStartPage'); //adding a html file  .html.

  G::RenderPage('publish', 'extJs');