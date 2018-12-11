<?php

$action = $_REQUEST['action'];
unset($_REQUEST['action']);

global $RBAC;
$RBAC->allows(basename(dirname(__FILE__)) . PATH_SEP . basename(__FILE__), $action);

$ajax = new Ajax();
$ajax->$action($_REQUEST);

class Ajax
{

    /**
     * Get the list related to the translation
     *
     * @param array $params
     *
     * @return void
    */
    public function getList($params)
    {
        $search = isset($params['search']) ? $params['search'] : null;
        $params['dateFrom'] = str_replace('T00:00:00', '', $params['dateFrom']);
        $params['dateTo'] = str_replace('T00:00:00', '', $params['dateTo']);
        $result = Translation::getAll('en', $params['start'], $params['limit'], $search, $params['dateFrom'], $params['dateTo']);

        echo G::json_encode($result);
    }

    /**
     * Save translation
     *
     * @return void
     */
    public function save()
    {
        try {
            $id = $_POST['id'];
            $label = preg_replace("[\n|\r|\n\r]", ' ', $_POST['label']);

            $res = Translation::addTranslation('LABEL', $id, 'en', $label);
            $result = new stdClass();
            if ($res['codError'] < 0) {
                $result->success = false;
                $result->msg = $res['message'];
            } else {
                $result->success = true;
                $result->msg = 'Label ' . htmlspecialchars($id) . ' saved Successfully!';
            }
        } catch (Exception $e) {
            $result->success = false;
            $result->msg = htmlspecialchars($e->getMessage());
        }
        print G::json_encode($result);
    }

    /**
     * Delete translation
     *
     * @return void
     */
    public function delete()
    {
        $ids = explode(',', $_POST['IDS']);
        $category = 'LABEL';
        $result = new stdClass();

        try {
            foreach ($ids as $id) {
                $tr = TranslationPeer::retrieveByPK($category, $id, 'en');
                if ((is_object($tr) && get_class($tr) == 'Translation')) {
                    $tr->delete();
                }
            }

            $result->success = true;
            $result->msg = 'Deleted Successfully!';
        } catch (Exception $e) {
            $result->success = false;
            $result->msg = htmlspecialchars($e->getMessage());
        }
        print G::json_encode($result);
    }

    /**
     * Rebuild translation
     *
     * @return void
     */
    public function rebuild()
    {
        $result = new stdClass();
        try {
            $t = new Translation();
            $result = Translation::generateFileTranslation('en');
            $result['success'] = true;
        } catch (Exception $e) {
            $result->success = false;
            $result->msg = htmlspecialchars($e->getMessage());
        }
        print G::json_encode($result);
    }
}

