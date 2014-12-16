<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

class Test3 extends Api
{

    public function hello3()
    {
        return 'Hello #3';
    }

    /**
     * @status 201
     */
    public function post2()
    {
        return array('success' => true);
    }
}

