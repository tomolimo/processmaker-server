<?php

namespace ProcessMaker\BusinessModel\DynaForm;

interface ValidatorInterface
{

    public function validatePost(&$post);
}
