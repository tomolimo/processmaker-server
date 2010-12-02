<?xml version="1.0" encoding="UTF-8"?>
<dynaForm
  width         = "100%"
>
<uid type="text" colWidth="50" titleAlign="left" align="left" dataCompareField="APP_NUMBER" dataCompareType="=" >
  <en>#</en>
</uid>
<name type="text" value="@#APP_TITLE" link="@G::encryptlink(@#cases_Open)?APP_UID=@#APP_UID&amp;DEL_INDEX=@#DEL_INDEX" colWidth="180" titleAlign="left" align="left" dataCompareField="APP_TITLE.CON_VALUE" dataCompareType="contains" >

  <en>name</en>
</name>
<age type="text" value="@#APP_TAS_TITLE" link="@G::encryptlink(@#cases_Open)?APP_UID=@#APP_UID&amp;DEL_INDEX=@#DEL_INDEX" colWidth="180" titleAlign="left" align="left" dataCompareField="TAS_TITLE.CON_VALUE" dataCompareType="contains" >

  <en>Task</en>
</age>
<balance type="text" colWidth="180" titleAlign="left" align="left" dataCompareField="PRO_TITLE.CON_VALUE" dataCompareType="contains" >

  <en>balance</en>
</balance>

</dynaForm>
