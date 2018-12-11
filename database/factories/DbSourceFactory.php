<?php
/**
 * Model factory for a process
 */
use Faker\Generator as Faker;

$factory->define(\ProcessMaker\Model\DbSource::class, function(Faker $faker) {

    /**
     * @todo Determine if we need more base columns populated
     */
    $dbName = $faker->word;
    return [
        'DBS_UID' => G::generateUniqueID(),
        'PRO_UID' => function() {
            return factory(\ProcessMaker\Model\Process::class)->create()->PRO_UID;
        },
        'DBS_TYPE' => 'mysql',
        'DBS_SERVER' => $faker->localIpv4,
        'DBS_DATABASE_NAME' => $faker->word,
        'DBS_USERNAME' => $faker->userName,
        /**
         * @todo WHY figure out there's a magic value to the encryption here
         */
        'DBS_PASSWORD' => \G::encrypt( $faker->password, $dbName) . "_2NnV3ujj3w",
        'DBS_PORT' => $faker->numberBetween(1000, 9000),
        'DBS_ENCODE' => 'utf8', // @todo Perhaps grab this from our definitions in DbConnections
        'DBS_CONNECTION_TYPE' => 'NORMAL', // @todo Determine what this value means
        'DBS_TNS' => null // @todo Determine what this value means
    ];
});