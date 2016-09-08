<?php
namespace ProcessMaker\Util;

class System
{
    /**
     * Get Time Zone
     *
     * @return string Return Time Zone
     */
    public static function getTimeZone()
    {
        try {
            $arraySystemConfiguration = \System::getSystemConfiguration('', '', SYS_SYS);

            //Return
            return $arraySystemConfiguration['time_zone'];
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

