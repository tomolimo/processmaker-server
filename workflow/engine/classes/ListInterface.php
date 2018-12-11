<?php

/**
 * Interface for list of cases
 *
 */
interface ListInterface extends ListCreateUpdateInterface, ListAdditionalColumnsInterface, ListUserDisplayFormatInterface
{

    /**
     * This function add restriction in the query related to the filters.
     *
     * @param Criteria $criteria , must be contain only select of columns
     * @param array $filters
     * @param array $additionalColumns information about the new columns related to custom cases list
     *
     * @throws PropelException
     */
    public function loadFilters(&$criteria, $filters);

    /**
     * This function get the information in the corresponding cases list.
     *
     * @param string $usr_uid , must be show cases related to this user
     * @param array $filters for apply in the result
     * @param callable $callbackRecord
     *
     * @return array $data
     * @throws PropelException
     */
    public function loadList($usr_uid, $filters = [], callable $callbackRecord = null);

    /**
     * Returns the number of cases of a user.
     *
     * @param string $usrUid
     * @param array $filters
     *
     * @return int
     */
    public function getCountList($usrUid, $filters = []);
}
