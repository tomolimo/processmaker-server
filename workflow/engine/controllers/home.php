<?php
/**
 * Home controller
 *
 * @author Erik Amaru Ortiz <erik@colosa.com, aortiz.erik@gmail.com>
 * @inherits Controller
 * @access public
 */

class Home extends Controller
{
  private $userID;
  private $userName;
  private $userFullName;
  private $userRolName;
  private $userUxType;

  private $appListStart = 0;
  private $appListLimit = 15;

  public function __construct()
  {
    // setting as using simplified.
    $_SESSION['current_ux'] = 'SIMPLIFIED';

    // getting the ux type from user o group conf.
    $this->userUxType = isset($_SESSION['user_experience'])? $_SESSION['user_experience']: $_SESSION['current_ux'];

    if (isset($_SESSION['USER_LOGGED']) && !empty($_SESSION['USER_LOGGED'])) {
      $this->userID       = isset($_SESSION['USER_LOGGED']) ? $_SESSION['USER_LOGGED'] : null;
      $this->userName     = isset($_SESSION['USR_USERNAME']) ? $_SESSION['USR_USERNAME'] : '';
      $this->userFullName = isset($_SESSION['USR_FULLNAME']) ? $_SESSION['USR_FULLNAME'] : '';
      $this->userRolName  = isset($_SESSION['USR_ROLENAME']) ? $_SESSION['USR_ROLENAME'] : '';
    }
    //g::pr($_SESSION); die;
  }

  /**
   * getting default list
   * @param string $httpData (opional)
   */

  public function index($httpData)
  {
    G::LoadClass('process');
    G::LoadClass('case');

    $process = new Process();
    $case    = new Cases();

    //Get ProcessStatistics Info
    $start = 0;
    $limit = '';

    $proData = $process->getAllProcesses($start, $limit);
    $processList = $case->getStartCasesPerType ( $_SESSION ['USER_LOGGED'], 'category' );
    unset($processList[0]);

    $this->setView('home/index');
    $this->setVar('usrUid', $this->userID);
    $this->setVar('userName', $this->userName);
    $this->setVar('processList', $processList);
    $this->setVar('canStartCase', $case->canStartCase($_SESSION ['USER_LOGGED']));
    $this->setVar('userUxType', $this->userUxType);

    G::RenderPage('publish', 'mvc');
  }

  public function appList($httpData)
  {
    require_once ( "classes/model/AppNotes.php" );
    $appNotes = new AppNotes();

    $httpData->t = isset($httpData->t)? $httpData->t : 'in';
    $title = $httpData->t != 'draft' ? 'My Inbox' : 'My Drafts';
    $action = $httpData->t != 'draft' ? 'todo' : 'draft';

    $notesStart = 0;
    $notesLimit = 4;

    //$cases = self::getAppList($httpData->t, $this->appListStart, $this->appListLimit);

    G::LoadClass('applications');
    $apps = new Applications();
    $cases = $apps->getAll($this->appListStart, $this->appListLimit, $action);
    
    foreach ($cases['data'] as $i => $row) {
      $notes = $appNotes->getNotesList($row['APP_UID'], $this->userID, $notesStart, $notesLimit);
      $notes = $notes['array'];
      
      $cases['data'][$i]['NOTES_COUNT'] = $notes['totalCount'];
      $cases['data'][$i]['NOTES_LIST']  = $notes['notes'];
    }

    // settings html template
    $this->setView('home/appList');

    // settings vars and rendering
    $this->setVar('cases', $cases['data']);
    $this->setVar('cases_count', $cases['totalCount']);
    $this->setVar('title', $title);
    $this->setVar('appListStart', $this->appListLimit);
    $this->setVar('appListLimit', 10);
    $this->setVar('listType', $httpData->t);

    G::RenderPage('publish', 'mvc');
  }

  public function getApps($httpData)
  {
    G::LoadClass('applications');
    $apps = new Applications();

    require_once ( "classes/model/AppNotes.php" );
    $appNotes = new AppNotes();

    $notesStart = 0;
    $notesLimit = 4;

    $action = $httpData->t != 'draft' ? 'todo' : 'draft';

    $cases = $apps->getAll($httpData->start, $httpData->limit, $action);

    foreach ($cases['data'] as $i => $row) {
      $notes = $appNotes->getNotesList($row['APP_UID'], $this->userID, $notesStart, $notesLimit);
      $notes = $notes['array'];
      
      $cases['data'][$i]['NOTES_COUNT'] = $notes['totalCount'];
      $cases['data'][$i]['NOTES_LIST']  = $notes['notes'];
    }

    $this->setView('home/applications');

    // settings vars and rendering
    $this->setVar('cases', $cases['data']);

    G::RenderPage('publish', 'mvc');
  }

