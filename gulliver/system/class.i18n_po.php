<?php

class i18n_PO
{
  private $_file   = NULL;
  private $_string = '';
  private $_meta;
  private $_fp;
  
  protected $_editingHeader;
  
  function __construct($file)
  {
    $this->_fp = fopen($file, 'w');
    
    if ( ! is_resource($this->_fp) ) {
      return FALSE;
    }
  }
  
  function buildInit()
  {
    // lock PO file exclusively
    if ( ! flock($this->_fp, LOCK_EX) ) {
      fclose($this->_fp);
      return FALSE;
    }
    
    $this->__init__();
  }
  
  function __init__()
  {
    $this->_meta = 'msgid ""';
    $this->_writeLine($this->_meta);
    $this->_meta = 'msgstr ""';
    $this->_writeLine($this->_meta);
    
    $this->_editingHeader = TRUE;
  }
  
  function addHeader($id, $value)
  {
    if( $this->_editingHeader ) {
      $meta = '"'.trim($id).': '.trim($value).'\n"';
      $this->_writeLine($meta);
    }
  }
  
  function addTranslatorComment($str)
  {
    $this->headerStroke();
    $comment = '# ' . trim($str);
    $this->_writeLine($comment);
  }
  
  function addExtractedComment($str)
  {
    $this->headerStroke();
    $comment = '#. ' . trim($str); 
    $this->_writeLine($comment);
  }
  
  function addReference($str)
  {
    $this->headerStroke();
    $reference = '#: ' . trim($str); 
    $this->_writeLine($reference);
  }
  
  function addFlag($str)
  {
    $this->headerStroke();
    $flag = '#, ' . trim($str); 
    $this->_writeLine($flag);
  }
  
  function addPreviousUntranslatedString($str)
  {
    $this->headerStroke();
    $str = '#| ' . trim($str); 
    $this->_writeLine($str);
  }
  
  function addTranslation($msgid, $msgstr)
  {
    $this->headerStroke();
    $this->_writeLine('msgid "'  . $this->prepare($msgid, true) . '"');
    $this->_writeLine('msgstr "' . $this->prepare($msgstr, true) . '"');
    $this->_writeLine('');
  }
  
  function _writeLine($str)
  {
    $this->_write($str . "\n");
  }
  
  function _write($str)
  {
    fwrite($this->_fp, $str);
  }
  
  function prepare($string, $reverse = false)
  {
    $string = str_replace('\"', '"', $string);
  
    if ($reverse) {
      $smap = array('"', "\n", "\t", "\r");
      $rmap = array('\"', '\\n"' . "\n" . '"', '\\t', '\\r');
      return (string) str_replace($smap, $rmap, $string);
    } else {
      $string = preg_replace('/"\s+"/', '', $string);
      $smap = array('\\n', '\\r', '\\t', '\"');
      $rmap = array("\n", "\r", "\t", '"');
      return (string) str_replace($smap, $rmap, $string);
    }
  }
  
  function headerStroke(){
    if( $this->_editingHeader ) {
      $this->_editingHeader = FALSE;
      $this->_writeLine('');;
    }
  }
  
  function __destruct() {
    if ( $this->_fp )
      fclose($this->_fp);
  }
}