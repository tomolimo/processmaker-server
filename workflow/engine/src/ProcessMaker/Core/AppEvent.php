<?php

namespace ProcessMaker\Core;

class AppEvent
{
    /**
     * Identify XML Form type elements
     */
    const XMLFORM_RENDER = 0;

    /**
     * Identify login action
     */
    const LOGIN = 1;

    /**
     * Identify scripts with no login
     */
    const SCRIPTS_WITH_NO_LOGIN = 2;

    /**
     * Represents the AppEvent object.
     * 
     * @var object 
     */
    private static $appEvent = null;

    /**
     * List of closure elements.
     * 
     * @var array 
     */
    private $callbacks = [];

    /**
     * Represents the html string.
     * 
     * @var string 
     */
    private $html = null;

    /**
     * Get an AppEvent object.
     * 
     * @return object
     */
    public static function getAppEvent()
    {
        if (self::$appEvent === null) {
            self::$appEvent = new AppEvent();
        }
        return self::$appEvent;
    }

    /**
     * Process all closure elements.
     * 
     * @param int $type
     * @param object $object
     * @return $this
     */
    public function dispatch($type, &$object)
    {
        foreach ($this->callbacks as $callback) {
            $callback($type, $object, $this);
        }
        return $this;
    }

    /**
     * Add a closure function.
     * 
     * @param function $callback
     * @return $this
     */
    public function addEvent($callback)
    {
        if (is_callable($callback)) {
            $this->callbacks[] = $callback;
        }
        return $this;
    }

    /**
     * Get html value.
     * 
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Set html value.
     * 
     * @param string $html
     * @return $this
     */
    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }
}
