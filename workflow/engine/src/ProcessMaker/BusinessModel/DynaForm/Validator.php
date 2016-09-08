<?php

namespace ProcessMaker\BusinessModel\DynaForm;

abstract class Validator implements ValidatorInterface
{

    protected $json;

    public function __construct($json)
    {
        $this->json = $json;
    }

}
