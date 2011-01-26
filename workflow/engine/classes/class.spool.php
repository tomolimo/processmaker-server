<?php
/**
 * class.tasks.php
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
 * spoolRun - brief send email from the spool database, and see if we have all the addresses we send to. 
 * @author Ian K Armstrong <ika@[REMOVE_THESE_CAPITALS]openmail.cc>
 * @copyright Copyright (c) 2007, Ian K Armstrong
 * @license http://www.opensource.org/licenses/gpl-3.0.html GNU Public License
 * @link http://www.openmail.cc
 */

/**
 * LOG FIXES
 * =========
 *
 * 24-03-2010   Erik A.O. <erik@colosa.com>
 * class: the $ExceptionCode and $aWarnings class attributes were added    
 * function handleFrom(): Validations for invalid data for {$this->fileData['from_email']} were added
 * function resendEmails(): handler for warnings was added and fixes
 * function getWarnings(): added
 * function sendMail(): now is handling the exception 
 */

require_once ('classes/model/AppMessage.php');

 /**
  * @package  workflow.ProcessMaker
  */


class spoolRun {
  private $config;
  private $fileData;
  private $spool_id;
  public  $status;
  public  $error;
  
  private $ExceptionCode = Array (); //Array to define the Expetion codes
  private $aWarnings = Array (); //Array to store the warning that were throws by the class

  private $longMailEreg;
  private $mailEreg;
  

  /**
   * Class constructor - iniatilize default values
   * @param none
   * @return none
   */
  function __construct() {
    $this->config = array ();
    $this->fileData = array ();
    $this->spool_id = '';
    $this->status = 'pending';
    $this->error = '';
    
    $this->ExceptionCode['FATAL']   = 1;
    $this->ExceptionCode['WARNING'] = 2;
    $this->ExceptionCode['NOTICE']  = 3;

    $this->longMailEreg = '/([\"\w\W\s]*\s*)?(<([\w\-\.]+@[\.-\w]+\.\w{2,3})+>)/';
    $this->mailEreg     = '/^([\w\-_\.]+@[\.-\w]+\.\w{2,3}+)$/';
  }
  
  /**
   * get all files into spool in a list
   * @param none
   * @return none
   */
  public function getSpoolFilesList() {
    $sql = "SELECT * FROM APP_MESSAGE WHERE APP_MSG_STATUS ='pending'";
    
    $con = Propel::getConnection("workflow");
    $stmt = $con->prepareStatement($sql);
    $rs = $stmt->executeQuery();
    
    while( $rs->next() ) {
      $this->spool_id = $rs->getString('APP_MSG_UID');
      $this->fileData['subject'] = $rs->getString('APP_MSG_SUBJECT');
      $this->fileData['from'] = $rs->getString('APP_MSG_FROM');
      $this->fileData['to'] = $rs->getString('APP_MSG_TO');
      $this->fileData['body'] = $rs->getString('APP_MSG_BODY');
      $this->fileData['date'] = $rs->getString('APP_MSG_DATE');
      $this->fileData['cc'] = $rs->getString('APP_MSG_CC');
      $this->fileData['bcc'] = $rs->getString('APP_MSG_BCC');
      $this->fileData['template'] = $rs->getString('APP_MSG_TEMPLATE');
      $this->fileData['attachments'] = array (); //$rs->getString('APP_MSG_ATTACH');
      if( $this->config['MESS_ENGINE'] == 'OPENMAIL' ) {
        if( $this->config['MESS_SERVER'] != '' ) {
          if( ($sAux = @gethostbyaddr($this->config['MESS_SERVER'])) ) {
            $this->fileData['domain'] = $sAux;
          } else {
            $this->fileData['domain'] = $this->config['MESS_SERVER'];
          }
        } else {
          $this->fileData['domain'] = gethostbyaddr('127.0.0.1');
        }
      }
      $this->sendMail();
    }
  }
  
  /**
   * create a msg record for spool
   * @param array $aData
   * @return none
   */
  public function create($aData) {
    G::LoadClass('insert');
    $oInsert = new insert();
    $sUID = $oInsert->db_insert($aData);
    
    $aData['app_msg_date'] = isset($aData['app_msg_date']) ? $aData['app_msg_date'] : '';
    
    if( isset($aData['app_msg_status']) ) {
      $this->status = strtolower($aData['app_msg_status']);
    }
    
    $this->setData($sUID, $aData['app_msg_subject'], $aData['app_msg_from'], $aData['app_msg_to'], $aData['app_msg_body'], $aData['app_msg_date'], $aData['app_msg_cc'], $aData['app_msg_bcc'], $aData['app_msg_template']);
  }
  
