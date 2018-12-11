<?php

/**
 * The list implements user name formated.
 *
 */
interface ListUserDisplayFormatInterface
{

    /**
     * Get the $userDisplayFormat value.
     *
     * @return string
     */
    public function getUserDisplayFormat();

    /**
     * Set the value of $userDisplayFormat.
     *
     * @param string $v new value
     * @return void
     */
    public function setUserDisplayFormat($v);
}
