<?php

/**
 * @brief smtp class to send emails. Requires an email server.
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
 * @file class.smtp.php
 *
 */

  //-------------------------------------------------------------
  // smtp authentication
  //-------------------------------------------------------------
  // setSmtpAuthentication($sAuth)
  // setUsername($sName)
  // setPassword($sPass)
  //-------------------------------------------------------------

class smtp
{
  private $mail_server;
  private $port;
  private $return_path;
  private $envelope_to;
  private $status;
  private $headers;
  private $body;
  private $log;
  private $with_auth;
  private $username;
  private $password;

  /**
   * Function __construct
   * Constructor of the class
   * @return void
   */
  function __construct()
  {
    $this->status  = false;
    $this->with_auth = false;   // change to 'true' to use smtp authentication
    $this->username = '';       // needed for smtp authentication
    $this->password = '';       // needed for smtp authentication
    $this->mail_server = @gethostbyaddr('127.0.0.1');
    $this->port = 25;
    $this->return_path = '';
    $this->envelope_to = array();
    $this->headers = '';
    $this->body = '';
    $this->log = array();
  }

  /**
   * Function setServer
   * This sets the server used for sending mail
   * @param string $sServer
   * @return void
   */
  public function setServer($sServer)
  {
    if(($sAux = @gethostbyaddr($sServer)))
      $sServer = $sAux;
    $this->mail_server = $sServer;
  }

  /**
   * Function setPort
   * This sets the port used for sending mail
   * @param string $iPort
   * @return void
   */
  public function setPort($iPort) 
  {
    $this->port = ($iPort != '' ? (int)$iPort : 25);
  }

  /**
   * Function setReturnPath
   * This function sets the return path
   * @param string $sReturnPath
   * @return void
   */
  public function setReturnPath($sReturnPath) 
  {
    $this->return_path = $sReturnPath;
  }

  /**
   * Function setHeaders
   * This sets the headers of the mail to be sent
   * @param string $sHeaders
   * @return void
   */
  public function setHeaders($sHeaders) 
  {
    $this->headers = $sHeaders;
  }
  
  /**
   * Function setBody
   * This sets the mail body
   * @param string $sBody
   * @return void
   */
  public function setBody($sBody) 
  {
    $this->body = $sBody;
  }

  /**
   * Function setSmtpAuthentication
   * This function sets the Smtp Authentication
   * @param string $sAuth
   * @return void
   */
  public function setSmtpAuthentication($sAuth) 
  {
    $this->with_auth = $sAuth;
  }

  /**
   * Function setUsername
   * This function sets the user name
   * @param string $sName
   * @return void
   */ 
  public function setUsername($sName) 
  {
    $this->username = $sName;
  }

  /**
   * Function setPassword
   * This function sets the password 
   * @param string $sPass
   * @return void
   */
  public function setPassword($sPass) 
  {
    $this->password = $sPass;
  }

  /**
   * Function returnErrors
   * This Function returns errors
   * @return void
   */
  public function returnErrors() 
  {
    return $this->log;
  }
  
  /**
   * Function returnStatus
   * @return void
   */
  public function returnStatus() 
  {
    return $this->status;
  }
  
  /**
   * Function setEnvelopeTo
   * @param string $env_to
   * @return void
   */
  public function setEnvelopeTo($env_to)
  {
    if(count($env_to)>0){
      foreach($env_to as $val){
        (false !== ($p = strpos($val,'<')))
         ? $this->envelope_to[] = trim(substr($val,$p))
         : $this->envelope_to[] = trim($val);
      }
    }
  }
  
  /**
   * Function sendMessage
   * This function is responsible for sending the message 
   * @return boolean
   */
  public function sendMessage()
  {
    // connect
    $errno = $errstr = '';
    $cp = @fsockopen("$this->mail_server", $this->port, $errno, $errstr, 1);
    if(!$cp){
      $this->log[] = 'Failed to make a connection';
      return false;
    }
    $res = fgets($cp,256);
    if(substr($res,0,3) != '220'){
      $this->log[] = $res.' Failed to connect';
      fclose($cp);
      return false;
    }
    if(false !== $this->with_auth){
        // say EHLO - works with SMTP and ESMTP servers
      fputs($cp, 'EHLO '."$this->mail_server\r\n");
      $res = fgets($cp,256);
      if(substr($res,0,3) != '250'){
        $this->log[] = $res.' Failed to say EHLO';
        fclose($cp);
        return false;
      }
        // Request Authentication
      fputs($cp, 'AUTH LOGIN'."\r\n");
      $res = fgets($cp,256);
      if(substr($res,0,3) != '334'){
        $this->log[] = $res.' Auth Login Failed';
        fclose($cp);
        return false;
      }
        // Send Username
      fputs($cp, base64_encode($this->username)."\r\n");
      $res = fgets($cp,256);
      if(substr($res,0,3) != '334'){
        $this->log[] = $res.' Username failed';
        fclose($cp);
        return false;
      }
      // Send Password
      fputs($cp, base64_encode($this->password)."\r\n");
      $res = fgets($cp,256);
      if(substr($res,0,3) != '235'){
        $this->log[] = $res.' Password failed';
        fclose($cp);
        return false;
      }
    }
    else{// without smtp authentication
        // say HELO
      fputs($cp, 'HELO '."$this->mail_server\r\n");
      $res = fgets($cp,256);
      if(substr($res,0,3) != '250'){
        $this->log[] = $res.' Failed to say HELO';
        fclose($cp);
        return false;
      }
    }
      // mail from
    fputs($cp, 'MAIL FROM: '."$this->return_path\r\n");
    $res = fgets($cp,256);
    if(substr($res,0,3) != '250'){
      $this->log[] = $res.' MAIL FROM failed';
      fclose($cp);
      return false;
    }
      // mail to
    foreach($this->envelope_to as $val){
      fputs($cp, 'RCPT TO: '."$val\r\n");
      $res = fgets($cp,256);
      if(substr($res,0,3) != '250'){
        $this->log[] = $res.' RCPT TO failed';
        fclose($cp);
        return false;
      }
    }
      // data
    fputs($cp, 'DATA'."\r\n");
    $res = fgets($cp,256);
    if(substr($res,0,3) != '354'){
      $this->log[] = $res.' DATA failed';
      fclose($cp);
      return false;
    }
      // send headers
    fputs($cp, "$this->headers\r\n");
    // send body
    fputs($cp, "$this->body\r\n");
    // end of message
    fputs($cp, "\r\n.\r\n");
    $res = fgets($cp,256);
    if(substr($res,0,3) != '250'){
      $this->log[] = $res. ' Message failed';
      fclose($cp);
      return false;
    }
      // quit
    fputs($cp, 'QUIT'."\r\n");
    $res = fgets($cp,256);
    if(substr($res,0,3) != '221'){
      $this->log[] = $res.' QUIT failed';
      fclose($cp);
      return false;
    }
    fclose($cp);
    $this->status  = true;
  }
} // end of class
?>