  /**
   * set configuration
   * @param array $aConfig
   * @return none
   */
  public function setConfig($aConfig) {
    $this->config = $aConfig;
  }
  
  /**
   * set email parameters
   * @param string $sAppMsgUid, $sSubject, $sFrom, $sTo, $sBody, $sDate, $sCC, $sBCC, $sTemplate
   * @return none
   */
  public function setData($sAppMsgUid, $sSubject, $sFrom, $sTo, $sBody, $sDate = '', $sCC = '', $sBCC = '', $sTemplate = '') {
    $this->spool_id = $sAppMsgUid;
    $this->fileData['subject'] = $sSubject;
    $this->fileData['from'] = $sFrom;
    $this->fileData['to'] = $sTo;
    $this->fileData['body'] = $sBody;
    $this->fileData['date'] = ($sDate != '' ? $sDate : date('Y-m-d H:i:s'));
    $this->fileData['cc'] = $sCC;
    $this->fileData['bcc'] = $sBCC;
    $this->fileData['template'] = $sTemplate;
    $this->fileData['attachments'] = array ();
    
    if( $this->config['MESS_ENGINE'] == 'OPENMAIL' ) {
      if( $this->config['MESS_SERVER'] != '' ) {
        if( ($sAux = @gethostbyaddr($this->config['MESS_SERVER'])) ) {
          $this->fileData['domain'] = $sAux;
        } else {
          $this->fileData['domain'] = $this->config['MESS_SERVER'];
        }
      } else {
        $this->fileData['domain'] = gethostbyaddr('127.0.0.1');
      }
    }
  }
  
  /**
   * send mail
   * @param none
   * @return boolean true or exception
   */
  public function sendMail() {
    try {
      $this->handleFrom();
      $this->handleEnvelopeTo();
      $this->handleMail();
      $this->updateSpoolStatus();
      return true;
    } catch( Exception $e ) {
      throw $e;
    }
  }
  
  /**
   * update the status to spool
   * @param none
   * @return none
   */
  private function updateSpoolStatus() {
    $oAppMessage = AppMessagePeer::retrieveByPK($this->spool_id);
    $oAppMessage->setappMsgstatus($this->status);
    $oAppMessage->setappMsgsenddate(date('Y-m-d H:i:s'));
    $oAppMessage->save();
  }
  
  /**
   * handle the email that was set in "TO" parameter 
   * @param none
   * @return boolean true or exception
   */
  private function handleFrom() {
    if( strpos($this->fileData['from'], '<') !== false ) {      
      //to validate complex email address i.e. Erik A. O <erik@colosa.com>
      preg_match($this->longMailEreg, $this->fileData['from'], $matches);
      if( isset($matches[1]) && $matches[1] != '' ) {
        //drop the " characters if they exist
        $this->fileData['from_name'] = trim(str_replace('"', '', $matches[1]));
      } else { //if the from name was not set
        $this->fileData['from_name'] = 'Processmaker';
      }
      
      if( ! isset($matches[3]) ) {
        throw new Exception('Invalid email address in FROM parameter (' . $this->fileData['from'] . ')', $this->ExceptionCode['WARNING']);
      }
      
      $this->fileData['from_email'] = trim($matches[3]);
    } else {
      //to validate simple email address i.e. erik@colosa.com
      preg_match($this->mailEreg, $this->fileData['from'], $matches);
      
      if( ! isset($matches[0]) ) {
        throw new Exception('Invalid email address in FROM parameter (' . $this->fileData['from'] . ')', $this->ExceptionCode['WARNING']);
      }
      
      $this->fileData['from_name'] = 'Processmaker Web boot';
      $this->fileData['from_email'] = $matches[0];
    }
  
  }
  
