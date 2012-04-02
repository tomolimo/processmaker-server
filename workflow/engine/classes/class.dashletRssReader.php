<?php

require_once 'classes/interfaces/dashletInterface.php';

class dashletRssReader implements DashletInterface {

  public static function getAdditionalFields($className) {
    $additionalFields = array();

    $urlFrom = new stdclass();
    $urlFrom->xtype = 'textfield';
    $urlFrom->name = 'DAS_URL';
    $urlFrom->fieldLabel = 'Url';
    $urlFrom->width = 250;
    $urlFrom->maxLength = 150;
    $urlFrom->allowBlank = false;
    $urlFrom->value = "http://";
    $additionalFields[] = $urlFrom;

    return $additionalFields;
  }

  public static function getXTemplate($className) {
    return "<iframe src=\"{page}?DAS_INS_UID={id}\" width=\"{width}\" height=\"207\" frameborder=\"0\"></iframe>";
  }

  public function setup($config) {
    $this->urlFrom  = isset($config['DAS_URL']) ? $config['DAS_URL'] : "http://license.processmaker.com/syspmLicenseSrv/en/green/services/rssAP";
    return true;
  }

  public function render ($width = 300) {
    $self->url = $this->urlFrom;
    $self->rss = @simplexml_load_file($self->url);
    if($self->rss)
    {
      $index= 0;
      $render = '';
      $self->items = $self->rss->channel->item;
      if (count($self->rss->channel)!= 0) {
        $status = 'true';
        foreach($self->items as $self->item)
        {
          $self->title = $self->item->title;
          $self->link = $self->item->link;
          
          $self->des = $self->item->description;
          $render[] = array('link' => '<a href="'.$self->link.'" target="_blank">'.$self->title.'</a><br/>','description' => $self->des.'<br/><hr>');
          $index++;
        }
      }
      else {
        $status = 'Error';
        $render[] =array('link' => 'Error', 'description' =>"Unable to parse XML");
      }
    }
    else {
      $status = 'Error';
      $render[] =array('link' => 'Error', 'description' =>"Unable to parse XML");
    }
    G::verifyPath ( PATH_SMARTY_C,     true );
    $smarty = new Smarty();
    $smarty->template_dir = PATH_CORE.'templates/dashboard/';
    $smarty->compile_dir  = PATH_SMARTY_C;

    try {
      $smarty->assign('url', $this->urlFrom);
      $smarty->assign('render', $render);
      $smarty->assign('status', $status);
    }
    catch (Exception $ex) {
      print $item->key;
    }
    $smarty->display('dashletRssReaderTemplate.html',null,null);
    
  }

}