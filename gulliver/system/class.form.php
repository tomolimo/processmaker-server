<?php
/**
 * class.form.php
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2008 Colosa Inc.23
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 *
 */
/**
 * Class Form
 * @author David S. Callizaya S. <davidsantos@colosa.com>
 * @package gulliver.system
 * @access public
 * @dependencies XmlForm  xmlformTemplate
 */
class Form extends XmlForm
{
  var $id='';
  var $width = 600;
  var $title = '';
  var $fields = array();
  var $values = array();
  var $action = '';
  var $ajaxServer = '';
  var $enableTemplate = false;
  var $ajaxSubmit = false;
  var $callback='function(){}';
  var $in_progress='function(){}';
  var $template;
  var $className="formDefault";
  var $objectRequiredFields = null;
  var $nextstepsave = '';
  var $printdynaform = '';

  public $visual_frontend;

  /**
   * Function setDefaultValues
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @return string
   */
  function setDefaultValues( )
  {
    foreach($this->fields as $name => $content) {
      if (get_class($content) != '__PHP_Incomplete_Class') {
        if (isset($content->defaultValue))
          $this->values[$name] = $content->defaultValue;
        else
          $this->values[$name] = '';
      }
      else {
        $this->values[$name] = '';
      }
    }
    foreach($this->fields as $k => $v){
      if (is_object($v)) {//julichu
        $this->fields[$k]->owner =& $this;
      }
    }
  }
  
  /**
   * Function Form
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @param string filename
   * @param string home
   * @param string language
   * @param string forceParse
   * @param string $visual_frontend
   * @return string
   */
  function Form($filename, $home='', $language = '', $forceParse = false, $visual_frontend=null)
  {
    $this->visual_frontend = $visual_frontend;
    if ($language=== '') 
      $language = defined('SYS_LANG')? SYS_LANG : 'en';
    if ($home=== '') 
      $home = defined('PATH_XMLFORM')? PATH_XMLFORM :
      (defined('PATH_DYNAFORM')? PATH_DYNAFORM: '');
    //to do: obtain the error code in case the xml parsing has errors: DONE
    //Load and parse the xml file
    if ( substr($filename, -4) !== '.xml' ) 
      $filename = $filename . '.xml';
    $this->home=$home;
    $res = parent::parseFile( $filename , $language, $forceParse );
    if ($res==1) 
      trigger_error('Faild to parse file ' . $filename . '.', E_USER_ERROR );
    if ($res==2) 
      trigger_error('Faild to create cache file "' . $xmlform->parsedFile . '".', E_USER_ERROR );
    $this->setDefaultValues();
    //to do: review if you can use the same form twice. in order to use once or not.
    //DONE: Use require to be able to use the same xmlform more than once.
    foreach($this->fields as $k => $v)  {
      //too memory? but it fails if it's loaded with baneco.xml with SYS_LANG='es'
      //NOTE: This fails apparently when class of  ($this->fields[$k]) is PHP_Incomplete_Class (because of cache)
      if (is_object($v)) {//julichu
        $this->fields[$k]->owner =& $this;
        if ($this->fields[$k]->type==='grid') $this->fields[$k]->parseFile($home, $language);
      }
    }
    $this->template = PATH_CORE . 'templates/'.$this->type.'.html';
  }
  
  /**
   * Function printTemplate
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @param string template
   * @param string scriptContent
   * @return string
   */
  function printTemplate( $template, &$scriptContent )
  {
    if (!file_exists($template)){
      throw(new Exception('Template "'.basename($template).'" doesn`t exist.'));
    }
    $o = new xmlformTemplate($this, $template);
    if (is_array(reset($this->values))) 
      $this->rows=count(reset($this->values));
    if ($this->enableTemplate) {
      $filename = substr($this->fileName , 0, -3) .
        ( $this->type==='xmlform' ? '' : '.' . $this->type  ) . 'html';
      if (!file_exists( $filename )) {
        $o->template = $o->printTemplate( $this );
        $f=fopen($filename, 'w+');
        fwrite($f, $o->template);
        fclose($f);
      }
      $o->template = implode( '', file( $filename ) );
    } else {
      $o->template = $o->printTemplate( $this );
    }
    return $o->template;
  }
  
