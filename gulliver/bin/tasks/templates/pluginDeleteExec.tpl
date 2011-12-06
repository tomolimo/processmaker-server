<?php 
  try {
    $form = $_POST['form'];
 <!-- START BLOCK : keys -->
    ${phpName} = $form['{name}'];
<!-- END BLOCK : keys -->

<!-- START BLOCK : plugin -->
    require_once (PATH_PLUGINS . '{pluginName}' . PATH_SEP . 'class.{pluginName}.php');
    $pluginObj = new {pluginName}Class ();
<!-- END BLOCK : plugin -->

    require_once ("classes/model/{className}.php");
 
    //if exists the row in the database propel will update it, otherwise will insert.
    $tr = {className}Peer::retrieveByPK({keylist});
    if ((is_object($tr) && get_class($tr) == '{className}')) {
      $tr->delete();
    }

    G::Header('location: {phpClassName}List');
  }
  catch (Exception $e) {
    $G_PUBLISH = new Publisher;
    $aMessage['MESSAGE'] = $e->getMessage();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage);
    G::RenderPage('publish', 'blank');
  }
  