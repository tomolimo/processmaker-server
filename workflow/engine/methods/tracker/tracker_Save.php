<?
unset($_POST['form']['SAVE']);
if (!isset($_POST['form']['CT_DERIVATION_HISTORY'])) {
  $_POST['form']['CT_DERIVATION_HISTORY'] = 0;
}
if (!isset($_POST['form']['CT_MESSAGE_HISTORY'])) {
  $_POST['form']['CT_MESSAGE_HISTORY'] = 0;
}
require_once 'classes/model/CaseTracker.php';
$oCaseTracker = new CaseTracker();
$oCaseTracker->update($_POST['form']);