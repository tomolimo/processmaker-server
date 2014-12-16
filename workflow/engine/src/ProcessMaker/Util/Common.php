<?php
namespace ProcessMaker\Util;

class Common extends \Maveriks\Util\Common
{
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
}