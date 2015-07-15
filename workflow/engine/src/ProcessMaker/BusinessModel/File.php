<?php
namespace ProcessMaker\BusinessModel;

use \G;

class File
{
    /**
     * Upload file
     *
     * @param array  $aData
     */
    public function uploadFile($aData)
    {
        try {
            if ($_FILES['file_content']['error'] != 1) {
                if ($_FILES['file_content']['tmp_name'] != '') {
                    $aAux = explode('.', $_FILES['file_content']['name']);
                    $content = file_get_contents($_FILES['file_content']['tmp_name']);
                    $result = array('file_content' => $content);

                    \G::uploadFile($_FILES['file_content']['tmp_name'], PATH_DOCUMENT.'/upload/', $_FILES['file_content']['name']);
                }
            } else {
                $result->success = false;
                $result->fileError = true;
                throw (new \Exception($result));
            }
            return $result;
       
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

