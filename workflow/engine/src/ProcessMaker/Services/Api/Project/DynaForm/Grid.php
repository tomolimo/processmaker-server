<?php
namespace ProcessMaker\Services\Api\Project\DynaForm;

use \ProcessMaker\Services\Api;
use \Luracast\Restler\RestException;

/**
 * Project\DynaForm\Grid Api Controller
 *
 * @protected
 */
class Grid extends Api
{
    private $grid;

    /**
     * Constructor of the class
     *
     * @return  void
     */
    public function __construct()
    {
        try {
            $this->grid = new \ProcessMaker\BusinessModel\DynaForm\Grid();
            $this->grid->setRunningWorkflow(false);
            $this->grid->setArrayVariableNameForException([
                '$projectUid'  => 'prj_uid',
                '$dynaFormUid' => 'dyn_uid',
                '$gridName'    => 'grd_name',
                '$fieldId'     => 'fld_id'
            ]);

        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET /:prj_uid/dynaform/:dyn_uid/grid/:grd_name/field-definitions
     *
     * @param string $prj_uid  {@min 32}{@max 32}
     * @param string $dyn_uid  {@min 32}{@max 32}
     * @param string $grd_name
     */
    public function doGetDynaFormGridFieldDefinitions($prj_uid, $dyn_uid, $grd_name)
    {
        try {
            $response = $this->grid->getGridFieldDefinitions($dyn_uid, $grd_name);

            return $response;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }

    /**
     * @url GET /:prj_uid/dynaform/:dyn_uid/grid/:grd_name/field-definition/:fld_id
     *
     * @param string $prj_uid  {@min 32}{@max 32}
     * @param string $dyn_uid  {@min 32}{@max 32}
     * @param string $grd_name
     * @param string $fld_id
     */
    public function doGetDynaFormGridFieldDefinition($prj_uid, $dyn_uid, $grd_name, $fld_id)
    {
        try {
            $response = $this->grid->getGridFieldDefinition($dyn_uid, $grd_name, $fld_id);

            return $response;
        } catch (\Exception $e) {
            throw new RestException(Api::STAT_APP_EXCEPTION, $e->getMessage());
        }
    }
}

