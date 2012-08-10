<?php
class Author
{
    public $dp;

    static $FIELDS = array('name', 'email');

    function __construct()
    {
    }

    function get($id=NULL)
    {
        return is_null($id) ? 'GET: getting all records' : "Getting a record with id: $id";
    }

    function post($request_data=NULL)
    {
        return 'POST: posting 1';
    }

    function put($id=NULL, $request_data=NULL)
    {
        return 'PUT: update 1';
    }

    function delete($id=NULL) {
        return 'DELETE: deleting '.$id;
    }
}