  /**
   * Function render
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @param string template
   * @param string scriptContent
   * @return string
   */
  function render( $template, &$scriptContent )
  {
    /***
     * This section was added for store the current used template.
   */
    $tmp_var = explode('/', $template);
    if( is_array($tmp_var) ){
      $tmp_var = $tmp_var[sizeof($tmp_var)-1];
      $this->using_template = $tmp_var;
    }
    /***/
    $this->template = $template;
    $o = new xmlformTemplate($this, $template);
    $values = $this->values;
    $aValuekeys=array_keys($values);
    if (isset($aValuekeys[0]) && ((int)$aValuekeys[0]==1)) 
      $values=XmlForm_Field_Grid::flipValues($values);
    //TODO: Review when $values of a grid has only one row it is converted as a $values for a list (when template="grid" at addContent())
    if (is_array(reset($values))) {
      $this->rows=count(reset($values));
    }
    if ($this->enableTemplate) {
      $filename = substr($this->fileName, 0, -3) . 'html';
      if (!file_exists( $filename )) {
        $o->template = $o->printTemplate( $this );
        $f=fopen($filename, 'w+');
        fwrite($f, $o->template);
        fclose($f);
      }
      $o->template = implode( '', file( $filename ) );
    } else {
      $o->template = $o->printTemplate( $this );
    }
    $scriptContent = $o->printJavaScript( $this );
    $content = $o->printObject($this);
    return $content;
  }

 /**
   * Function setValues
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @param array $newValues
   * @return string
   */
  function setValues($newValues=array())
  {
    if  ( !is_array ( $newValues) ){
      return;
    }
    foreach($this->fields as $k => $v){
      if ( array_key_exists($k,$newValues) ) {
        if ( is_array($newValues[$k]) ) {
          $this->values[$k] = array();
          foreach( $newValues[$k] as $j => $item ) {
            if ($this->fields[$k]->validateValue($newValues[$k][$j], $this ))
              $this->values[$k][$j] = $newValues[$k][$j];
          }
          if ((sizeof($this->values[$k])===1) && ($v->type!=='grid') && isset($this->values[$k][0]) ) 
            $this->values[$k] = $this->values[$k][0];
          if (sizeof($this->values[$k])===0) 
            $this->values[$k] = '';
        } else {
          if ($this->fields[$k]->validateValue($newValues[$k], $this ))
            $this->values[$k] = $newValues[$k];
        }
      }
    }
    foreach ($newValues as $k => $v) {
      if (strpos($k,'SYS_GRID_AGGREGATE_') !== false) {
        $this->values[$k] = $newValues[$k];
      }
    }
    foreach($this->fields as $k => $v){
      if(get_class($this->fields[$k])!='__PHP_Incomplete_Class'){
        $this->fields[$k]->owner =& $this;
      }
    }
    if(isset($this->labelWidth)){
      $nMaxPorcent = 1024;
      $nWidth = stripos($this->width, '%');
      if($nWidth > 0) {
        $sStrFind = $this->width;
        $result = substr($sStrFind, 0, strpos($sStrFind, '%'));   
        $nWidth = (int)(($nMaxPorcent/100)*$result);
      } else {
        $nWidth = (int)$this->width; 
        $nMaxPorcent = $nWidth;
      }
      $nLabelWidth = stripos($this->labelWidth, '%');
      if($nLabelWidth > 0) {
        $sStrFind = $this->labelWidth;
        $result = substr($sStrFind, 0, strpos($sStrFind, '%'));   
        $nLabelWidth = (int)(($nWidth/100)*$result);
      } else {
        $nLabelWidth = (int)$this->labelWidth; 
      }
        // krumo($nWidth,$nLabelWidth);
      if(($nWidth  - $nLabelWidth) > 0)
        $this->fieldContentWidth = (int)($nWidth - $nLabelWidth);
    }
  }
  
  /**
   * Function getFields
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @param string template
   * @param int $therow
   * @return string
   */
  function getFields($template, $therow = -1)
  {
    $o = new xmlformTemplate($this, $template);
    return $o->getFields( $this, $therow );
  }
  
  /**
   * Function that validates the values retrieved in $_POST
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @return array $_POST['form']
   */
  function validatePost()
  {
    return $_POST['form']=$this->validateArray($_POST['form']);
  }
  
