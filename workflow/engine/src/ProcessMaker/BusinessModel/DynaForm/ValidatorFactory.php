<?php

namespace ProcessMaker\BusinessModel\DynaForm;

class ValidatorFactory
{

    const TITLE = "title";
    const SUBTITLE = "subtitle";
    const LABEL = "label"; //deprecated
    const LINK = "link";
    const IMAGE = "image";
    const FILE = "file";
    const SUBMIT = "submit";
    const BUTTON = "button";
    const GRID = "grid";
    const SUBFORM = "subform";
    const TEXT = "text";
    const TEXTAREA = "textarea";
    const DROPDOWN = "dropdown";
    const CHECKBOX = "checkbox";
    const CHECKGROUP = "checkgroup";
    const RADIO = "radio";
    const DATETIME = "datetime";
    const SUGGEST = "suggest";
    const HIDDEN = "hidden";
    const FORM = "form";
    const CELL = "cell";
    const ANNOTATION = "label"; //todo
    const GEOMAP = "location";
    const QRCODE = "scannerCode";
    const SIGNATURE = "signature";
    const IMAGEM = "imageMobile";
    const AUDIOM = "audioMobile";
    const VIDEOM = "videoMobile";
    const PANEL = "panel";
    const MSGPANEL = "msgPanel";

    /**
     * 
     * @param type $type
     * @param type $json
     * @return \ProcessMaker\BusinessModel\DynaForm\ValidatorInterface
     */
    public static function createValidatorClass($type = '', $json = null)
    {
        switch ($type) {
            case ValidatorFactory::CHECKGROUP:
                return new ValidatorCheckGroup($json);
            default :
                return null;
        }
    }

}