  public function startCase($httpData)
  {
    G::LoadClass('case');
    $case  = new Cases();
    $aData = $case->startCase($httpData->id, $_SESSION['USER_LOGGED']);
    
    $_SESSION['APPLICATION']   = $aData['APPLICATION'];
    $_SESSION['INDEX']         = $aData['INDEX'];
    $_SESSION['PROCESS']       = $aData['PROCESS'];
    $_SESSION['TASK']          = $httpData->id;
    $_SESSION['STEP_POSITION'] = 0;
    $_SESSION['CASES_REFRESH'] = true;
        
    $oCase = new Cases();
    $aNextStep = $oCase->getNextStep($_SESSION['PROCESS'], $_SESSION['APPLICATION'], $_SESSION['INDEX'], $_SESSION['STEP_POSITION']);
    //../cases/cases_Open?APP_UID={$APP.APP_UID}&DEL_INDEX={$APP.DEL_INDEX}&action=todo
    $aNextStep['PAGE'] = '../cases/cases_Open?APP_UID='.$aData['APPLICATION'].'&DEL_INDEX='.$aData['INDEX'].'&action=draft';
    $_SESSION ['BREAKSTEP'] ['NEXT_STEP'] = $aNextStep;

    G::header('Location: ' . $aNextStep['PAGE']);
  }

  private function getAppList($type, $start=null, $limit=null)
  {
    require_once ( "classes/model/AppCacheView.php" );
    require_once ( "classes/model/Application.php" );
    require_once ( "classes/model/AppNotes.php" );

    $appCache = new AppCacheView();
    $appNotes = new AppNotes();

    $notesStart = 0;
    $notesLimit = 4;

    // getting user's cases on inbox
    switch ($type) {
      case 'inbox': case 'in':
        $criteria = $appCache->getToDoListCriteria($this->userID);
        break;
      
      case 'draft':
      default:
        $criteria = $appCache->getDraftListCriteria($this->userID); //fast enough    
        break;
    } 

    $distinct = $type == 'draft' ? true : false;
    $criteriac = $appCache->getToDoCountCriteria($this->userID);
    $totalCount = AppCacheViewPeer::doCount( $criteriac, $distinct );

    if (isset($start))
      $criteria->setOffset($start);
    
    if (isset($limit))
      $criteria->setLimit($limit);

    $criteria->addDescendingOrderByColumn(AppCacheViewPeer::APP_NUMBER);
    $dataset = AppCacheViewPeer::doSelectRS($criteria);
    $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      
    $cases = array();
    //$data['totalCount'] = $totalCount;
    $rows = array();
    $priorities = array('1'=>'VL', '2'=>'L', '3'=>'N', '4'=>'H', '5'=>'VH');
    $index = $start;
    
    while ($dataset->next()) {
      $row = $dataset->getRow();
      if (is_numeric(str_replace('#', '', $row['APP_TITLE']))) {
        $row['APP_TITLE'] = 'Case ' . str_replace('#', '', $row['APP_TITLE']);
      }
      // replacing the status data with their respective translation 
      if (isset($row['APP_STATUS'])) {
        $row['APP_STATUS'] = G::LoadTranslation("ID_{$row['APP_STATUS']}");
      }
      // replacing the priority data with their respective translation
      if (isset($row['DEL_PRIORITY'])) {
        $row['DEL_PRIORITY'] = G::LoadTranslation("ID_PRIORITY_{$priorities[$row['DEL_PRIORITY']]}");
      }

      $row['DEL_DELEGATE_DATE'] = G::getformatedDate($row['DEL_DELEGATE_DATE'], 'M d, yyyy at h:i:s');

      
      $notes = $appNotes->getNotesList($row['APP_UID'], $this->userID, $notesStart, $notesLimit);
      $notes = $notes['array'];
      
      $row['NOTES_COUNT'] = $notes['totalCount'];
      $row['NOTES_LIST']  = $notes['notes'];

      $cases[] = $row;
    }

    return array('data' => $cases, 'totalCount' => $totalCount);
  }
}