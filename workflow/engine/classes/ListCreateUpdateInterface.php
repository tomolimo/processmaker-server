<?php

/**
 * The list can create and update records.
 *
 */
interface ListCreateUpdateInterface
{

    /**
     * Create an application record into list.
     *
     * @param type $data
     *
     * @throws Exception
     */
    public function create($data);

    /**
     * Update an application record from list.
     *
     * @param type $data
     *
     * @throws Exception
     */
    public function update($data);
}
