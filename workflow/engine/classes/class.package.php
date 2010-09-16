<?php

/**
 * @brief Package spool files for sending
 *
 * @package Tomahawk_Mail
 * @author Ian K Armstrong <ika@[REMOVE_THESE_CAPITALS]openmail.cc>
 * @copyright Copyright (c) 2007, Ian K Armstrong
 * @license http://www.opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link http://www.openmail.cc
 *
 * @category web_mail
 * @subpackage mail
 * @filesource
 * @version
 *
 * @file class.package.php
 *
 */

class package
{
  private $headers;
  private $message;
  private $charset;
  private $emailboundary;
  private $debug;
  private $fileData;
  private $max_line_length;
  private $with_html;

  /** 
  * This function is the constructor of the package class 
  * @param array $fileData
  * @return void
  */
  function __construct($fileData=array())
  {
    $this->fileData         = array();
    $this->debug            = 1;
    $this->emailboundary    = 'Part-'.md5(uniqid(microtime()));
    $this->charset          = 'UTF-8'; //'ISO-8859-1'
    $this->headers          = '';
    $this->message          = '';
    $this->with_html        = false;
    $this->max_line_length  = '70';

    if(count($fileData)>0) {
      $this->fileData = $fileData;
      $this->addHeaders();
      $this->compileBody();
    }

  }

  /** 
  * This function returns the header
  * @param 
  * @return string
  */
  public function returnHeader()
  {
    return $this->headers;

  }
  
  /** 
  * This function returns the body
  * @param 
  * @return string
  */
  public function returnBody()
  {
    return $this->message;

  }

  /** 
  * This function returns an error
  * @param string $error
  * @return string
  */
  private function returnErrors($error)
  {
    if($this->debug>0) return $error;

  }

  /** 
  * This function puts a headers (to, cc, etc)
  * @param 
  * @return void
  */
  private function addHeaders()
  {
    $header = '';

    (strlen($this->fileData['from_name'])>0)
    ? $header .= 'From: '."{$this->fileData['from_name']}".' <'."{$this->fileData['from_email']}>"."\r\n"
    : $header .= 'From: '."<{$this->fileData['from_email']}>"."\r\n";

    // to
    if(strlen($this->fileData['to'])>0)
    {
      $to = "{$this->fileData['to']}";
      $header .= 'To: '.$to."\r\n";

    }

    // cc
    if(strlen($this->fileData['cc'])>0)
    {
      $cc = "{$this->fileData['cc']}";
      $header .= 'Cc: '.$cc."\r\n";

    }

    $header .= 'X-Sender: '."{$this->fileData['from_email']}"."\r\n";
    $header .= 'Return-Path: <'."{$this->fileData['from_email']}".'>'."\r\n";
    $header .= 'Errors-To: '."{$this->fileData['from_email']}"."\r\n";
    $header .= 'Reply-To: '."{$this->fileData['from_email']}"."\r\n";

    if(!empty($this->fileData['reference']))
    {
      $header .= 'In-Reply-To: <'."{$this->fileData['reference']}".'>'."\r\n";
      $header .= 'References: <'."{$this->fileData['reference']}".'>'."\r\n";

    }

    $header .= 'Message-Id: <'.md5(uniqid(rand())).':'
      .str_replace(' ','_', "{$this->fileData['from_name']}")
      .'@'."{$this->fileData['domain']}".'>'."\r\n";

    $header .= 'X-Mailer: ProcessMaker <http://www.processmaker.com>'."\r\n";
    $header .= 'X-Priority: 3'."\r\n";
    $header .= 'Date: '."{$this->fileData['date']}"."\r\n";
    $header .= 'Subject: '."{$this->fileData['subject']}"."\r\n";
    $header .= 'MIME-Version: 1.0'."\r\n";

    (count($this->fileData['attachments'])>0)
      ? $header .= 'Content-Type: multipart/mixed; '."\r\n\t".'boundary="'.$this->emailboundary.'"'."\r\n"
      : $header .= 'Content-Type: multipart/alternative; '."\r\n\t".'boundary="'.$this->emailboundary.'"'."\r\n";

    $header .= 'This is a multi-part message in MIME format'."\r\n";

    $this->headers = $header;

  }

  /** 
  * This function adds a file (to, cc, etc)
  * @param string $data
  * @return string
  */
  private function addAttachment($data='')
  {
    $attach_this = '';

    if(trim($data)!='')
    {
      list($file,$name,$type) = explode('|',$data);

      if(is_readable($file))
      {
        // attachment header
        $attachment_header  = '--'.$this->emailboundary."\r\n";
        $attachment_header .= 'Content-type: '.$type.'; name="'.$name.'"'."\r\n";
        $attachment_header .= 'Content-transfer-encoding: base64'."\r\n";
        $attachment_header .= 'Content-disposition: attachment; filename="'.$name.'"'."\r\n\r\n";

        // read, encode, chunk split
        $file_content = file_get_contents($file);
        $file_content = base64_encode($file_content);
        $file_content = chunk_split($file_content,70);

        // add content and header
        $attach_this = $attachment_header.$file_content."\r\n";

      } else { $this->returnErrors($file.' not readable in addAttachment');}

    } else { $this->returnErrors('missing data in addAttachment');}

    return $attach_this;

  }

  /** 
  * This function fixs body
  * @param string $data
  * @return string
  */
  private function fixbody()
  {
    $lines  = array();
    $b      = '';
    $body   = "{$this->fileData['body']}";
    $body   = str_replace("\r", "\n", str_replace("\r\n", "\n", $body));
    $lines  = explode("\n", $body);

    foreach($lines as $line)
    {
      // wrap lines
      $line = wordwrap($line, $this->max_line_length, "\r\n");

      // leading dot problem
      if(substr($line, 0,1) == '.') $line = '.' . $line;

      $b .= $line."\r\n";
    }
    return $b;

  }

  /** 
  * This function compiles message
  * @param 
  * @return void
  */
  private function compileBody()
  {
    $comp = '';
    $body = $this->fixbody();

    // text
    $comp .= '--'.$this->emailboundary."\r\n";
    $comp .= 'Content-Type: text/plain; charset='.$this->charset."\r\n";
    $comp .= 'Content-Transfer-Encoding: 8bit'."\r\n\r\n";
    $comp .= "$body"."\r\n\r\n";

    // html
    if($this->with_html)
    {
      $temp = file_get_contents('template.html');
      $temp = str_replace("\n", "", str_replace("\r", "", $temp));

      $comp .= '--'.$this->emailboundary."\r\n";
      $comp .= 'Content-Type: text/html; charset='.$this->charset."\r\n";
      $comp .= 'Content-Transfer-Encoding: 8bit'."\r\n\r\n";

      $body = str_replace('[>content<]',"$body",$temp);
      $comp .= "$body"."\r\n\r\n";

    }

    // attachments
    /*
    if(count($this->fileData['attachments'])>0)
    {
      foreach($this->fileData['attachments'] as $data)
      {
        $comp  .= $this->addAttachment($data);

      }

    }*/

    $comp .= '--'.$this->emailboundary.'--'."\r\n";

    $this->message = $comp;

  }

} // end of class


?>
