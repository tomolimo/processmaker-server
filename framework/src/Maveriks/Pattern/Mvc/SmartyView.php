<?php
namespace Maveriks\Pattern\Mvc;

use Maveriks\Util\Common;

class SmartyView extends View
{
    protected $smarty;

    public function __construct($tpl = '')
    {
        parent::__construct($tpl);
        require_once PATH_THIRDPARTY . 'smarty/libs/Smarty.class.php'; //

        $this->smarty = new \Smarty();
        $this->smarty->compile_dir  = defined('PATH_SMARTY_C')? PATH_SMARTY_C : sys_get_temp_dir();
        $this->smarty->cache_dir    = defined('PATH_SMARTY_CACHE')? PATH_SMARTY_CACHE : sys_get_temp_dir();

        if (! is_dir($this->smarty->compile_dir)) {
            Common::mk_dir($this->smarty->compile_dir);
        }
        if (! is_dir($this->smarty->cache_dir)) {
            Common::mk_dir($this->smarty->cache_dir);
        }
    }

    public function assign($name, $value)
    {
        $this->smarty->assign($name, $value);
    }

    public function render()
    {
        $this->smarty->display($this->getTpl());
    }
}
