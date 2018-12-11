<?php

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use ProcessMaker\Core\System;

class MonologProvider
{
    /**
     * @var MonologProvider
     */
    private static $instance = null;
    /**
     * @var LineFormatter
     */
    private $formatter;
    /**
     * @var RotatingFileHandler
     */
    private $streamRoutating;
    /**
     * @var Logger
     */
    private $registerLogger;

    //the default format "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
    private $output = "<%level%> %datetime% %channel% %level_name%: %message% %context% %extra%\n";
    private $dateFormat = 'M d H:i:s';
    /**
     * The maximal amount of files to keep (0 means unlimited)
     * @var int
     */
    private $maxFilesToKeep;
    /**
     * @var int level debug
     */
    private $levelDebug;
    /**
     * Whether the messages that are handled can bubble up the stack or not
     * @var boolean
     */
    private $bubble = true;
    /**
     * @var int file permissions
     */
    private $filePermission;
    /**
     * @var string path file
     */
    private $pathFile;

    /**
     * Logging levels from loo protocol defined in RFC 5424
     *
     * @var array $levels Logging levels
     */
    protected static $levels = [
        'DEBUG' => 100,
        'INFO' => 200,
        'NOTICE' => 250,
        'WARNING' => 300,
        'ERROR' => 400,
        'CRITICAL' => 500,
        'ALERT' => 550,
        'EMERGENCY' => 600
    ];

    /**
     * Construct of the class
     *
     * @param string $channel
     * @param string $fileLog
     * @param boolean $readLoggingLevel
     *
    */
    public function __construct($channel, $fileLog, $readLoggingLevel = true)
    {
        //Set path where the file will be saved
        $pathFile = $this->definePathFile();
        $this->setPathFile($pathFile);

        //Set maximal amount of files to keep (0 means unlimited)
        $maxFilesToRotation = $this->defineMaxFiles();
        $this->setMaxFiles($maxFilesToRotation);

        /**
         * The permissions are normally set at the operating system level, and it's the IT administrator responsibility to set the correct file permissions
         * It's not recommendable define in the env.ini configuration
        */
        $permissionFile = 0666;
        $permissionFile = is_int($permissionFile) ? decoct($permissionFile) : $permissionFile;
        $this->setFilePermission($permissionFile);

        $this->setFormatter();
        //Set the config: channel, fileLog and  levelDebug that will be saved
        $this->setConfig($channel, $fileLog, $readLoggingLevel);

        $this->testWriteLog($channel, $fileLog, [
            $pathFile
        ]);
    }

    /**
     * This function defines the debug level
     * We will to check if the logging_level exist in the env.ini
     *
     * @param boolean $readLoggingLevel
     *
     * @return string
    */
    private function defineLevelDebug($readLoggingLevel = true)
    {
        $levelDebug = 'INFO';

        if ($readLoggingLevel) {
            //In the parse_ini_file the word NONE are considered FALSE
            if (defined('LOGGING_LEVEL')) {
                $levelDebug = !empty(LOGGING_LEVEL) ? LOGGING_LEVEL : 'NONE';
            } else {
                //Getting configuration from env.ini
                $sysConf = System::getSystemConfiguration();
                $levelDebug = !empty($sysConf['logging_level']) ? $sysConf['logging_level'] : 'NONE';
            }
        }

        return $levelDebug;
    }

    /**
     * This function defines the path file
     * We will to check if the logs_location exist in the env.ini
     *
     * @return string
     */
    private function definePathFile()
    {
        $path = PATH_DATA . 'sites' . PATH_SEP . config('system.workspace') . PATH_SEP . 'log' . PATH_SEP;

        if (defined('LOGS_LOCATION')) {
            $path = !empty(LOGS_LOCATION) ? LOGS_LOCATION : $path;
        } else {
            $sysConf = System::getSystemConfiguration();
            $path = !empty($sysConf['logs_location']) ? $sysConf['logs_location'] : $path;
        }

        return $path;
    }

    /**
     * This function defines the max number of files
     * We will to check if the logs_max_files exist in the env.ini
     *
     * @return integer
    */
    private function defineMaxFiles()
    {
        $maxFilesToRotation = 60;

        if (defined('LOGS_MAX_FILES')) {
            $maxFilesToRotation = !empty(LOGS_MAX_FILES) ? LOGS_MAX_FILES : $maxFilesToRotation;
        } else {
            $sysConf = System::getSystemConfiguration();
            $maxFilesToRotation = !empty($sysConf['logs_max_files']) ? $sysConf['logs_max_files'] : $maxFilesToRotation;
        }

        return $maxFilesToRotation;
    }

    /**
     * Test write log
     *
     * @param string $channel
     * @param string $fileLog
     * @param array $paths
     */
    private function testWriteLog($channel, $fileLog, $paths)
    {
        $fileInfo = pathinfo($fileLog);
        $timedFilename = str_replace(
            ['{filename}', '{date}'],
            [$fileInfo['filename'], date('Y-m-d')],
            '{filename}-{date}'
        );

        if (!empty($fileInfo['extension'])) {
            $timedFilename .= '.' . $fileInfo['extension'];
        }

        if (!file_exists($this->getPathFile() . $timedFilename)) {
            try {
                $level = $this->getLevelDebug();
                if (!empty($level)) {
                    $this->getLogger()->addRecord($level, 'Start writing the log file');
                }
            } catch (UnexpectedValueException $exception) {
                //In case that the file can not be written, it will be written to the standard log file.
                error_log($exception->getMessage());
                if ($paths) {
                    $path = array_shift($paths);
                    $this->setPathFile($path);
                    $this->setConfig($channel, $fileLog);
                    $this->testWriteLog($channel, $fileLog, $paths);
                }
            } catch (Exception $exception) {
                //In case of an exception, it will be written to the standard log file.
                error_log($exception->getMessage());
            }
        }
    }

