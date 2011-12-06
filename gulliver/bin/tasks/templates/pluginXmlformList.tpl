<?xml version="1.0" encoding="UTF-8"?>
<dynaForm name="{className}" width="100%" type="pagedtable"
  sql="SELECT * from {tableName} "
  sqlConnection=""
  menu="{phpFolderName}/{phpClassName}Options"
>
<!-- START BLOCK : onlyFields -->
<{name} type="text" colWidth='{size}'>
  <en>{label}</en>
</{name}>

<!-- END BLOCK : onlyFields -->

<LINK type="link" colWidth="60" titleAlign="left" align="left" link='{phpClassName}Edit?id={primaryKey}'>
  <en>Edit</en>
</LINK>

<LINK2 type="link" colWidth="60" titleAlign="left" align="left" link='{phpClassName}Delete?id={primaryKey}'>
  <en>Delete</en>
</LINK2 >
</dynaForm>