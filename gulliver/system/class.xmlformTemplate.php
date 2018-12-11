<?php

use ProcessMaker\Core\AppEvent;

/**
 * Class xmlformTemplate
 *
 * @author David S. Callizaya S. <davidsantos@colosa.com>
 * @package gulliver.system
 * @access public
 */
class xmlformTemplate extends Smarty
{
    public $template;
    public $templateFile;

    /**
     * Function xmlformTemplate
     *
     * @author David S. Callizaya S. <davidsantos@colosa.com>
     * @access public
     * @param string form
     * @param string templateFile
     * @return string
     */
    public function xmlformTemplate (&$form, $templateFile)
    {
        $this->template_dir = PATH_XMLFORM;
        $this->compile_dir = PATH_SMARTY_C;
        $this->cache_dir = PATH_SMARTY_CACHE;
        $this->config_dir = PATH_THIRDPARTY . 'smarty/configs';
        $this->caching = false;

        // register the resource name "db"
        $this->templateFile = $templateFile;
    }

    /**
     * Function printTemplate
     *
     * @author David S. Callizaya S. <davidsantos@colosa.com>
     * @access public
     * @param string form
     * @param string target
     * @return string
     */
    public function printTemplate (&$form, $target = 'smarty')
    {
        if (strcasecmp( $target, 'smarty' ) === 0) {
            $varPrefix = '$';
        }
        if (strcasecmp( $target, 'templatePower' ) === 0) {
            $varPrefix = '';
        }

        $ft = new StdClass();
        foreach ($form as $name => $value) {
            if (($name !== 'fields') && ($value !== '')) {
                $ft->{$name} = '{$form_' . $name . '}';
            }
            if ($name === 'cols') {
                $ft->{$name} = $value;
            }
            if ($name === 'owner') {
                $ft->owner = & $form->owner;
            }
            if ($name === 'deleteRow') {
                $ft->deleteRow = $form->deleteRow;
            }
            if ($name === 'addRow') {
                $ft->addRow = $form->addRow;
            }
            if ($name === 'editRow') {
                $ft->editRow = $form->editRow;
            }
        }
        if (! isset( $ft->action )) {
            $ft->action = '{$form_action}';
        }
        $hasRequiredFields = false;

        foreach ($form->fields as $k => $v) {
            $ft->fields[$k] = $v->cloneObject();
            $ft->fields[$k]->label = '{' . $varPrefix . $k . '}';

            if ($form->type === 'grid') {
                if (strcasecmp( $target, 'smarty' ) === 0) {
                    $ft->fields[$k]->field = '{' . $varPrefix . 'form.' . $k . '[row]}';
                }
                if (strcasecmp( $target, 'templatePower' ) === 0) {
                    $ft->fields[$k]->field = '{' . $varPrefix . 'form[' . $k . '][row]}';
                }
            } else {
                if (strcasecmp( $target, 'smarty' ) === 0) {
                    $ft->fields[$k]->field = '{' . $varPrefix . 'form.' . $k . '}';
                }
                if (strcasecmp( $target, 'templatePower' ) === 0) {
                    $ft->fields[$k]->field = '{' . $varPrefix . 'form[' . $k . ']}';
                }
            }

            $hasRequiredFields = $hasRequiredFields | (isset( $v->required ) && ($v->required == '1') && ($v->mode == 'edit'));

            if ($v->type == 'xmlmenu') {
                $menu = $v;
            }
        }

        if (isset( $menu )) {
            if (isset( $menu->owner->values['__DYNAFORM_OPTIONS']['PREVIOUS_STEP'] )) {
                $prevStep_url = $menu->owner->values['__DYNAFORM_OPTIONS']['PREVIOUS_STEP'];

                $this->assign( 'prevStep_url', $prevStep_url );
                $this->assign( 'prevStep_label', G::loadTranslation( 'ID_BACK' ) );
            }
        }

        $this->assign( 'hasRequiredFields', $hasRequiredFields );
        $this->assign( 'form', $ft );
        $this->assign( 'printTemplate', true );
        $this->assign( 'printJSFile', false );
        $this->assign( 'printJavaScript', false );
        //$this->assign ( 'dynaformSetFocus', "try {literal}{{/literal} dynaformSetFocus();}catch(e){literal}{{/literal}}" );
        return $this->fetch( $this->templateFile );
    }

