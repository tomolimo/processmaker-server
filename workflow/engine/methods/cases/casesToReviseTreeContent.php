<?php

class TreeNode {

    public $text = "";
    public $id = "";
    public $iconCls = "";
    public $leaf = true;
    public $draggable = false;
    public $href = "#";
    public $hrefTarget = "";

    function  __construct($id,$text,$iconCls,$leaf,$draggable,$href,$hrefTarget) {

        $this->id = $id;
        $this->text = $text;
        $this->iconCls = $iconCls;
        $this->leaf = $leaf;
        $this->draggable = $draggable;
        $this->href = $href;
        $this->hrefTarget = $hrefTarget;
    }
}

class TreeNodes {

    protected $nodes = array();

    function add($id,$text,$iconCls,$leaf,$draggable, $href,$hrefTarget) {

        $n = new TreeNode($id,$text,$iconCls,$leaf, $draggable,$href,$hrefTarget);

        $this->nodes[] = $n;
    }

    function toJson() {
        return json_encode($this->nodes);
    }
}

G::LoadClass('case');

$o = new Cases();



$requestedNode = "";

if (isset($_REQUEST["node"])) {
    $requestedNode = $_REQUEST["node"];
}
$PRO_UID = '';
$treeNodes = new TreeNodes();

switch ($requestedNode){
  case 'node-root':
    $i=0;
    $APP_UID    = $_GET['APP_UID'];
    $DEL_INDEX  = $_GET['DEL_INDEX'];
    $outputHref = "cases_StepToReviseOutputs?ex=$i&PRO_UID=$PRO_UID&DEL_INDEX=$DEL_INDEX&APP_UID=$APP_UID";
    $treeNodes->add("node-dynaforms","Dynaforms","",false,false,"","");
    $treeNodes->add("node-input-documents","Input Documents","",false,false,"","");
    $treeNodes->add("node-output-documents","Output Documents","",true,false,$outputHref,"");
  break;
  case 'node-dynaforms':
    $i = 0;
    $APP_UID = $_GET['APP_UID'];
    $DEL_INDEX = $_GET['DEL_INDEX'];
    $steps = $o->getAllDynaformsStepsToRevise($_GET['APP_UID']);
    foreach ($steps as $step) {
      require_once 'classes/model/Dynaform.php';
      $od = new Dynaform();
      $dynaformF = $od->Load($step['STEP_UID_OBJ']);

      $n = $step['STEP_POSITION'];
      $TITLE   = " - ".$dynaformF['DYN_TITLE'];
      $DYN_UID = $dynaformF['DYN_UID'];
      $PRO_UID = $step['PRO_UID'];
      $href = "cases_StepToRevise?type=DYNAFORM&ex=$i&PRO_UID=$PRO_UID&DYN_UID=$DYN_UID&APP_UID=$APP_UID&position=".$step['STEP_POSITION']."&DEL_INDEX=$DEL_INDEX";
      $treeNodes->add($DYN_UID,$TITLE,"datasource",true,false,$href,"_parent");
      $i++;
    }
  break;
  case 'node-input-documents':
    $i = 0;
    $APP_UID = $_GET['APP_UID'];
    $DEL_INDEX = $_GET['DEL_INDEX'];
    $steps = $o->getAllInputsStepsToRevise($_GET['APP_UID']);
    //$i=1;
    foreach ($steps as $step) {
      require_once 'classes/model/InputDocument.php';
      $od = new InputDocument();
      $IDF = $od->Load($step['STEP_UID_OBJ']);

      $n = $step['STEP_POSITION'];
      $TITLE = " - ".$IDF['INP_DOC_TITLE'];
      $INP_DOC_UID = $IDF['INP_DOC_UID'];
      $PRO_UID = $step['PRO_UID'];
      $href = "cases_StepToReviseInputs?type=INPUT_DOCUMENT&ex=$i&PRO_UID=$PRO_UID&INP_DOC_UID=$INP_DOC_UID&APP_UID=$APP_UID&position=".$step['STEP_POSITION']."&DEL_INDEX=$DEL_INDEX";
      $treeNodes->add($INP_DOC_UID,$TITLE,"datasource",true,false,$href,"_parent");
      $i++;
    }
  break;
}
 
//    $treeNodes->add("input-doc-1","input doc 1","report",true,false,"","");
//    $treeNodes->add("input-doc-2","input doc 2","report",true,false,"","");
//    $treeNodes->add("input-doc-3","input doc 3","report",true,false,"","");

echo $treeNodes->toJson();

//echo $treeNodes->toJson();
//  $internalTreeNodes = new ExtJsTree("internal","Dynavars","data",true,false,"","");
//  $internalTreeNodes->add(new TreeNode("dynaforms","Dynaforms","data",true,false,"",""));
//  $internalTreeNodes->add(new TreeNode("datasets","Datasets","dataset",true,false,"",""));
//  echo "[".$internalTreeNodes->toJson()."]";
//
//  $treeNodes = new ExtJsTree("root","Root","data",true,false,"","");
//  $treeNodes->add(new TreeNode("dynaforms","Dynaforms","data",true,false,"",""));
//  $treeNodes->add(new TreeNode("datasets","Datasets","dataset",true,false,"",""));
//  $treeNodes->add(new TreeNode("reports","Reports","report",true,false,"",""));
//  $treeNodes->add($internalTreeNodes);
//  var_dump($treeNodes);
//  echo $treeNodes->toJson();