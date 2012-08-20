<?php

class Services_Rest_Task
{
    /**
     * Implementation for 'GET' method for Rest API
     *
     * @param  mixed $tasUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function get($tasUid=null)
    {
        $result = array();
        try {
            if (func_num_args() == 0) {
                $criteria = new Criteria('workflow');

                $criteria->addSelectColumn(TaskPeer::PRO_UID);
                $criteria->addSelectColumn(TaskPeer::TAS_UID);
                $criteria->addSelectColumn(TaskPeer::TAS_TYPE);
                $criteria->addSelectColumn(TaskPeer::TAS_DURATION);
                $criteria->addSelectColumn(TaskPeer::TAS_DELAY_TYPE);
                $criteria->addSelectColumn(TaskPeer::TAS_TEMPORIZER);
                $criteria->addSelectColumn(TaskPeer::TAS_TYPE_DAY);
                $criteria->addSelectColumn(TaskPeer::TAS_TIMEUNIT);
                $criteria->addSelectColumn(TaskPeer::TAS_ALERT);
                $criteria->addSelectColumn(TaskPeer::TAS_PRIORITY_VARIABLE);
                $criteria->addSelectColumn(TaskPeer::TAS_ASSIGN_TYPE);
                $criteria->addSelectColumn(TaskPeer::TAS_ASSIGN_VARIABLE);
                $criteria->addSelectColumn(TaskPeer::TAS_MI_INSTANCE_VARIABLE);
                $criteria->addSelectColumn(TaskPeer::TAS_MI_COMPLETE_VARIABLE);
                $criteria->addSelectColumn(TaskPeer::TAS_ASSIGN_LOCATION);
                $criteria->addSelectColumn(TaskPeer::TAS_ASSIGN_LOCATION_ADHOC);
                $criteria->addSelectColumn(TaskPeer::TAS_TRANSFER_FLY);
                $criteria->addSelectColumn(TaskPeer::TAS_LAST_ASSIGNED);
                $criteria->addSelectColumn(TaskPeer::TAS_USER);
                $criteria->addSelectColumn(TaskPeer::TAS_CAN_UPLOAD);
                $criteria->addSelectColumn(TaskPeer::TAS_VIEW_UPLOAD);
                $criteria->addSelectColumn(TaskPeer::TAS_VIEW_ADDITIONAL_DOCUMENTATION);
                $criteria->addSelectColumn(TaskPeer::TAS_CAN_CANCEL);
                $criteria->addSelectColumn(TaskPeer::TAS_OWNER_APP);
                $criteria->addSelectColumn(TaskPeer::STG_UID);
                $criteria->addSelectColumn(TaskPeer::TAS_CAN_PAUSE);
                $criteria->addSelectColumn(TaskPeer::TAS_CAN_SEND_MESSAGE);
                $criteria->addSelectColumn(TaskPeer::TAS_CAN_DELETE_DOCS);
                $criteria->addSelectColumn(TaskPeer::TAS_SELF_SERVICE);
                $criteria->addSelectColumn(TaskPeer::TAS_START);
                $criteria->addSelectColumn(TaskPeer::TAS_TO_LAST_USER);
                $criteria->addSelectColumn(TaskPeer::TAS_SEND_LAST_EMAIL);
                $criteria->addSelectColumn(TaskPeer::TAS_DERIVATION);
                $criteria->addSelectColumn(TaskPeer::TAS_POSX);
                $criteria->addSelectColumn(TaskPeer::TAS_POSY);
                $criteria->addSelectColumn(TaskPeer::TAS_WIDTH);
                $criteria->addSelectColumn(TaskPeer::TAS_HEIGHT);
                $criteria->addSelectColumn(TaskPeer::TAS_COLOR);
                $criteria->addSelectColumn(TaskPeer::TAS_EVN_UID);
                $criteria->addSelectColumn(TaskPeer::TAS_BOUNDARY);
                $criteria->addSelectColumn(TaskPeer::TAS_DERIVATION_SCREEN_TPL);
                
                $dataset = AppEventPeer::doSelectRS($criteria);
                $dataset->setFetchmode(ResultSet::FETCHMODE_ASSOC);

                while ($dataset->next()) {
                    $result[] = $dataset->getRow();
                }
            } else {
                $record = TaskPeer::retrieveByPK($tasUid);
                $result = $record->toArray(BasePeer::TYPE_FIELDNAME);
            }
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
        
        return $result;
    }

    /**
     * Implementation for 'POST' method for Rest API
     *
     * @param  mixed $tasUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function post($proUid, $tasUid, $tasType, $tasDuration, $tasDelayType, $tasTemporizer, $tasTypeDay, $tasTimeunit, $tasAlert, $tasPriorityVariable, $tasAssignType, $tasAssignVariable, $tasMiInstanceVariable, $tasMiCompleteVariable, $tasAssignLocation, $tasAssignLocationAdhoc, $tasTransferFly, $tasLastAssigned, $tasUser, $tasCanUpload, $tasViewUpload, $tasViewAdditionalDocumentation, $tasCanCancel, $tasOwnerApp, $stgUid, $tasCanPause, $tasCanSendMessage, $tasCanDeleteDocs, $tasSelfService, $tasStart, $tasToLastUser, $tasSendLastEmail, $tasDerivation, $tasPosx, $tasPosy, $tasWidth, $tasHeight, $tasColor, $tasEvnUid, $tasBoundary, $tasDerivationScreenTpl)
    {
        try {
            $result = array();
            $obj = new Task();

            $obj->setProUid($proUid);
            $obj->setTasUid($tasUid);
            $obj->setTasType($tasType);
            $obj->setTasDuration($tasDuration);
            $obj->setTasDelayType($tasDelayType);
            $obj->setTasTemporizer($tasTemporizer);
            $obj->setTasTypeDay($tasTypeDay);
            $obj->setTasTimeunit($tasTimeunit);
            $obj->setTasAlert($tasAlert);
            $obj->setTasPriorityVariable($tasPriorityVariable);
            $obj->setTasAssignType($tasAssignType);
            $obj->setTasAssignVariable($tasAssignVariable);
            $obj->setTasMiInstanceVariable($tasMiInstanceVariable);
            $obj->setTasMiCompleteVariable($tasMiCompleteVariable);
            $obj->setTasAssignLocation($tasAssignLocation);
            $obj->setTasAssignLocationAdhoc($tasAssignLocationAdhoc);
            $obj->setTasTransferFly($tasTransferFly);
            $obj->setTasLastAssigned($tasLastAssigned);
            $obj->setTasUser($tasUser);
            $obj->setTasCanUpload($tasCanUpload);
            $obj->setTasViewUpload($tasViewUpload);
            $obj->setTasViewAdditionalDocumentation($tasViewAdditionalDocumentation);
            $obj->setTasCanCancel($tasCanCancel);
            $obj->setTasOwnerApp($tasOwnerApp);
            $obj->setStgUid($stgUid);
            $obj->setTasCanPause($tasCanPause);
            $obj->setTasCanSendMessage($tasCanSendMessage);
            $obj->setTasCanDeleteDocs($tasCanDeleteDocs);
            $obj->setTasSelfService($tasSelfService);
            $obj->setTasStart($tasStart);
            $obj->setTasToLastUser($tasToLastUser);
            $obj->setTasSendLastEmail($tasSendLastEmail);
            $obj->setTasDerivation($tasDerivation);
            $obj->setTasPosx($tasPosx);
            $obj->setTasPosy($tasPosy);
            $obj->setTasWidth($tasWidth);
            $obj->setTasHeight($tasHeight);
            $obj->setTasColor($tasColor);
            $obj->setTasEvnUid($tasEvnUid);
            $obj->setTasBoundary($tasBoundary);
            $obj->setTasDerivationScreenTpl($tasDerivationScreenTpl);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'PUT' method for Rest API
     *
     * @param  mixed $tasUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function put($proUid, $tasUid, $tasType, $tasDuration, $tasDelayType, $tasTemporizer, $tasTypeDay, $tasTimeunit, $tasAlert, $tasPriorityVariable, $tasAssignType, $tasAssignVariable, $tasMiInstanceVariable, $tasMiCompleteVariable, $tasAssignLocation, $tasAssignLocationAdhoc, $tasTransferFly, $tasLastAssigned, $tasUser, $tasCanUpload, $tasViewUpload, $tasViewAdditionalDocumentation, $tasCanCancel, $tasOwnerApp, $stgUid, $tasCanPause, $tasCanSendMessage, $tasCanDeleteDocs, $tasSelfService, $tasStart, $tasToLastUser, $tasSendLastEmail, $tasDerivation, $tasPosx, $tasPosy, $tasWidth, $tasHeight, $tasColor, $tasEvnUid, $tasBoundary, $tasDerivationScreenTpl)
    {
        try {
            $obj = TaskPeer::retrieveByPK($tasUid);

            $obj->setProUid($proUid);
            $obj->setTasType($tasType);
            $obj->setTasDuration($tasDuration);
            $obj->setTasDelayType($tasDelayType);
            $obj->setTasTemporizer($tasTemporizer);
            $obj->setTasTypeDay($tasTypeDay);
            $obj->setTasTimeunit($tasTimeunit);
            $obj->setTasAlert($tasAlert);
            $obj->setTasPriorityVariable($tasPriorityVariable);
            $obj->setTasAssignType($tasAssignType);
            $obj->setTasAssignVariable($tasAssignVariable);
            $obj->setTasMiInstanceVariable($tasMiInstanceVariable);
            $obj->setTasMiCompleteVariable($tasMiCompleteVariable);
            $obj->setTasAssignLocation($tasAssignLocation);
            $obj->setTasAssignLocationAdhoc($tasAssignLocationAdhoc);
            $obj->setTasTransferFly($tasTransferFly);
            $obj->setTasLastAssigned($tasLastAssigned);
            $obj->setTasUser($tasUser);
            $obj->setTasCanUpload($tasCanUpload);
            $obj->setTasViewUpload($tasViewUpload);
            $obj->setTasViewAdditionalDocumentation($tasViewAdditionalDocumentation);
            $obj->setTasCanCancel($tasCanCancel);
            $obj->setTasOwnerApp($tasOwnerApp);
            $obj->setStgUid($stgUid);
            $obj->setTasCanPause($tasCanPause);
            $obj->setTasCanSendMessage($tasCanSendMessage);
            $obj->setTasCanDeleteDocs($tasCanDeleteDocs);
            $obj->setTasSelfService($tasSelfService);
            $obj->setTasStart($tasStart);
            $obj->setTasToLastUser($tasToLastUser);
            $obj->setTasSendLastEmail($tasSendLastEmail);
            $obj->setTasDerivation($tasDerivation);
            $obj->setTasPosx($tasPosx);
            $obj->setTasPosy($tasPosy);
            $obj->setTasWidth($tasWidth);
            $obj->setTasHeight($tasHeight);
            $obj->setTasColor($tasColor);
            $obj->setTasEvnUid($tasEvnUid);
            $obj->setTasBoundary($tasBoundary);
            $obj->setTasDerivationScreenTpl($tasDerivationScreenTpl);
            
            $obj->save();
        } catch (Exception $e) {
            throw new RestException(412, $e->getMessage());
        }
    }

    /**
     * Implementation for 'DELETE' method for Rest API
     *
     * @param  mixed $tasUid Primary key
     *
     * @return array $result Returns array within multiple records or a single record depending if
     *                       a single selection was requested passing id(s) as param
     */
    protected function delete($tasUid)
    {
        $conn = Propel::getConnection(TaskPeer::DATABASE_NAME);
        
        try {
            $conn->begin();
        
            $obj = TaskPeer::retrieveByPK($tasUid);
            if (! is_object($obj)) {
                throw new RestException(412, 'Record does not exist.');
            }
            $obj->delete();
        
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            throw new RestException(412, $e->getMessage());
        }
    }


}
