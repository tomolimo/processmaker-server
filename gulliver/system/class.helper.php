<?

class Helper{
  public $content;
  public $gzipEnabled;
  public $minified;
  public $gzipModuleEnabled;
  public $contentType;
  
  function __construct(){
    $this->content      = '';
    $this->gzipEnabled  = true;
    $this->minified     = true;
    $this->gzipModuleEnabled = false;
    $this->contentType = 'text/html';
  }
  
  function addFile($file){
    if( is_file($file) )
      $this->content .= file_get_contents($file);
  }
  
  function addContent($content){
      $this->content = $content;
  }
  
  function setContentType($ctype){
    $this->contentType = $ctype;
  }
  
  function init(){
    header("Content-type: {$this->contentType}");
    header('Pragma: cache');
    header('Cache-Control: public');

    if( $this->gzipEnabled && extension_loaded('zlib') ){
      $this->gzipModuleEnabled = true;
      ob_start('ob_gzhandler');
    } else 
      ob_start();
  }
  
  function minify(){
    $this->content = G::removeComments($this->content);
  }
  
  function flush(){
    if( $this->minified )
      $this->minify();
    print($this->content);
    ob_end_flush();
  }
  
  function serve($type=null){
    if( isset($type) )
      $this->setContentType($ctype);
    $this->init();
    $this->flush();
  }
} 

function minify($buffer) {
  return G::removeComments($buffer);
}