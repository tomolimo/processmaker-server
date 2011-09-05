<?xml version="1.0" encoding="UTF-8"?>
<dynaForm

  name          = "cases_List"
  type          = "filterform"
  sqlConnection = ""
  menu          = "cases/cases_Options"
  filterForm    = "cases/cases_List"
  searchBy      = "APPLICATION.APP_NUMBER | APP_TITLE.CON_VALUE | TAS_TITLE.CON_VALUE | PRO_TITLE.CON_VALUE "
  width         = "100%"
>
<cases_Open type="private" defaultValue="cases_Open" showInTable="0"/>
<APP_UID type="private" showInTable="0"/>
<DEL_INDEX type="private" showInTable="0"/>
<MARK type="cellMark" className="cellSelected1" classNameAlt="cellSelected2" condition="'@#DEL_INIT_DATE'==''"/>

<APP_NUMBER type="text" colWidth="50" titleAlign="left" align="left" dataCompareField="APP_NUMBER" dataCompareType="=" >
  <en>#</en>
</APP_NUMBER>

<APP_TITLE type="text" value="@#APP_TITLE" link="@G::encryptlink(@#cases_Open)?APP_UID=@#APP_UID&amp;DEL_INDEX=@#DEL_INDEX" colWidth="100" titleAlign="left" align="left" dataCompareField="APP_TITLE.CON_VALUE" dataCompareType="contains" >
  <en>Case</en>
</APP_TITLE>

<APP_TAS_TITLE type="text" value="@#APP_TAS_TITLE" link="@G::encryptlink(@#cases_Open)?APP_UID=@#APP_UID&amp;DEL_INDEX=@#DEL_INDEX" colWidth="130" titleAlign="left" align="left" dataCompareField="TAS_TITLE.CON_VALUE" dataCompareType="contains" >
  <en>Task</en>
</APP_TAS_TITLE>

<APP_PRO_TITLE type="text" colWidth="180" titleAlign="left" align="left" dataCompareField="PRO_TITLE.CON_VALUE" dataCompareType="contains" >
  <en>Process</en>
</APP_PRO_TITLE>

<APP_DEL_PREVIOUS_USER type="text" colWidth="120" titleAlign="left" align="left" dataCompareField="APP_LAST_USER.USR_USERNAME" dataCompareType="contains" >
  <en>Sent by</en>
</APP_DEL_PREVIOUS_USER>

<DEL_TASK_DUE_DATE type="date" rows="3" cols="32" colWidth="120" titleAlign="left" align="left" dataCompareField="APP_DELEGATION.DEL_TASK_DUE_DATE" dataCompareType="contains" >
  <en>Due Date</en>
</DEL_TASK_DUE_DATE>

<APP_UPDATE_DATE type="date" rows="3" cols="32" colWidth="120" titleAlign="left" align="left" dataCompareField="APPLICATION.APP_UPDATE_DATE" dataCompareType="contains" >
  <en>Last Modification</en>
</APP_UPDATE_DATE>

<DEL_PRIORITY type="dropdown" value="" link="" colWidth="180" titleAlign="left" align="left" dataCompareField="APP_DELEGATION.DEL_PRIORITY" dataCompareType="contains">
  <en>Priority  
      <option name="1">Very High</option>
      <option name="2">High</option>
      <option name="3">Normal</option>
      <option name="4">Low</option>
      <option name="5">Very Low</option>
  </en>
</DEL_PRIORITY>

<DEL_INIT_DATE type="date" rows="3" cols="32" colWidth="90" titleAlign="left" align="left" dataCompareField="APPLICATION.APP_UPDATE_DATE" dataCompareType="contains" showInTable="0">
  <en>Start Date</en>
</DEL_INIT_DATE>

<APP_STATUS type="private" showInTable="0"/>

<MARK2 type="cellMark" className="RowLink1" classNameAlt="RowLink2" defaultValue="1"/>
<!--<EDIT type="link" colWidth="40" value="@G::LoadTranslation(ID_EDIT)" link="#" onclick="casesEdit(@QAPP_UID);return false;"/>
-->

<OPEN type="link" value="@G::LoadTranslation(ID_OPEN)" link="@G::encryptlink(@#cases_Open)?APP_UID=@#APP_UID&amp;DEL_INDEX=@#DEL_INDEX" colWidth="40" titleAlign="left" align="left" dataCompareField="APP_TITLE.CON_VALUE" dataCompareType="contains" >
  <en></en>
</OPEN>



<!--<CANCEL type="link" colWidth="40" value="@G::LoadTranslation(ID_CANCEL)" link="#" onclick="cancelCase(@QAPP_UID, @QDEL_INDEX);return false;"/>-->

<!-- FILTER FORM -->
<SEARCH type="button" onclick="pagedTableFilter( this.form );" showInTable="0">
  <en>Apply Filter</en>
</SEARCH>

<PAGED_TABLE_ID type="private" showInTable="0"/>

<JSFILTER type="javascript" replaceTags="1" showInTable="0">
function pagedTableFilter( form ) {
  @#PAGED_TABLE_ID.doFilter( form );
}
</JSFILTER>
</dynaForm>