    /**
     * Return Formatter
     *
     * @return LineFormatter
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * Set LineFormatter $formatter
     */
    public function setFormatter()
    {
        $this->formatter = new LineFormatter($this->getOutput(), $this->getDateFormat());
    }

    /**
     * @return RotatingFileHandler
     */
    public function getStream()
    {
        return $this->streamRoutating;
    }

    /**
     * @param string File name
     */
    public function setStream($fileLog)
    {
        //Set Routating Handler
        $this->streamRoutating = new RotatingFileHandler($this->getPathFile() . $fileLog,
            $this->getMaxFiles(),
            $this->getLevelDebug(),
            $this->isBubble(),
            $this->getFilePermissionOctDec());

        $this->streamRoutating->setFormatter($this->getFormatter());
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->registerLogger;
    }

    /**
     * @param string $channel The logging channel
     */
    public function setLogger($channel)
    {
        //Create the channel and register the Logger with StreamRoutating
        $this->registerLogger = new Logger($channel);
        $this->registerLogger->pushProcessor(new IntrospectionProcessor());
        $this->registerLogger->pushHandler($this->getStream());
    }

    /**
     * Return format output
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set format output
     *
     * @param string $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * Return date format
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Set date format
     * @param string $dateFormat
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * Return is can bubble up the stack or not.
     *
     * @return boolean
     */
    public function isBubble()
    {
        return $this->bubble;
    }

    /**
     * Set bubble
     *
     * @param boolean $bubble
     */
    public function setBubble($bubble)
    {
        $this->bubble = $bubble;
    }

    /**
     * Return level debug
     *
     * @return int
     */
    public function getLevelDebug()
    {
        return $this->levelDebug;
    }

    /**
     * Return max files
     *
     * @return int
     */
    public function getMaxFiles()
    {
        return $this->maxFilesToKeep;
    }

    /**
     * Set max files
     *
     * @param int $maxFilesToKeep
     */
    public function setMaxFiles($maxFilesToKeep)
    {
        $this->maxFilesToKeep = $maxFilesToKeep;
    }

    /**
     * Return permissions of file
     *
     * @return int
     */
    public function getFilePermission()
    {
        return $this->filePermission;
    }

    /**
     * Returns the decimal equivalent of the octal number represented by the octal_string argument.
     *
     * @return int
     */
    public function getFilePermissionOctDec()
    {
        return octdec($this->filePermission);
    }

    /**
     * Set file permissions
     *
     * @param int $filePermission
     */
    public function setFilePermission($filePermission)
    {
        $this->filePermission = $filePermission;
    }

    /**
     * Returns the path where the file will be saved
     *
     * @return string
     */
    public function getPathFile()
    {
        return $this->pathFile;
    }

    /**
     * Set path
     *
     * @param string $pathFile
     */
    public function setPathFile($pathFile)
    {
        $pathSep = '/';
        if (strpos($pathFile, '\\') !== false) {
            $pathSep = '\\';
        }
        if (substr($pathFile, -1, strlen($pathSep)) !== $pathSep) {
            $pathFile .= $pathSep;
        }
        $this->pathFile = $pathFile;
    }

    /**
     * Set level debug by string
     *
     * @param string $levelDebug
     */
    public function setLevelDebug($levelDebug)
    {
        //If is a valid, we will to define the level
        if (isset(static::$levels[$levelDebug])) {
            $level = static::$levels[$levelDebug];
            $this->levelDebug = $level;
        }
    }

    /**
     * To get singleton instance
     *
     * @access public
     *
     * @param string $channel
     * @param string $fileLog
     * @param boolean $readLoggingLevel
     *
     * @return object
     */
    public static function getSingleton($channel, $fileLog, $readLoggingLevel = true)
    {
        if (self::$instance === null) {
            self::$instance = new MonologProvider($channel, $fileLog, $readLoggingLevel);
        } else {
            self::$instance->setConfig($channel, $fileLog, $readLoggingLevel);
        }
        return self::$instance;
    }

    /**
     * Set channel and fileLog
     *
     * @access public
     *
     * @param string $channel The logging channel
     * @param string $fileLog name file
     * @param boolean $readLoggingLevel
     */
    public function setConfig($channel, $fileLog, $readLoggingLevel = true)
    {
        $this->setStream($fileLog);
        $this->setLogger($channel);
        $levelDebug = $this->defineLevelDebug($readLoggingLevel);
        $this->setLevelDebug($levelDebug);
    }

    /**
     * Register log
     *
     * @access public
     *
     * @param int $level The logging level
     * @param string $message The log message
     * @param array $context The log context
     *
     * @return void
     */
    public function addLog($level, $message, $context)
    {
        if (!empty($this->getLevelDebug())) {
            $this->getLogger()->addRecord($level, $message, $context);
        }
    }
}