  /**
   * handle all recipients to compose the mail
   * @param none
   * @return boolean true or exception
   */
  private function handleEnvelopeTo() {
    $hold = array ();
    $holdcc = array ();
    $holdbcc = array ();
    $text = trim($this->fileData['to']);
    
    $textcc ='';
    $textbcc='';    
    if( isset($this->fileData['cc']) && trim($this->fileData['cc']) != '' ) {
      $textcc = trim($this->fileData['cc']);
    }

    if( isset($this->fileData['bcc']) && trim($this->fileData['bcc']) != '' ) {
      $textbcc = trim($this->fileData['bcc']);
    }
    
    if( false !== (strpos($text, ',')) ) {
      $hold = explode(',', $text);
      
      foreach( $hold as $val ) {
        if( strlen($val) > 0 ) {
          $this->fileData['envelope_to'][] = "$val";
        }
      }
    } else {
      $this->fileData['envelope_to'][] = "$text";
    }
    //for cc add by alvaro
     if( false !== (strpos($textcc, ',')) ) {
      $holdcc = explode(',', $textcc);

      foreach( $holdcc as $valcc ) {
        if( strlen($valcc) > 0 ) {
          $this->fileData['envelope_cc'][] = "$valcc";
        }
      }
    } else {
      $this->fileData['envelope_cc'][] = "$textcc";
    }
    //forbcc add by alvaro
     if( false !== (strpos($textbcc, ',')) ) {
      $holdbcc = explode(',', $textbcc);

      foreach( $holdbcc as $valbcc ) {
        if( strlen($valbcc) > 0 ) {
          $this->fileData['envelope_bcc'][] = "$valbcc";
        }
      }
    } else {
      $this->fileData['envelope_bcc'][] = "$textbcc";
    }


  }
  
  /**
   * handle and compose the email content and parameters
   * @param none
   * @return none
   */
  private function handleMail() {
    if( count($this->fileData['envelope_to']) > 0 ) {
      switch( $this->config['MESS_ENGINE'] ) {
        case 'MAIL':
          G::LoadThirdParty('phpmailer', 'class.phpmailer');
          $oPHPMailer = new PHPMailer();
          $oPHPMailer->Mailer = 'mail';
          $oPHPMailer->SMTPAuth = (isset($this->config['SMTPAuth']) ? $this->config['SMTPAuth'] : '');
          $oPHPMailer->Host = $this->config['MESS_SERVER'];
          $oPHPMailer->Port = $this->config['MESS_PORT'];
          $oPHPMailer->Username = $this->config['MESS_ACCOUNT'];
          $oPHPMailer->Password = $this->config['MESS_PASSWORD'];
          $oPHPMailer->From = $this->fileData['from_email'];
          $oPHPMailer->FromName = utf8_decode($this->fileData['from_name']);
          $oPHPMailer->Subject = utf8_decode($this->fileData['subject']);
          $oPHPMailer->Body = utf8_decode($this->fileData['body']);
          
          foreach( $this->fileData['envelope_to'] as $sEmail ) {
            if( strpos($sEmail, '<') !== false ) {
              preg_match($this->longMailEreg, $sEmail, $matches);
              $sTo = trim($matches[3]);
              $sToName = trim($matches[1]);
              $oPHPMailer->AddAddress($sTo, $sToName);
            } else {
              $oPHPMailer->AddAddress($sEmail);
            }
          }
          
          $oPHPMailer->IsHTML(true);
          if( $oPHPMailer->Send() ) {
            $this->error = '';
            $this->status = 'sent';
          } else {
            $this->error = $oPHPMailer->ErrorInfo;
            $this->status = 'failed';
          }
          break;
        case 'PHPMAILER':
          G::LoadThirdParty('phpmailer', 'class.phpmailer');
          $oPHPMailer = new PHPMailer();
          $oPHPMailer->Mailer = 'smtp';
          $oPHPMailer->SMTPAuth = (isset($this->config['SMTPAuth']) ? $this->config['SMTPAuth'] : '');
          $oPHPMailer->Host = $this->config['MESS_SERVER'];
          $oPHPMailer->Port = $this->config['MESS_PORT'];
          $oPHPMailer->Username = $this->config['MESS_ACCOUNT'];
          $oPHPMailer->Password = $this->config['MESS_PASSWORD'];
          $oPHPMailer->From = $this->fileData['from_email'];
          $oPHPMailer->FromName = utf8_decode($this->fileData['from_name']);
          $oPHPMailer->Subject = utf8_decode($this->fileData['subject']);
          $oPHPMailer->Body = utf8_decode($this->fileData['body']);
          
          foreach( $this->fileData['envelope_to'] as $sEmail ) {
            $evalMail = strpos($sEmail, '<');
            
            if( strpos($sEmail, '<') !== false ) {
              preg_match($this->longMailEreg, $sEmail, $matches);
              $sTo = trim($matches[3]);
              $sToName = trim($matches[1]);
              $oPHPMailer->AddAddress($sTo, $sToName);
            } else {
              $oPHPMailer->AddAddress($sEmail);
            }
          }
          //add cc add by alvaro
           foreach( $this->fileData['envelope_cc'] as $sEmail ) {
            $evalMail = strpos($sEmail, '<');

            if( strpos($sEmail, '<') !== false ) {
              preg_match($this->longMailEreg, $sEmail, $matches);
              $sTo = trim($matches[3]);
              $sToName = trim($matches[1]);
              $oPHPMailer->AddCC($sTo, $sToName);
            } else {
              $oPHPMailer->AddCC($sEmail);
            }
          }
           //add bcc add by alvaro
           foreach( $this->fileData['envelope_bcc'] as $sEmail ) {
            $evalMail = strpos($sEmail, '<');

            if( strpos($sEmail, '<') !== false ) {
              preg_match($this->longMailEreg, $sEmail, $matches);
              $sTo = trim($matches[3]);
              $sToName = trim($matches[1]);
              $oPHPMailer->AddBCC($sTo, $sToName);
            } else {
              $oPHPMailer->AddBCC($sEmail);
            }
          }

          
          $oPHPMailer->IsHTML(true);
          if( $oPHPMailer->Send() ) {
            $this->error = '';
            $this->status = 'sent';
          } else {
            $this->error = $oPHPMailer->ErrorInfo;
            $this->status = 'failed';
          }
          break;
        case 'OPENMAIL':
          G::LoadClass('package');
          G::LoadClass('smtp');
          $pack = new package($this->fileData);
          $header = $pack->returnHeader();
          $body = $pack->returnBody();
          $send = new smtp();
          $send->setServer($this->config['MESS_SERVER']);
          $send->setPort($this->config['MESS_PORT']);
          $send->setUsername($this->config['MESS_ACCOUNT']);
          $send->setPassword($this->config['MESS_PASSWORD']);
          $send->setReturnPath($this->fileData['from_email']);
          $send->setHeaders($header);
          $send->setBody($body);
          $send->setEnvelopeTo($this->fileData['envelope_to']);
          if( $send->sendMessage() ) {
            $this->error = '';
            $this->status = 'sent';
          } else {
            $this->error = implode(', ', $send->returnErrors());
            $this->status = 'failed';
          }
          break;
      }
    }
  }
  
