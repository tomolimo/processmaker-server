<?php
/**
 * pmTables controller
 * @author Douglas Medrano <douglas@colosa.com, douglas.medrano@live.com>
 * @New Calendar
 * @access public
 */
class Admin extends Controller
{
  /**
   * getting default list
   * @param string $httpData->PRO_UID (opional)
   */
  public function calendarEdit($httpData)
  {
    global $RBAC;
    
    $CalendarUid = str_replace ( '"', '', isset ( $_GET ['id'] ) ? $_GET ['id'] : G::GenerateUniqueID () );
    G::LoadClass ( 'calendar' );
    $calendarObj = new calendar ( );
    
    if ((isset ( $_GET ['id'] )) && ($_GET ['id'] != "")) {
      $fields = $calendarObj->getCalendarInfoE ( $CalendarUid );
      $fields ['OLD_NAME'] = $fields['CALENDAR_NAME'];
    }
    if (! (isset ( $fields ['CALENDAR_UID'] ))) { //For a new Calendar
      $fields ['CALENDAR_UID'] = $CalendarUid;
      $fields ['OLD_NAME'] = '';
      
      //Default Business Hour
      $fields ['BUSINESS_DAY'] [1] ['CALENDAR_BUSINESS_DAY'] = 7;
      $fields ['BUSINESS_DAY'] [1] ['CALENDAR_BUSINESS_START'] = "09:00";
      $fields ['BUSINESS_DAY'] [1] ['CALENDAR_BUSINESS_END'] = "17:00";
    }
    if ((isset ( $_GET ['cp'] )) && ($_GET ['cp'] == 1)) { // Copy Calendar
      $fields ['CALENDAR_UID'] = G::GenerateUniqueID ();
      $fields ['CALENDAR_NAME'] = G::LoadTranslation ( "ID_COPY_OF" ) . " " . $fields ['CALENDAR_NAME'];
    }
    
    $RBAC->requirePermissions('PM_SETUP_ADVANCE');    
    G::LoadClass('configuration');
    $c = new Configurations();
    $configPage = $c->getConfiguration('additionalTablesList', 'pageSize','',$_SESSION['USER_LOGGED']);
    $Config['pageSize'] = isset($configPage['pageSize']) ? $configPage['pageSize'] : 20;
    
    $this->includeExtJS('admin/calendarEdit');
    $this->setView('admin/calendarEdit');
    
    $variableArray = array();
    $variableArray[0] = 'uno';
    $variableArray[1] = 'dos';
    

    $businessDayArray = array();
      for($i=0;$i<sizeof($fields['BUSINESS_DAY']);$i++) {
        $businessDayArray[$i] =  $fields['BUSINESS_DAY'][$i+1];
        
      }
    $fields['BUSINESS_DAY'] = $businessDayArray;   
    //validating if the calendar is new, it means that we don't have the $_GET array
    $fields['HOLIDAY']=(isset ( $_GET['id'] )&&$_GET['id']!='')?$fields['HOLIDAY']:array();
    $holidayArray     = array();
    for($i=0;$i<sizeof($fields['HOLIDAY']);$i++) {
      $holidayArray[$i] =  $fields['HOLIDAY'][$i+1];
      
    }

    $_GET ['id']= (isset ( $_GET['id'] )&&$_GET['id']!='')?$_GET['id']:'';
    $fields['HOLIDAY'] = $holidayArray;
    $fields['NEWCALENDAR'] = 'NO';
    if(isset ( $_GET['id'] )&&$_GET['id']=='') {
       $fields['CALENDAR_UID'] =  G::GenerateUniqueID();
       $fields['NEWCALENDAR'] = 'YES';
    }
    $this->setJSVar('CALENDAR_UID',$fields['CALENDAR_UID']);
    $this->setJSVar('fields',$fields);
    
    G::RenderPage('publish', 'extJs');
  }
}
