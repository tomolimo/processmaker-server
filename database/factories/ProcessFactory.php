<?php
/**
 * Model factory for a process
 */
use Faker\Generator as Faker;

$factory->define(\ProcessMaker\Model\Process::class, function(Faker $faker) {

    /**
     * @todo Determine if we need more base columns populated
     */
    return [
        'PRO_UID' => G::generateUniqueID(),
        'PRO_TITLE' => $faker->sentence(3),
        'PRO_DESCRIPTION' => $faker->paragraph(3)
    ];
});