<?php
/**
 * login.php
 * {projectName}
 *
 */
if (!isset($_SESSION['G_MESSAGE']))
{
	$_SESSION['G_MESSAGE'] = '';
}
if (!isset($_SESSION['G_MESSAGE_TYPE']))
{
	$_SESSION['G_MESSAGE_TYPE'] = '';
}

$msg     = $_SESSION['G_MESSAGE'];
$msgType = $_SESSION['G_MESSAGE_TYPE'];

session_destroy();
session_start();

if (strlen($msg) > 0 )
{
	$_SESSION['G_MESSAGE'] = $msg;
}
if (strlen($msgType) > 0 )
{
	$_SESSION['G_MESSAGE_TYPE'] = $msgType;
}

$G_PUBLISH = new Publisher;
$G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/login', '', '', SYS_URI.'login/authentication.php');

G::RenderPage( "publish" );
?>
<script type="text/javascript">
    var openInfoPanel = function()
    {
      var oInfoPanel = new leimnud.module.panel();
      oInfoPanel.options = {
        size    :{ w:500,h:424 },
        position:{ x:0,y:0,center:true },
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
</script>
