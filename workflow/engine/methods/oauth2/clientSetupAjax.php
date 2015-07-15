<?php
$option = (isset($_POST["option"]))? $_POST["option"] : "";

$response = array();

switch ($option) {
    case "INS":
        $name = $_POST["name"];
        $description = $_POST["description"];
        $webSite     = $_POST["webSite"];
        $redirectUri = $_POST["redirectUri"];

        try {
            $arrayData = array(
                //"CLIENT_ID"   => "",
                "CLIENT_NAME" => $name,
                "CLIENT_DESCRIPTION" => $description,
                "CLIENT_WEBSITE" => $webSite,
                "REDIRECT_URI"   => $redirectUri,
                "USR_UID" => $_SESSION["USER_LOGGED"]
            );

            $oclient = new OauthClients();
            $result = $oclient->create($arrayData);

            $response["status"] = "OK";
            $response["data"]   = $result;
        } catch (Exception $e) {
            $response["status"]  = "ERROR";
            $response["message"] = $e->getMessage();
        }
        break;
    case "UPD":
        $oauthClientId = $_POST["oauthClientId"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $webSite     = $_POST["webSite"];
        $redirectUri = $_POST["redirectUri"];

        try {
            $arrayData = array(
                "CLIENT_ID"   => $oauthClientId,
                "CLIENT_NAME" => $name,
                "CLIENT_DESCRIPTION" => $description,
                "CLIENT_WEBSITE" => $webSite,
                "REDIRECT_URI"   => $redirectUri,
                "USR_UID" => $_SESSION["USER_LOGGED"]
            );

            $oclient = new OauthClients();
            $result = $oclient->update($arrayData);

            $response["status"] = "OK";
        } catch (Exception $e) {
            $response["status"]  = "ERROR";
            $response["message"] = $e->getMessage();
        }
        break;
    case "DEL":
        $oauthClientId = $_POST["oauthClientId"];

        try {
            $oclient = new OauthClients();
            $result = $oclient->remove($oauthClientId);

            $response["status"] = "OK";
        } catch (Exception $e) {
            $response["status"]  = "ERROR";
            $response["message"] = $e->getMessage();
        }
        break;
    case "LST":
        $pageSize = $_POST["pageSize"];
        $search = $_POST["search"];

        $sortField = (isset($_POST["sort"]))? $_POST["sort"]: "";
        $sortDir   = (isset($_POST["dir"]))? $_POST["dir"]: "";
        $start = (isset($_POST["start"]))? $_POST["start"]: 0;
        $limit = (isset($_POST["limit"]))? $_POST["limit"]: $pageSize;

        try {
            $oclient = new OauthClients();
            $result = $oclient->getAll(array("USR_UID" => $_SESSION["USER_LOGGED"], "SEARCH" => $search), $sortField, $sortDir, $start, $limit);

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

