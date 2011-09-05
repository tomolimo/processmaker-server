<html>
  <head>
  <title>{$username}</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="shortcut icon" href="/images/favicon.ico"   type="image/x-icon"/>
  <link rel="stylesheet" type="text/css" href="/skins/green/style.css"/>
  {$header}
  <script type="text/javascript">{literal}
    var openInfoPanel = function()
    {
      var oInfoPanel = new leimnud.module.panel();
      oInfoPanel.options = {
        size    :{w:500,h:424},
        position:{x:0,y:0,center:true},
        title   :'System Information',
        theme   :'processmaker',
        control :{
          close :true,
          drag  :false
        },
        fx:{
          modal:true
        }
      };
      oInfoPanel.setStyle = {modal: {
        backgroundColor: 'white'
      }};
      oInfoPanel.make();
      var oRPC = new leimnud.module.rpc.xmlhttp({
        url   : '../login/dbInfo',
        async : false,
        method: 'POST',
        args  : ''
      });
      oRPC.make();
      oInfoPanel.addContent(oRPC.xmlhttp.responseText);
    };
  {/literal}</script>
</head>
<body>
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td id="pm_header">

      <table width="100%" height="32" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
        <tr>
          <td width="50%" rowspan="2" valign="top"><img src="/js/common/core/images/default_logo.gif"></td>
          <td width="50%" height="16" align="right" valign="top">
            <div align="right" class="logout"><small>{php}if ((int)$_SESSION['USER_LOGGED'] != 0) {{/php}<a href="../login/login">{php}echo G::LoadTranslation('ID_LOGOUT');{/php}</a>{php}}{/php}</small> &nbsp; &nbsp;</div>
          </td>
        </tr>
        <tr>
          <td width="50%" height="16" valign="bottom" class="title">
            <div align="right"><?php //aca iba el título ?></div>
          </td>
        </tr>
</table>
    </td>
  </tr>
  <tr>
    <td>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" >
          <tr>
            <td width="100%" align="left" class="mainMenu" id="pm_menu">
              <table width="70%" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td class="mainMenu">&nbsp;<td>
                  {include file= "$tpl_menu"}
                 </tr>
               </table>
            </td>
        </tr>
          <tr>
            {php}
            global $G_TMP_MENU_ALIGN;
            {/php}
            <td width="100%" align="{php}($G_TMP_MENU_ALIGN==''?'center':$G_TMP_MENU_ALIGN){/php}" class="subMenu"  id="pm_submenu">
              <table width="50%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td>&nbsp;&nbsp;&nbsp;<td>
                      {include file= "$tpl_submenu"}
                    </tr>
              </table>
      </td>
    </tr>
    <tr>
      <td id="pm_separator" class="pm_separator">
      </td>
    </tr>
    <tr>
      <td width="100%" align="center">

          {php}
            global $G_TEMPLATE;
            if ($G_TEMPLATE != '')
            {
              G::LoadTemplate($G_TEMPLATE);
            }
      {/php}
        </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr height="100%">
    <td height="100%" valign="bottom">
      <div class="Footer">
        <div class="image"></div>
        <div class="content">
        {php}if (strpos($_SERVER['REQUEST_URI'], '/login/login') !== false) {{/php}
          {php}if ( defined('SYS_SYS') ) {{/php}
            <a href="#" onclick="openInfoPanel();return false;" class="FooterLink">| System Information |</a><br />
          {php}}{/php}
          <br />Copyright © 2003-2008 Colosa, Inc. All rights reserved.
        {php}}{/php}
        </div>
    </div>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
