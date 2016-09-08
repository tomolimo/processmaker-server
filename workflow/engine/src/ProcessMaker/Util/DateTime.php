<?php
namespace ProcessMaker\Util;

class DateTime
{
    const ISO8601 = 'Y-m-d\TH:i:sP';

    const REGEXPDATE = '[1-9]\d{3}\-(?:0[1-9]|1[0-2])\-(?:0[1-9]|[12][0-9]|3[01])';
    const REGEXPTIME = '(?:[0-1]\d|2[0-3])\:[0-5]\d\:[0-5]\d';

    /**
     * Get Time Zone Offset by Time Zone ID
     *
     * @param string $timeZoneId Time Zone ID
     *
     * @return int Return the Time Zone Offset; false otherwise
     */
    public function getTimeZoneOffsetByTimeZoneId($timeZoneId)
    {
        try {
            $dt = new \DateTime(null, new \DateTimeZone($timeZoneId));

            //Return
            return $dt->getOffset();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Time Zone ID by Time Zone Offset
     *
     * @param int  $offset         Time Zone Offset
     * @param bool $flagAllResults Flag which sets include all the results
     *
     * @return mixed Return the Time Zone ID; UTC ID otherwise
     */
    public function getTimeZoneIdByTimeZoneOffset($offset, $flagAllResults = true)
    {
        try {
            $timeZoneId = ($flagAllResults)? [] : '';

            foreach (\DateTimeZone::listIdentifiers() as $value) {
                $timeZoneOffset = $this->getTimeZoneOffsetByTimeZoneId($value);

                if ($timeZoneOffset !== false && $timeZoneOffset == $offset) {
                    if ($flagAllResults) {
                        $timeZoneId[] = $value;
                    } else {
                        $timeZoneId = $value;
                        break;
                    }
                }
            }

            $timeZoneId = (!empty($timeZoneId))? $timeZoneId : (($flagAllResults)? ['UTC'] : 'UTC');

            //Return
            return $timeZoneId;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get Time Zone ID by UTC Offset
     *
     * @param string $utcOffset UTC Offset
     *
     * @return string Return the Time Zone ID; UTC ID otherwise
     */
    public function getTimeZoneIdByUtcOffset($utcOffset)
    {
        try {
            if (preg_match('/^([\+\-])(\d{2}):(\d{2})$/', $utcOffset, $arrayMatch)) {
                $sign = $arrayMatch[1];
                $h = (int)($arrayMatch[2]);
                $m = (int)($arrayMatch[3]);
            } else {
                //Return
                return 'UTC';
            }

            $offset = (($sign == '+')? '' : '-') . (($h * 60 * 60) + ($m * 60)); //Convert UTC Offset to seconds

            //Return
            return $this->getTimeZoneIdByTimeZoneOffset((int)($offset), false);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get UTC Offset by Time Zone Offset
     *
     * @param int $offset Time Zone Offset
     *
     * @return string Return the UTC Offset
     */
    public function getUtcOffsetByTimeZoneOffset($offset)
    {
        try {
            $sign = ($offset >= 0)? '+' : '-';

            $offset = abs($offset) / 60; //Convert seconds to minutes

            $h = floor($offset / 60);
            $m = $offset - ($h * 60);

            //Return
            return $sign . sprintf('%02d:%02d', $h, $m);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Convert date from Time Zone to Time Zone
     *
     * @param string $date         Date
     * @param string $fromTimeZone Time Zone source
     * @param string $toTimeZone   Time Zone to convert
     * @param string $format       Format to return date
     *
     * @return string Return date
     */
    public function convertTimeZone($date, $fromTimeZone, $toTimeZone, $format = 'Y-m-d H:i:s')
    {
        try {
            $dt = new \DateTime($date, new \DateTimeZone($fromTimeZone)); //From Time Zone
            $dt->setTimeZone(new \DateTimeZone($toTimeZone)); //To Time Zone

            //Return
            return $dt->format($format);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Convert ISO-8601 to Time Zone
     *
     * @param string $dateIso8601 Date
     * @param string $toTimeZone  Time Zone to convert
     * @param string $format      Format to return date
     *
     * @return string Return date
     */
    public function convertIso8601ToTimeZone($dateIso8601, $toTimeZone, $format = 'Y-m-d H:i:s')
    {
        try {
            $fromTimeZone = 'UTC';

            if (preg_match('/^.+([\+\-]\d{2}:\d{2})$/', $dateIso8601, $arrayMatch)) {
                $fromTimeZone = $this->getTimeZoneIdByUtcOffset($arrayMatch[1]);
            }

            $dt = \DateTime::createFromFormat(self::ISO8601, $dateIso8601, new \DateTimeZone($fromTimeZone)); //From ISO-8601
            $dt->setTimeZone(new \DateTimeZone($toTimeZone)); //To Time Zone

            //Return
            return $dt->format($format);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Convert ISO-8601/datetime/array-ISO-8601-datetime-data to Time Zone
     *
     * @param mixed  $data         Data
     * @param string $fromTimeZone Time Zone source
     * @param string $toTimeZone   Time Zone to convert
     * @param array  $arrayKey     Keys that convert to Time Zone
     * @param string $format       Format to return data
     *
     * @return mixed Return data
     */
    public function convertDataToTimeZone($data, $fromTimeZone, $toTimeZone, array $arrayKey = [], $format = 'Y-m-d H:i:s')
    {
        try {
            $regexpDatetime = '/^' . self::REGEXPDATE . '\s' . self::REGEXPTIME . '$/';
            $regexpIso8601  = '/^' . self::REGEXPDATE . 'T' . self::REGEXPTIME . '[\+\-]\d{2}:\d{2}$/';

            if (empty($data)) {
                //Return
                return $data;
            }

            switch (gettype($data)) {
                case 'string':
                    if (is_string($data) && preg_match($regexpDatetime, $data)) {
                        if ($fromTimeZone != $toTimeZone) {
                            $data = $this->convertTimeZone($data, $fromTimeZone, $toTimeZone, $format);
                        }
                    }

                    if (is_string($data) && preg_match($regexpIso8601, $data)) {
                        $data = $this->convertIso8601ToTimeZone($data, $toTimeZone, $format);
                    }
                    break;
                case 'array':
                    $regexpKey = (!empty($arrayKey))? '/^(?:' . implode('|', $arrayKey) . ')$/i': '';

                    array_walk_recursive(
                        $data,
                        function (&$value, $key, $arrayData)
                        {
                            try {
                                if ($arrayData['regexpKey'] == '' || preg_match($arrayData['regexpKey'], $key)) {
                                    if (is_string($value) && preg_match($arrayData['regexpDatetime'], $value)) {
                                        if ($arrayData['fromTimeZone'] != $arrayData['toTimeZone']) {
                                            $value = $this->convertTimeZone($value, $arrayData['fromTimeZone'], $arrayData['toTimeZone'], $arrayData['format']);
                                        }
                                    }

                                    if (is_string($value) && preg_match($arrayData['regexpIso8601'], $value)) {
                                        $value = $this->convertIso8601ToTimeZone($value, $arrayData['toTimeZone'], $arrayData['format']);
                                    }
                                }
                            } catch (\Exception $e) {
                                throw $e;
                            }
                        },
                        ['fromTimeZone' => $fromTimeZone, 'toTimeZone' => $toTimeZone, 'format' => $format, 'regexpDatetime' => $regexpDatetime, 'regexpIso8601' => $regexpIso8601, 'regexpKey' => $regexpKey]
                    );
                    break;
                case 'object':
                    $data = json_decode(json_encode($data), true);
                    $data = $this->convertDataToTimeZone($data, $fromTimeZone, $toTimeZone, $arrayKey, $format);
                    $data = json_decode(json_encode($data));
                    break;
            }

            //Return
            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Convert datetime/array-datetime-data to ISO-8601
     *
     * @param mixed  $data         Data
     * @param string $fromTimeZone Time Zone source
     * @param string $toTimeZone   Time Zone to convert
     * @param array  $arrayKey     Keys that convert to ISO-8601
     *
     * @return mixed Return data
     */
    public function convertDataToIso8601($data, $fromTimeZone, $toTimeZone, array $arrayKey = [])
    {
        try {
            $regexpDatetime = '/^' . self::REGEXPDATE . '\s' . self::REGEXPTIME . '$/';

            if (empty($data)) {
                //Return
                return $data;
            }

            switch (gettype($data)) {
                case 'string':
                    if (is_string($data) && preg_match($regexpDatetime, $data)) {
                        if ($fromTimeZone != $toTimeZone) {
                            $data = $this->convertTimeZone($data, $fromTimeZone, $toTimeZone);
                        }

                        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $data, new \DateTimeZone($toTimeZone));

                        $data = $dt->format(self::ISO8601);
                    }
                    break;
                case 'array':
                    $regexpKey = (!empty($arrayKey))? '/^(?:' . implode('|', $arrayKey) . ')$/i': '';

                    array_walk_recursive(
                        $data,
                        function (&$value, $key, $arrayData)
                        {
                            try {
                                if (($arrayData['regexpKey'] == '' || preg_match($arrayData['regexpKey'], $key)) &&
                                    is_string($value) && preg_match($arrayData['regexpDatetime'], $value)
                                ) {
                                    if ($arrayData['fromTimeZone'] != $arrayData['toTimeZone']) {
                                        $value = $this->convertTimeZone($value, $arrayData['fromTimeZone'], $arrayData['toTimeZone']);
                                    }

                                    $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $value, new \DateTimeZone($arrayData['toTimeZone']));

                                    $value = $dt->format(self::ISO8601);
                                }
                            } catch (\Exception $e) {
                                throw $e;
                            }
                        },
                        ['fromTimeZone' => $fromTimeZone, 'toTimeZone' => $toTimeZone, 'regexpDatetime' => $regexpDatetime, 'regexpKey' => $regexpKey]
                    );
                    break;
                case 'object':
                    $data = json_decode(json_encode($data), true);
                    $data = $this->convertDataToIso8601($data, $fromTimeZone, $toTimeZone, $arrayKey);
                    $data = json_decode(json_encode($data));
                    break;
            }

            //Return
            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Convert ISO-8601/datetime/array-ISO-8601-datetime-data to UTC
     *
     * @param mixed  $data     Data
     * @param array  $arrayKey Keys that convert to UTC
     * @param string $format   Format to return data
     *
     * @return mixed Return data
     */
    public static function convertDataToUtc($data, array $arrayKey = [], $format = 'Y-m-d H:i:s')
    {
        try {
            if (!(isset($_SESSION['__SYSTEM_UTC_TIME_ZONE__']) && $_SESSION['__SYSTEM_UTC_TIME_ZONE__'])) {
                //Return
                return $data;
            }

            $fromTimeZone = \ProcessMaker\BusinessModel\User::getUserLoggedTimeZone();
            $toTimeZone   = 'UTC';

            //Return
            return (new \ProcessMaker\Util\DateTime())->convertDataToTimeZone($data, $fromTimeZone, $toTimeZone, $arrayKey, $format);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Convert UTC to Time Zone
     *
     * @param mixed  $data       Data
     * @param string $toTimeZone Time Zone to convert
     * @param array  $arrayKey   Keys that convert to Time Zone
     * @param string $format     Format to return data
     *
     * @return mixed Return data
     */
    public static function convertUtcToTimeZone($data, $toTimeZone = null, array $arrayKey = [], $format = 'Y-m-d H:i:s')
    {
        try {
            if (!(isset($_SESSION['__SYSTEM_UTC_TIME_ZONE__']) && $_SESSION['__SYSTEM_UTC_TIME_ZONE__'])) {
                //Return
                return $data;
            }

            $fromTimeZone = 'UTC';
            $toTimeZone   = (!is_null($toTimeZone))? $toTimeZone : \ProcessMaker\BusinessModel\User::getUserLoggedTimeZone();

            //Return
            return (new \ProcessMaker\Util\DateTime())->convertDataToTimeZone($data, $fromTimeZone, $toTimeZone, $arrayKey, $format);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Convert UTC to ISO-8601
     *
     * @param mixed $data     Data
     * @param array $arrayKey Keys that convert to ISO-8601
     *
     * @return mixed Return data
     */
    public static function convertUtcToIso8601($data, array $arrayKey = [])
    {
        try {
            if (!(isset($_SESSION['__SYSTEM_UTC_TIME_ZONE__']) && $_SESSION['__SYSTEM_UTC_TIME_ZONE__'])) {
                //Return
                return $data;
            }

            $fromTimeZone = 'UTC';
            $toTimeZone   = \ProcessMaker\BusinessModel\User::getUserLoggedTimeZone();

            //Return
            return (new \ProcessMaker\Util\DateTime())->convertDataToIso8601($data, $fromTimeZone, $toTimeZone, $arrayKey);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