  /**
   * try resend the emails from spool
   * @param none
   * @return none or exception
   */
  function resendEmails() {
    
    require_once 'classes/model/Configuration.php';
    $oConfiguration = new Configuration();
    $aConfiguration = $oConfiguration->load('Emails', '', '', '', '');
    $aConfiguration = unserialize($aConfiguration['CFG_VALUE']);
    
    if( $aConfiguration['MESS_ENABLED'] == '1' ) {
      $this->setConfig(array (
        'MESS_ENGINE' => $aConfiguration['MESS_ENGINE'], 
        'MESS_SERVER' => $aConfiguration['MESS_SERVER'], 
        'MESS_PORT' => $aConfiguration['MESS_PORT'], 
        'MESS_ACCOUNT' => $aConfiguration['MESS_ACCOUNT'], 
        'MESS_PASSWORD' => $aConfiguration['MESS_PASSWORD'] 
      ));
      require_once 'classes/model/AppMessage.php';
      $oCriteria = new Criteria('workflow');
      $oCriteria->add(AppMessagePeer::APP_MSG_STATUS, 'sent', Criteria::NOT_EQUAL);
      $oDataset = AppMessagePeer::doSelectRS($oCriteria);
      $oDataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);
      
      while( $oDataset->next() ) {
        $aRow = $oDataset->getRow();
        try {
          $this->setData($aRow['APP_MSG_UID'], $aRow['APP_MSG_SUBJECT'], $aRow['APP_MSG_FROM'], $aRow['APP_MSG_TO'], $aRow['APP_MSG_BODY']);
          $this->sendMail();
        } catch( Exception $oException ) {
          if( $oException->getCode() == $this->ExceptionCode['WARNING'] ) {
            array_push($this->aWarnings, 'Spool::resendEmails(): Using ' . $aConfiguration['MESS_ENGINE'] . ' for APP_MGS_UID=' . $aRow['APP_MSG_UID'] . ' -> With message: ' . $oException->getMessage());
            continue;
          } else {
            throw $oException;
          }
        }
      }
    }
  }
  
  /**
   * gets all warnings
   * @param none
   * @return string $this->aWarnings 
   */
  function getWarnings() {
    if( sizeof($this->aWarnings) != 0 ) {
      return $this->aWarnings;
    }
    return false;
  }
}
?>
