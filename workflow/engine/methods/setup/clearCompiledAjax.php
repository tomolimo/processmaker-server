<?php
try {
    $response = new stdClass;
    if (isset( $_POST['javascriptCache'] ) || isset( $_POST['metadataCache'] ) || isset( $_POST['htmlCache'] )) {

        $msgLog = "";
        $msgLogAux = "";

        if (isset($_POST["javascriptCache"])) {
            G::rm_dir(PATH_C . "ExtJs");
            $response->javascript = true;

            $msgLog = $msgLog . "Javascript Cache";

            if (isset($_POST["metadataCache"])) {
                G::rm_dir(PATH_C . "xmlform");
                $response->xmlform = true;

                $msgLogAux = $msgLog;
                $msgLog = $msgLog . ", Forms Metadata Cache";

                if (isset($_POST["htmlCache"])) {
                    G::rm_dir(PATH_C . "smarty");
                    $response->smarty = true;

                    $msgLog = $msgLog . " and Forms Html Templates Cache.";
                } else {
                    $msgLog = $msgLogAux ." and Forms Metadata Cache.";
                }
            } else {
                if (isset($_POST["htmlCache"])) {
                    G::rm_dir(PATH_C . "smarty");
                    $response->smarty = true;

                    $msgLog = $msgLog . " and Forms Html Templates Cache.";
                } else {
                    $msgLog = $msgLog . ".";
                }
            }
        } else {
            if (isset($_POST["metadataCache"])) {
                G::rm_dir(PATH_C . "xmlform");
                $response->xmlform = true;

                $msgLog = $msgLog . "Forms Metadata Cache";

                if (isset($_POST["htmlCache"])) {
                    G::rm_dir(PATH_C . "smarty");
                    $response->smarty = true;

                    $msgLog = $msgLog . " and Forms Html Templates Cache.";
                } else {
                    $msgLog = $msgLog . ".";
                }
            } else {
                if (isset($_POST["htmlCache"])) {
                    G::rm_dir(PATH_C . "smarty");
                    $response->smarty = true;

                    $msgLog = $msgLog . "Forms Html Templates Cache.";
                }
            }
        }

        $response->success = true;

        G::auditLog("ClearCache", $msgLog);
    } else {
        $response->success = false;
    }
} catch (Exception $e) {
    $response->success = false;
    $response->message = $e->getMessage();
}
echo G::json_encode( $response );

