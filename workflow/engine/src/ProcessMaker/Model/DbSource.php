<?php

namespace ProcessMaker\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DbSource
 * @package ProcessMaker\Model
 *
 * Represents an external database connection. Each DB Source is related to a owner process.
 */
class DbSource extends Model
{
    // Set our table name
    protected $table = 'DB_SOURCE';
    // We do not store timestamps
    public $timestamps = false;

}