    /**
     * Function printJavaScript
     *
     * @author David S. Callizaya S. <davidsantos@colosa.com>
     * @access public
     * @param string form
     * @return string
     */
    public function printJavaScript (&$form)
    {
        $this->assign( 'form', $form );
        $this->assign( 'printTemplate', false );
        $this->assign( 'printJSFile', false );
        $this->assign( 'printJavaScript', true );
        return $this->fetch( $this->templateFile );
    }

    /**
     * Function printJSFile
     *
     * @author David S. Callizaya S. <davidsantos@colosa.com>
     * @access public
     * @param string form
     * @return string
     */
    public function printJSFile (&$form)
    {
        //JS designer>preview
        if (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"]) && preg_match("/^.*dynaforms_Editor\?.*PRO_UID=.*DYN_UID=.*$/", $_SERVER["HTTP_REFERER"]) && preg_match("/^.*dynaforms\/dynaforms_Ajax.*$/", $_SERVER["REQUEST_URI"])) {
            $js = null;

            foreach ($form->fields as $index => $value) {
                $field = $value;

                if ($field->type == "javascript" && !empty($field->code)) {
                    $js = $js . " " . $field->code;
                }
            }

            if ($js != null) {
                $form->jsDesignerPreview = "
                //JS designer>preview
                $js

                loadForm_" . $form->id . "(\"../gulliver/defaultAjaxDynaform\");

                if (typeof(dynaformOnload) != \"undefined\") {
                    dynaformOnload();
                }
                ";
            }
        }

        $this->assign( 'form', $form );
        $this->assign( 'printTemplate', false );
        $this->assign( 'printJSFile', true );
        $this->assign( 'printJavaScript', false );
        return $this->fetch( $this->templateFile );
    }

    /**
     * Function getFields
     *
     * @author David S. Callizaya S. <davidsantos@colosa.com>
     * @access public
     * @param string form
     * @return string
     */
    public function getFields (&$form, $therow = -1)
    {
        $result = array ();

        foreach ($form->fields as $k => $v) {
            $field = $v;

            if ($form->mode != '') {
                #@ last modification: erik
                $field->mode = $form->mode; #@
            } #@

            //if (isset($form->fields[$k]->sql)) $form->fields[$k]->executeSQL( $form );
            $value = (isset( $form->values[$k] )) ? $form->values[$k] : null;
            $result[$k] = G::replaceDataField( $form->fields[$k]->label, $form->values );

            if ($form->type == 'xmlform') {
                if (in_array($field->type, array("text", "currency", "percentage", "password", "suggest", "textarea", "dropdown", "yesno", "listbox", "checkbox", "date", "link", "file"))) {
                    $result[$k] = '<label for="form[' . $k . ']">' . $result[$k] . '</label>';
                }
            }

            if (! is_array( $value )) {
                if ($form->type == 'grid') {
                    $aAux = array ();
                    if (!isset($form->values[$form->name])) {
                        $form->values[$form->name] = array();
                    }
                    if ($therow == - 1) {
                        for ($i = 0; $i < count( $form->values[$form->name] ); $i ++) {
                            $aAux[] = '';
                        }
                    } else {
                        for ($i = 0; $i < $therow; $i ++) {
                            $aAux[] = '';
                        }
                    }

                    switch ($field->type) {
                        case "link":
                            $result["form"][$k] = $form->fields[$k]->renderGrid($aAux, array(), $form);
                            break;
                        default:
                            $result["form"][$k] = $form->fields[$k]->renderGrid($aAux, $form);
                            break;
                    }
                } else {
                    switch ($field->type) {
                        case "link":
                            $result["form"][$k] = $form->fields[$k]->render(
                                $value,
                                (isset($form->values[$k . "_label"]))? $form->values[$k . "_label"] : null,
                                $form
                            );
                            break;
                        default:
                            $result["form"][$k] = $form->fields[$k]->render($value, $form);
                            break;
                    }
                }
            } else {
                /*if (isset ( $form->owner )) {
                    if (count ( $value ) < count ( $form->owner->values [$form->name] )) {
		                $i = count ( $value );
		                $j = count ( $form->owner->values [$form->name] );

		                for($i; $i < $j; $i ++) {
		                    $value [] = '';
                        }
                    }
                }*/

                if ($field->type == "grid") {
                    // Fix data for grids
                    if (is_array($form->fields[$k]->fields)) {
                        foreach ($form->fields[$k]->fields as $gridFieldName => $gridField) {
                            $valueLength = count($value);
                            for ($i = 1; $i <= $valueLength; $i++) {
                                if (!isset($value[$i][$gridFieldName])) {
                                    switch ($gridField->type) {
                                        case 'checkbox':
                                            $defaultAttribute = 'falseValue';
                                            break;
                                        default:
                                            $defaultAttribute = 'defaultValue';
                                            break;
                                    }
                                    $value[$i][$gridFieldName] = isset($gridField->$defaultAttribute) ? $gridField->$defaultAttribute : '';
                                }
                            }
                        }
                    }
                    $form->fields[$k]->setScrollStyle( $form );
                    $result["form"][$k] = $form->fields[$k]->renderGrid( $value, $form, $therow );
                } else {
                    switch ($field->type) {
                        case "dropdown":
                            $result["form"][$k] = $form->fields[$k]->renderGrid( $value, $form, false, $therow );
                            break;
                        case "file":
                            $result["form"][$k] = $form->fields[$k]->renderGrid( $value, $form, $therow );
                            break;
                        case "link":
                            $result["form"][$k] = $form->fields[$k]->renderGrid(
                                $value,
                                (isset($form->values[$k . "_label"]))? $form->values[$k . "_label"] : array(),
                                $form
                            );
                            break;
                        default:
                            $result["form"][$k] = $form->fields[$k]->renderGrid( $value, $form );
                            break;
                    }
                }
            }
        }

        foreach ($form as $name => $value) {
            if ($name !== 'fields') {
                $result['form_' . $name] = $value;
            }
        }

        return $result;
    }

    /**
     * Function printObject
     * @author David S. Callizaya S. <davidsantos@colosa.com>
     * @access public
     * @param string form
     * @return string
     */
    public function printObject(&$form, $therow = -1)
    {
        //to do: generate the template for templatePower.
        //DONE: The template was generated in printTemplate, to use it
        // is necesary to load the file with templatePower and send the array
        //result
        $this->register_resource ( 'mem', array (array (&$this, '_get_template' ), array ($this, '_get_timestamp' ), array ($this, '_get_secure' ), array ($this, '_get_trusted' ) ) );
        $result = $this->getFields ( $form, $therow );

        $this->assign ( array ('PATH_TPL' => PATH_TPL ) );
        $this->assign ( $result );
        if ( defined('SYS_LANG_DIRECTION') && SYS_LANG_DIRECTION == 'R' ) {
            switch( $form->type ){
                case 'toolbar':
                    $form->align = 'right';
                    break;
            }
        }

        $this->assign ( array ('_form' => $form ) );
        //'mem:defaultTemplate'.$form->name obtains the template generated for the
        //current "form" object, then this resource y saved by Smarty in the
        //cache_dir. To avoiding troubles when two forms with the same id are being
        //drawed in a same page with different templates, add an . rand(1,1000)
        //to the resource name. This is because the process of creating templates
        //(with the method "printTemplate") and painting takes less than 1 second
        //so the new template resource generally will had the same timestamp.
        $output = $this->fetch ( 'mem:defaultTemplate' . $form->name );
        $output = AppEvent::getAppEvent()
                ->setHtml($output)
                ->dispatch(AppEvent::XMLFORM_RENDER, $form)
                ->getHtml();
        return $output;
    }

    /**
     * Smarty plugin
     * -------------------------------------------------------------
     * Type:     resource
     * Name:     mem
     * Purpose:  Fetches templates from this object
     * -------------------------------------------------------------
     */
    public function _get_template($tpl_name, &$tpl_source, &$smarty_obj)
    {
        $tpl_source = $this->template;
        return true;
    }

    /**
     * Function _get_timestamp
     * @author David S. Callizaya S. <davidsantos@colosa.com>
     * @access public
     * @param string tpl_name
     * @param string tpl_timestamp
     * @param string smarty_obj
     * @return string
     */
    public function _get_timestamp($tpl_name, &$tpl_timestamp, &$smarty_obj)
    {
        //NOTE: +1 prevents to load the smarty cache instead of this resource
        $tpl_timestamp = time () + 1;
        return true;
    }

    /**
     * Function _get_secure
     * @author David S. Callizaya S. <davidsantos@colosa.com>
     * @access public
     * @param string tpl_name
     * @param string smarty_obj
     * @return string
     */
    public function _get_secure($tpl_name, &$smarty_obj)
    {
        // assume all templates are secure
        return true;
    }

    /**
     * Function _get_trusted
     * @author David S. Callizaya S. <davidsantos@colosa.com>
     * @access public
     * @param string tpl_name
     * @param string smarty_obj
     * @return string
     */
    public function _get_trusted($tpl_name, &$smarty_obj)
    {
        // not used for templates
    }
}