<?php
namespace ProcessMaker\Exception;

use ProcessMaker\Project;

class ProjectAlreadyExists extends \RuntimeException
{
    const EXCEPTION_CODE = 21;

    public function __construct(Project\Handler $obj, $name, $message = "", \Exception $previous = null) {
        $message = empty($message) ? sprintf("Project \"%s\" with name: %s, already exists.", get_class($obj), $name) : $message;

        parent::__construct($message, self::EXCEPTION_CODE, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}