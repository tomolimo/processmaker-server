<?php
/**
 * To implement pmos translation function on smarty templates
 *
 * example use:
 *
 *    <h1>{translate label="ID_HOME_TITLE"}</h1>
 *
 * @params $params mixed array containg all parameters passed from smarty plugin call
 * @author Erik Amaru Ortiz <aortiz.erik@gmail.com, erik@colosa.com>
 */

function translate($params)
{
    if (array_key_exists('lang', $params)) {
        $lang = $params['lang'];
    } elseif (defined('SYS_LANG')) {
        $lang = SYS_LANG;
    } else {
        $lang = 'en';
    }

    if (! array_key_exists('label', $params)) {
       throw new Exception('Error: Param "label" is missing on "tranlate" smarty function, it should be called like: {translate label="SOME_LABEL_ID"}');
    }

    echo G::loadTranslation($params['label'], $lang);
}
