<?php

namespace ProcessMaker\BusinessModel\DynaForm;

class ValidatorCheckGroup extends Validator
{

    public function validatePost(&$post)
    {
        $json = $this->json;
        if ($json === null) {
            return;
        }
        if (!isset($post[$json->variable])) {
            $post[$json->variable] = array();
            $post[$json->variable . "_label"] = \G::json_encode(array());
        }
    }

}
