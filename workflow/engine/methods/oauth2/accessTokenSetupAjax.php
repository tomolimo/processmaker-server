<?php
$option = (isset($_POST["option"]))? $_POST["option"] : "";

$response = array();

switch ($option) {
    case "UPD":
        $oauthAccessTokenId = $_POST["oauthAccessTokenId"];
        $scope = $_POST["scope"];

        try {
            $arrayData = array(
                "ACCESS_TOKEN" => $oauthAccessTokenId,
                "SCOPE" => $scope
            );

            $oatoken = new OauthAccessTokens();
            $result = $oatoken->update($arrayData);

            $response["status"] = "OK";
        } catch (Exception $e) {
            $response["status"]  = "ERROR";
            $response["message"] = $e->getMessage();
        }
        break;
    case "DEL":
        $oauthAccessTokenId = $_POST["oauthAccessTokenId"];

        try {
            $oatoken = new OauthAccessTokens();
            $result = $oatoken->remove($oauthAccessTokenId);

            $response["status"] = "OK";
        } catch (Exception $e) {
            $response["status"]  = "ERROR";
            $response["message"] = $e->getMessage();
        }
        break;
    case "LST":
        $pageSize = $_POST["pageSize"];

        $sortField = (isset($_POST["sort"]))? $_POST["sort"]: "";
        $sortDir   = (isset($_POST["dir"]))? $_POST["dir"]: "";
        $start = (isset($_POST["start"]))? $_POST["start"]: 0;
        $limit = (isset($_POST["limit"]))? $_POST["limit"]: $pageSize;

        try {
            $oatoken = new OauthAccessTokens();
            $result = $oatoken->getAll(array("USER_ID" => $_SESSION["USER_LOGGED"]), $sortField, $sortDir, $start, $limit);

            $response["status"]  = "OK";
            $response["success"] = true;
            $response["resultTotal"] = $result["numRecTotal"];
            $response["resultRoot"]  = $result["data"];
        } catch (Exception $e) {
            $response["status"]  = "ERROR";
            $response["message"] = $e->getMessage();
        }
        break;
}

echo G::json_encode($response);

