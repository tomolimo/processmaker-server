<?php
namespace ProcessMaker\Util;

class Common extends \Maveriks\Util\Common
{
    private $frontEnd = false;

    /**
     * Set front-end flag (Terminal's front-end)
     *
     * @param bool $flag Flag
     *
     * return void
     */
    public function setFrontEnd($flag)
    {
        try {
            $this->frontEnd = $flag;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Show front-end (Terminal's front-end)
     *
     * @param string $option Option
     * @param string $data   Data string
     *
     * return void
     */
    public function frontEndShow($option, $data = "")
    {
        try {
            if (!$this->frontEnd) {
                return;
            }

            $numc = 50;
            $total = $numc - 2 - strlen($data);
            if($total < 0){
                $total = 0;
            }
            switch ($option) {
                case "BAR":
                    echo "\r" . "| " . $data . str_repeat(" ", $total);
                    break;
                case "TEXT":
                    echo "\r" . '| ' . $data . "\n";
                    break;
                default:
                    //START, END
                    echo "\r" . "+" . str_repeat("-", $numc - 2) . "+" . "\n";
                    break;
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Progress bar (Progress bar for terminal)
     *
     * @param int $total Total
     * @param int $count Count
     *
     * return string Return a string that represent progress bar
     */
    public function progressBar($total, $count)
    {
        try {
            $p = (int)(($count * 100) / $total);
            $n = (int)($p / 2);

            return "[" . str_repeat("|", $n) . str_repeat(" ", 50 - $n) . "] $p%";
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Generate random number
     *
     * @author Fernando Ontiveros Lira <fernando@colosa.com>
     * @access public
     * @return string
     */
    public static function generateUID()
    {
        do {
            $sUID = str_replace('.', '0', uniqid(rand(0, 999999999), true));
        } while (strlen( $sUID ) != 32);

        return $sUID;
    }

    /**
     * Generate a numeric or alphanumeric code
     *
     * @author Julio Cesar Laura Avendaño <juliocesar@colosa.com>
     * @access public
     * @param int $iDigits
     * @param string $sType
     * @return string
     */
    public function generateCode($iDigits = 4, $sType = 'NUMERIC')
    {
        if (($iDigits < 4) || ($iDigits > 50)) {
            $iDigits = 4;
        }
        if (($sType != 'NUMERIC') && ($sType != 'ALPHA') && ($sType != 'ALPHANUMERIC')) {
            $sType = 'NUMERIC';
        }

        $aValidCharacters = array(
            '0','1','2','3','4','5','6','7','8','9','A','B','C','D','E','F','G','H',
            'I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'
        );

        switch ($sType) {
            case 'NUMERIC':
                $iMin = 0;
                $iMax = 9;
                break;
            case 'ALPHA':
                $iMin = 10;
                $iMax = 35;
                break;
            case 'ALPHANUMERIC':
                $iMin = 0;
                $iMax = 35;
                break;
        }

        $sCode = '';
        for ($i = 0; $i < $iDigits; $i ++) {
            $sCode .= $aValidCharacters[rand($iMin, $iMax)];
        }

        return $sCode;
    }

    /**
     * Convert string to JSON
     *
     * @param string $string
     *
     * @return object Returns an object, FALSE otherwise
     */
    public static function stringToJson($string)
    {
        try {
            $object = json_decode($string);

            return (json_last_error() == JSON_ERROR_NONE)? $object : false;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}

