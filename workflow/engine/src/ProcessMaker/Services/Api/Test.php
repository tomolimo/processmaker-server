<?php
namespace ProcessMaker\Services\Api;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

class Test extends Api
{
    protected $data = array();

    public function __construct()
    {
        if (! isset($_SESSION['__rest_tmp__'])) {
            $this->data[1] = array(
                'id' => '1',
                'name' => 'John',
                'lastname' => 'Doe',
                'age' => '27'
            );
            $this->saveData();
        } else {
            $this->loadData();
        }
    }

    public function index()
    {
        return array_values($this->data);
    }

    public function get($id)
    {
        if (array_key_exists($id, $this->data)) {
            return $this->data[$id];
        }

        throw new RestException(400, "GET: Record not found. Record with id: $id does not exist!");
    }

    public function post($request_data = null)
    {
        $id = count($this->data) + 1;
        $this->data[$id] = array(
            'id' => $id,
            'name' => '',
            'lastname' => '',
            'age' => ''
        );

        if (array_key_exists('name', $request_data)) {
            $this->data[$id]['name'] = $request_data['name'];
        }
        if (array_key_exists('lastname', $request_data)) {
            $this->data[$id]['lastname'] = $request_data['lastname'];
        }
        if (array_key_exists('age', $request_data)) {
            $this->data[$id]['age'] = $request_data['age'];
        }

        $this->saveData();

        return $this->data[$id];
    }

    public function put($id, $request_data = null)
    {
        if (array_key_exists($id, $this->data)) {
            if (array_key_exists('name', $request_data)) {
                $this->data[$id]['name'] = $request_data['name'];
            }
            if (array_key_exists('lastname', $request_data)) {
                $this->data[$id]['lastname'] = $request_data['lastname'];
            }
            if (array_key_exists('age', $request_data)) {
                $this->data[$id]['age'] = $request_data['age'];
            }
            $this->saveData();

            return $this->data[$id];
        } else {
            throw new RestException(400, "PUT: Record not found. Record with id: $id does not exist!");
        }
    }

    public function delete($id)
    {
        if (array_key_exists($id, $this->data)) {
            $row = $this->data[$id];
            unset($this->data[$id]);
            $this->saveData();

            return $row;
        } else {
            throw new RestException(400, "DELETE: Record not found. Record with id: $id does not exist!");
        }
    }

    /* Private methods */
    private function loadData()
    {
        $this->data = $_SESSION['__rest_tmp__'];
    }

    private function saveData()
    {
        $_SESSION['__rest_tmp__'] = $this->data;
    }
}

