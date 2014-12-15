<?php
namespace Maveriks\Util;

/**
 * Singleton Class Logger
 *
 * This Utility is useful to log local messages
 * @package ProcessMaker\Util
 * @author Erik Amaru Ortiz <aortiz.erik@gmail.com, erik@colosa.com>
 */
class Logger
{
    private static $instance;
    private $logFile;
    private $fp;

    protected function __construct()
    {
        $this->logFile = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'processmaker.log';
        if (! file_exists($this->logFile)) {
            if (! touch($this->logFile)) {
                error_log("ProcessMaker Log file can't be created!");
            }
            chmod($this->logFile, 0777);
        }

        $this->fp = fopen($this->logFile, "a+");
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Logger();
        }

        return self::$instance;
    }

    public function setLogLine()
    {
        $args = func_get_args();

        $this->setLog(date('Y-m-d H:i:s') . " ");

        foreach ($args as $str) {
            $this->setLog((is_string($str) ? $str : var_export($str, true)) . PHP_EOL);
        }
    }

    public function setLogInline()
    {
        $args = func_get_args();
        $this->setLog(date('Y-m-d H:i:s') . " ");

        foreach ($args as $str) {
            $this->setLog((is_string($str) ? $str : var_export($str, true)) . " ");
        }
    }

    public function setLog($str)
    {
        fwrite($this->fp, $str);
    }

    public static function log()
    {
        $me = Logger::getInstance();
        $args = func_get_args();

        call_user_func_array(array($me, 'setLogLine'), $args);
    }
}

