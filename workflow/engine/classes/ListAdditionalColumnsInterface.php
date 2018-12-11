<?php

/**
 * The list implement additional columns.
 *
 */
interface ListAdditionalColumnsInterface
{

    /**
     * Get the $additionalClassName value.
     *
     * @return string
     */
    public function getAdditionalClassName();

    /**
     * Set the value of $additionalClassName.
     *
     * @param string $v new value
     * @return void
     */
    public function setAdditionalClassName($v);
}
