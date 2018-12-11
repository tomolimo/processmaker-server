<?php

class WsResponse
{
    /**
     * Status used from $status_code
     * 0 ID_COMMAND_EXECUTED_SUCCESSFULY
     *
     * 2 ID_USER_HAVENT_RIGHTS_SYSTEM
     * 3 ID_USER_NOT_REGISTERED
     * 4 ID_WRONG_PASS
     * 5 ID_USER_INACTIVE
     * 6 ID_INVALID_ROLE
     * 7 ID_USERNAME_ALREADY_EXISTS
     * 8 ID_USER_NOT_REGISTERED_GROUP
     * 9 ID_GROUP_NOT_REGISTERED_SYSTEM
     * 10 ID_ARRAY_VARIABLES_EMPTY
     * 11 ID_INVALID_PROCESS
     * 12 ID_NO_STARTING_TASK
     * 13 ID_MULTIPLE_STARTING_TASKS
     * 14 ID_TASK_INVALID_USER_NOT_ASSIGNED_TASK
     * 15 ID_ADMINISTRATOR_ROLE_CANT_CHANGED
     * 16 ID_CASE_DOES_NOT_EXIST
     * 17 ID_CASE_ASSIGNED_ANOTHER_USER
     * 18 ID_CASE_DELEGATION_ALREADY_CLOSED
     * 19 ID_CASE_IN_STATUS APP_TYPE
     * 20 ID_SPECIFY_DELEGATION_INDEX
     * 21 ID_CAN_NOT_ROUTE_CASE_USING_WEBSERVICES
     * 22 ID_TASK_DOES_NOT_HAVE_ROUTING_RULE
     * 23 ID_VARIABLES_PARAM_ZERO
     * 24 ID_VARIABLES_PARAM_NOT_ARRAY
     * 25 ID_USERNAME_REQUIRED
     * 26 ID_PASSWD_REQUIRED
     * 27 ID_MSG_ERROR_USR_FIRSTNAME
     * 28 ID_TEMPLATE_FILE_NOT_EXIST
     * 29 Email does not sent
     * 30 ID_TARGET_ORIGIN_USER_SAME
     * 31 ID_INVALID_ORIGIN_USER
     * 32 ID_CASE_NOT_OPEN
     * 33 ID_INVALID_CASE_DELEGATION_INDEX
     * 34 ID_TARGET_USER_DOES_NOT_HAVE_RIGHTS
     * 35 ID_TARGET_USER_DESTINATION_INVALID
     * 36 ID_CASE_COULD_NOT_REASSIGNED
     *
     * 100 Exception
    */
    public $status_code = 0;
    public $message = '';
    public $timestamp = '';

    /**
     * Function __construct
     * Constructor of the class
     *
     * @param string $status
     * @param string $message
     * @return void
     */
    function __construct ($status, $message)
    {
        $this->status_code = $status;
        $this->message = $message;
        $this->timestamp = date( 'Y-m-d H:i:s' );
    }

    /**
     * Function getPayloadString
     *
     * @param string $operation
     * @return string
     */
    function getPayloadString ($operation)
    {
        $res = "<$operation>\n";
        $res .= "<status_code>" . $this->status_code . "</status_code>";
        $res .= "<message>" . $this->message . "</message>";
        $res .= "<timestamp>" . $this->timestamp . "</timestamp>";
        //    $res .= "<array>" . $this->timestamp . "</array>";
        $res .= "<$operation>";
        return $res;
    }

    /**
     * Function getPayloadArray
     *
     * @return array
     */
    function getPayloadArray ()
    {
        return array ("status_code" => $this->status_code,'message' => $this->message,'timestamp' => $this->timestamp
        );
    }
}
