<?php 
  try {
    $form = $_POST['form'];
<!-- START BLOCK : fields2 -->
    ${phpName} = $form['{name}'];
<!-- END BLOCK : fields2 --> 

<!-- START BLOCK : plugin -->
    require_once (PATH_PLUGINS . '{pluginName}' . PATH_SEP . 'class.{pluginName}.php');
    $pluginObj = new {pluginName}Class();
<!-- END BLOCK : plugin -->

    require_once ("classes/model/{className}.php");

    //if exists the row in the database propel will update it, otherwise will insert.
    $tr = {className}Peer::retrieveByPK( {keylist} );
    if (!(is_object($tr) && get_class($tr) == '{className}')) {
      $tr = new {className}();
    }
<!-- START BLOCK : fields -->
    $tr->set{phpName}( ${phpName} );
<!-- END BLOCK : fields -->

    if ($tr->validate()) {
      //we save it, since we get no validation errors, or do whatever else you like.
      $res = $tr->save();
    }
    else {
      //Something went wrong. We can now get the validationFailures and handle them.
      $msg = '';
      $validationFailuresArray = $tr->getValidationFailures();
      foreach($validationFailuresArray as $objValidationFailure) {
        $msg .= $objValidationFailure->getMessage() . "<br/>";
      }
      //return array('codError' => -100, 'rowsAffected' => 0, 'message' => $msg);
    }
    //return array('codError' => 0, 'rowsAffected' => $res, 'message' => '');

    //to do: uniform  coderror structures for all classes
  
    //if ($res['codError'] < 0) {
    //  G::SendMessageText($res['message'] , 'error');
    //}
    G::Header('location: {phpClassName}List');
  }
  catch (Exception $e) {
    $G_PUBLISH = new Publisher;
    $aMessage['MESSAGE'] = $e->getMessage();
    $G_PUBLISH->AddContent('xmlform', 'xmlform', 'login/showMessage', '', $aMessage);
    G::RenderPage('publish', 'blank');
  }
  