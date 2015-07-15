<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

class Test2 extends Api
{

    public function hello2()
    {
        return 'Hello #2';
    }

    /**
     * @url GET /getHello
     */
    public function helloworld($param = '')
    {
        return 'Greetings, from a overridden url ' . $param;
    }

    /**
     * @url GET /sample/other/large/:name
     */
    public function sampleOther($name)
    {
        return 'Name: ' . $name;
    }
}