  /**
   * Function that validates the values retrieved in an Array:
   *   ex $_POST['form']
   * @author David S. Callizaya S. <davidsantos@colosa.com>
   * @access public
   * @param array $newValues
   * @return array
   */
  function validateArray($newValues)
  {
    //$values = $this->values;
    $values = array();
    foreach($this->fields as $k => $v){
      if (($v->type != 'submit')) {
        if ($v->type != 'file') {
          if ( array_key_exists($k,$newValues) ) {
            if ( is_array($newValues[$k]) ) {
              if (($v->type == 'checkgroup') || ($v->type == 'listbox')) {
                $values[$k] = implode('|', $newValues[$k]);
              } else {
                foreach( $newValues[$k] as $j => $item ) {
                  if(is_array($item)){
                    $i=0;
                    // the variable fieldIsSet checks if a dropdown label has been saved so the values cannot be rewritted again.
                    $fieldIsSet = false;
                    foreach($item as $kk => $vv){//print_r($this->fields[$k]->fields[$kk]->type);print'<hr>';
                    //we need to know which fields are dropdowns
                      if($this->fields[$k]->fields[$kk]->type == 'dropdown'){
                        if(($this->fields[$k]->fields[$kk]->saveLabel)==1){
                          if ($this->fields[$k]->validateValue($newValues[$k][$j], $this )){
                            // also we need to know if the $values array has dropdown labels added
                            if(!$fieldIsSet) {
                              $values[$k][$j] = $this->fields[$k]->maskValue( $newValues[$k][$j], $this );
                              $values[$k][$j][$kk. '_label'] = $this->fields[$k]->fields[$kk]->options[$vv];
                              $fieldIsSet = true;
                            } else {
                              // if a dropdown label been set there is no need to load the default values.
                              if(isset($this->fields[$k]->fields[$kk]->options[$vv]))
                               $values[$k][$j][$kk. '_label'] = $this->fields[$k]->fields[$kk]->options[$vv];
                            }
                            $query = G::replaceDataField($this->fields[$k]->fields[$kk]->sql,$values[$k][$j]);
                            //$query = $this->fields[$k]->fields[$kk]->sql;
                            // added in order to save the label of dropdowns with data from a sql query
                            if (trim($this->fields[$k]->fields[$kk]->options[$vv])==''){
                              // a better approach could be use instead the method
                              // $dropdown->executeSQL($owner, $row);
                              // that is part of the dropdown class
                              $con = Propel::getConnection('workflow');
                              $stmt = $con->prepareStatement($query);
                              $rs = $stmt->executeQuery(ResultSet::FETCHMODE_NUM);
                              while ($rs->next()){
                                // from the query executed we only need certain elements
                                $row = $rs->getRow();
                                $rowId      = $row['0'];
                                $rowContent = $row['1'];
                                // we need only the first element and this is a one element array
                                foreach ($newValues[$k][$j] as $newValue){
                                  $valueId = $vv;
                                  break;
                                }

                                if ($valueId==$rowId){
                                  $values[$k][$j][$kk. '_label'] = $rowContent;
                                  break;
                                }
                              }
                            }
                          }
                        }else{
                          if ($this->fields[$k]->validateValue($newValues[$k][$j], $this )){
                            // if there is a dropdown but with a savelabel set to 0 this checks if a previous dropdown has been setted
                            // so if its true then the $values cannot be rewritted
                            if (!$fieldIsSet){
                              $values[$k][$j] = $this->fields[$k]->maskValue( $newValues[$k][$j], $this );//print_r($values[$k][$j]);print'<hr>';
                            }
                          }
                        }
                      } else {
                        if (!$fieldIsSet){
                          // if there are no dropdowns previously setted and the evaluated field is not a dropdown
                          // only then rewritte the $values
                          $values[$k][$j] = $this->fields[$k]->maskValue( $newValues[$k][$j], $this );
                        }
                      }
                      $i++;
                    }
                  }else{
                    if ($this->fields[$k]->validateValue($newValues[$k][$j], $this ))
                      $values[$k][$j] = $this->fields[$k]->maskValue( $newValues[$k][$j], $this );//print_r($values[$k][$j]);print'<hr>';
                  }
                }
                if ((sizeof($values[$k])===1) && ($v->type!=='grid') && ($this->type!=='grid')){
                  $values[$k] = $values[$k][0];
                }
                if (sizeof($values[$k])===0) 
                  $values[$k] = '';
              }
            } else {
              if ($this->fields[$k]->validateValue($newValues[$k], $this ))
                $values[$k] = $this->fields[$k]->maskValue( $newValues[$k], $this );
            }
            if ($v->type == 'dropdown') {
              if ($v->saveLabel == 1 && isset($v->option[$newValues[$k]])) {
              	if(isset($v->option[$newValues[$k]]))
                  $values[$k . '_label'] = $v->option[$newValues[$k]];
                //* Fixed when the dropdowns has a sql statement
                //* By krlos <carlos /a/ colosa.com>
                //* Aug 3rd, 2010 
                // added in order to save the label of dropdowns with data from a sql query
                $query = G::replaceDataField($this->fields[$k]->sql,$newValues);
                //if the value is empty there is a sql query
                if (!(isset($v->option[$newValues[$k]])) && trim($v->option[$newValues[$k]])==''){
                  // a better approach could be use instead the method
                  // $dropdown->executeSQL($owner, $row);
                  // that is part of the dropdown class
                  $con = Propel::getConnection('workflow');
                  $stmt = $con->prepareStatement($query);
                  $rs = $stmt->executeQuery(ResultSet::FETCHMODE_NUM);
                  while ($rs->next()){
                    $row = $rs->getRow();
                    $rowId      = $row['0'];
                    $rowContent = $row['1'];
                    if ($newValues[$k]==$rowId){
                      $values[$k . '_label'] = $rowContent;
                      break;
                    }
                  }
                }
              }
            }
          }
          else{
            if ($v->type == 'checkbox') {
              $values[$k]=$v->falseValue;
            }
            else {
              if ($v->type != 'javascript' && $v->type !='title') {
                $values[$k] = null;
              }
            }
          }
        }
        else {
          if (isset($_FILES['form']['name'][$k])) {
            $values[$k] = $_FILES['form']['name'][$k];
          }
          /** 
           * FIXED for multiple inputs documents related to file type field
           * By Erik Amaru Ortiz <erik@colosa.com>
           * Nov 24th, 2009 
           */
          if ( isset($v->input) && $v->input != ''){
            $_POST['INPUTS'][$k] = $v->input;
          }
          /**/
        }
      }
    }
    foreach ($newValues as $k => $v) {
      if (strpos($k, 'SYS_GRID_AGGREGATE_') !== false) {
        $values[$k] = $v;
      }
    }//die;
    return $values;
  }
  
