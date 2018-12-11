<?php
namespace Maveriks\Util;

class ClassLoader
{
    private static $includePath = array();
    private static $includePathNs = array();
    private static $includeModelPath = array();
    private static $includeClassPath = array();
    protected static $instance;

    /**
     * Creates a new <tt>SplClassLoader</tt> that loads classes of the
     * specified namespace.
     *
     * @param string $ns The namespace to use.
     */
    public function __construct()
    {
        defined("DS") || define("DS", DIRECTORY_SEPARATOR);
        defined("NS") || define("NS", "\\");

        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * @return \Maveriks\Util\ClassLoader
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Gets the base include path for all class files in the namespace of this class loader.
     *
     * @return string $includePath
     */
    public function getIncludePaths()
    {
        return self::$includePath;
    }

    /**
     * Uninstalls this class loader from the SPL autoloader stack.
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    public function add($sourceDir, $namespace = "")
    {
        if (empty($namespace)) {
            self::$includePath[] = $sourceDir . (substr($sourceDir, -1) == DS ? "" : DS);
        } else {
            self::$includePathNs[$namespace] = $sourceDir . (substr($sourceDir, -1) == DS ? "" : DS);
        }
    }

    public function addModelClassPath($classPath)
    {
        self::$includeModelPath[] = $classPath;
    }

    public function addClass($class, $path)
    {
        self::$includeClassPath[strtolower($class)] = $path;
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $className The name of the class to load.
     * @return void
     */
    function loadClass($className)
    {
        $classPath  = str_replace(NS, DS, $className);

        if (false !== strpos($className, NS)) {// has namespace?
            $lastPos = strpos($className, NS);
            $mainNs = substr($className, 0, $lastPos);

            if (isset(self::$includePathNs[$mainNs])) {
                if (file_exists(self::$includePathNs[$mainNs] . $classPath . ".php")) {
                    require_once self::$includePathNs[$mainNs] . $classPath . ".php";
                    return true;
                } else {
                    return false;
                }
            }
        }

        if (isset(self::$includeClassPath[strtolower($className)]) && file_exists(self::$includeClassPath[strtolower($className)])) {
            require self::$includeClassPath[strtolower($className)];
        }

        foreach (self::$includeModelPath as $path) {
            if (file_exists($path.$className.".php")) {
                require $path.$className.".php";
                return true;
            } elseif (file_exists($path."om".DS.$className.".php")) {
                require $path."om".DS.$className.".php";
                return true;
            } elseif (file_exists($path."map".DS.$className.".php")) {
                require $path."map".DS.$className.".php";
                return true;
            }
        }

        foreach (self::$includePath as $path) {
            $filename = $path . $classPath . ".php";

            if (file_exists($filename)) {
                require $filename;
                return true;
            }
        }

        return false;
    }



}