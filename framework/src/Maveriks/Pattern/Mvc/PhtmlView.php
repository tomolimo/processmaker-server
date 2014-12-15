<?php
namespace Maveriks\Pattern\Mvc;

class PhtmlView extends View
{
    public static $blocks = array();

    private static $instance;
    private static $extend = '';

    public function __construct($tpl = '')
    {
        parent::__construct($tpl);
    }

    //Wrapped

    public function render()
    {
        self::$instance = $this;
        $tplFile = $this->getTpl();

        if (empty($tplFile)) {
            throw new \Exception('ERROR: template file was not set!');
        }

        foreach ($this->getTemplateDir() as $dir) {
            if (file_exists($dir . DIRECTORY_SEPARATOR . $tplFile)) {
                $tplFile = $dir . DIRECTORY_SEPARATOR . $tplFile;
                break;
            }
        }

        if (! file_exists($tplFile)) {
            throw new \Exception('ERROR: template file '.$tplFile.' does not exist!');
        }

        extract($this->data);

        include $tplFile;

        if (! empty(self::$extend)) {
            $tplFile = ''; //$this->getTemplateDir() . DIRECTORY_SEPARATOR . self::$extend . '.phtml';

            foreach ($this->getTemplateDir() as $dir) {
                if (file_exists($dir . DIRECTORY_SEPARATOR . self::$extend . '.phtml')) {
                    $tplFile = $dir . DIRECTORY_SEPARATOR . self::$extend . '.phtml';
                    break;
                }
            }

            if (! file_exists($tplFile)) {
                throw new \Exception('ERROR: Layout template file '.$tplFile.' does not exist!');
            }

            extract($this->data);

            include $tplFile;
        }
    }

    static public function extend($name)
    {
        self::$extend = $name;
    }

    static public function block($name, $default = '')
    {
        if (is_string($default)) {
            if (array_key_exists($name, self::$blocks)) {
                $callback = self::$blocks[$name];
                $callback();
            } else {
                return $default;
            }

            return;
        } elseif ($default instanceof \Closure) {
            self::$blocks[$name] = $default;
        }
    }
}