  /**
   * Function that return the valid fields to replace
   * @author Julio Cesar Laura Avendao?=o <juliocesar@colosa.com>
   * @access public
   * @param boolean $bWhitSystemVars
   * @return array
   */
  function getVars($bWhitSystemVars = true) 
  {
    $aFields = array();
    if ($bWhitSystemVars) {
      $aAux    = G::getSystemConstants();
      foreach ($aAux as $sName => $sValue) {
        $aFields[] = array('sName' => $sName, 'sType' => 'system');
      }
    }
    foreach($this->fields as $k => $v) {
      if (($v->type != 'title')  && ($v->type != 'subtitle') && ($v->type != 'link')       &&
          ($v->type != 'file')   && ($v->type != 'button')   && ($v->type != 'reset')      &&
          ($v->type != 'submit') && ($v->type != 'listbox')  && ($v->type != 'checkgroup') &&
          ($v->type != 'grid')   && ($v->type != 'javascript')) {
        $aFields[] = array('sName' => trim($k), 'sType' => trim($v->type));
      }
    }
    return $aFields;
  }

   /**
   * Function that verify the required fields without a correct value
   * @author Erik Amaru Ortiz <erik@colosa.com>
   * @access public
   * @param array $values
   * @param array $aNoRequiredByJS 
   * @return array/false
   */
  function validateRequiredFields($values, $aNoRequiredByJS = array())
  {
    $rFields = Array();
    $missingFields = Array();
    foreach ($this->fields as $o) {
      if(property_exists(get_class($o), 'required')) {
        if( $o->required == 1) {
          if (!in_array($o->name, $aNoRequiredByJS)) {
            array_push($rFields, $o->name);
          }
        }
      }
    }
    foreach($rFields as $field){
      #we verify if the requiered field is in array values,. t
      if (array_key_exists($field, $values)) {
        if( $values[$field] == "") {
          array_push($missingFields, $field);
        }
      } else {
        array_push($missingFields, $field);
      }
    }
    if(sizeof($missingFields) != 0) {
      return $missingFields;
    } else {
      return false;
    }
  }
}
