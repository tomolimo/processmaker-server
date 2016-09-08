<?php
$response = [];

try {
    $option  = $_POST['option'];
    $roleUid = $_POST['roleUid'];

    $pageSize = $_POST['pageSize'];
    $filter = $_POST['filter'];

    $sortField = (isset($_POST['sort']))? $_POST['sort']: 'USR_FIRSTNAME';
    $sortDir   = (isset($_POST['dir']))? $_POST['dir']: 'ASC';
    $start = (isset($_POST['start']))? $_POST['start']: 0;
    $limit = (isset($_POST['limit']))? $_POST['limit']: $pageSize;

    $roleUser = new \ProcessMaker\BusinessModel\Role\User();

    $result = $roleUser->getUsers(
        $roleUid, $option, ['filter' => $filter, 'filterOption' => ''], $sortField, $sortDir, $start, $limit
    );

    $response['status']  = 'OK';
    $response['success'] = true;
    $response['resultTotal'] = $result['total'];
    $response['resultRoot']  = $result['data'];
} catch (Exception $e) {
    $response['status']  = 'ERROR';
    $response['message'] = $e->getMessage();
}

echo G::json_encode($response);

