<?php

/**
 * class.wysiwygEditor.php
 *
 * @package gulliver.system
 *
 * ProcessMaker Open Source Edition
 * Copyright (C) 2004 - 2012 Colosa Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * For more information, contact Colosa Inc, 2566 Le Jeune Rd.,
 * Coral Gables, FL, 33134, USA, or email info@colosa.com.
 *
 */
//XmlForm_Field_DVEditor
/**
 * XmlForm_Field_HTML class definition
 * It is useful to see dynaforms how are built
 *
 * @package gulliver.system
 * @author
 *
 * @copyright (C) 2012 by Colosa Development Team.
 *
 */
class XmlForm_Field_WYSIWYG_EDITOR extends XmlForm_Field
{
    //public $toolbarSet = '';
    public $width = '100%';
    public $height = '300';
    public $defaultValue = '<br/>';
    public $editorType = '';
    /**
     * render function is drawing the dynaform
     *
     * @author
     *
     *
     * @access public
     * @param string $value
     * @param string $owner
     * @return string
     *
     */
    public function render ($value, $owner = null)
    {
        $value = ($value == '') ? '<br/>' : $value;
        $html  = "<textArea class='tmceEditor' id='form[" . $this->name . "]' name='form[" . $this->name . "]' >" . htmlentities( $value, ENT_QUOTES, 'UTF-8' ) . "</textarea>";
        return $html;
    }

    /**
     * attachEvents method executes javascript code in order to initialize
     * the component configuration, attributes, and additional stuff.
     *
     * @author
     *
     *
     * @access public
     * @param string $element
     * @return string
     *
     */
    public function attachEvents ($element)
    {
        //cleaning the conflictive prototype functions
        $editorDefinition = '';
        switch ($this->editorType){
            case 'EMAIL_TEMPLATE':
                $editorDefinition = '
                    //delete Array.prototype.toStr;
                    //delete Object.prototype.toStr;
                    //delete Object.prototype.concat;
                    //delete Object.prototype.get_by_key;
                    //delete Object.prototype.expand;
                    //delete Object.prototype.setParent;
                    //delete Object.prototype.isset_key;
                    tinyMCE.baseURL = "/js/tinymce/jscripts/tiny_mce"
                    tinyMCE.init({
                        theme   : "advanced",
                        plugins : "fullpage",
                        mode    : "specific_textareas",
                        editor_selector : "tmceEditor",
                        width   : "'.$this->width.'",
                        height  : "'.$this->height.'",
                        theme_advanced_buttons3_add : "fullpage"
                    });
                ';
                break;
            case 'OUTPUT_DOCUMENT':
                $editorDefinition = '
                //alert("outputdoc");
                //delete Array.prototype.toStr;
                //delete Object.prototype.toStr;
                //delete Object.prototype.concat;
                //delete Object.prototype.get_by_key;
                //delete Object.prototype.expand;
                //delete Object.prototype.setParent;
                //delete Object.prototype.isset_key;

                tinyMCE.baseURL = "/js/tinymce/jscripts/tiny_mce"
                tinyMCE.init({
                theme   : "advanced",
                plugins : "advhr,advimage,advlink,advlist,autolink,autoresize,autosave,bbcode,contextmenu,directionality,emotions,example,example_dependency,fullpage,fullscreen,iespell,inlinepopups,insertdatetime,layer,legacyoutput,lists,media,nonbreaking,noneditable,pagebreak,paste,preview,print,save,searchreplace,spellchecker,style,tabfocus,table,template,visualblocks,visualchars,wordcount,xhtmlxtras",
                mode    : "specific_textareas",
                editor_selector : "tmceEditor",
                width   : "640",
                height  : "300",
                theme_advanced_buttons1 : "fontselect,bold,italic,underline,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,numlist,bullist,|,insertfile"
                });
                ';

                break;
            case 'DYNAFORM_TEMPLATE':
                $editorDefinition = '

                //delete Array.prototype.toStr;
                //delete Object.prototype.toStr;
                //delete Object.prototype.concat;
                //delete Object.prototype.get_by_key;
                //delete Object.prototype.expand;
                //delete Object.prototype.setParent;
                //delete Object.prototype.isset_key;

                tinyMCE.baseURL = "/js/tinymce/jscripts/tiny_mce"
                tinyMCE.init({
                theme   : "advanced",
                plugins : "advhr,advimage,advlink,advlist,autolink,autoresize,autosave,bbcode,contextmenu,directionality,emotions,example,example_dependency,fullpage,fullscreen,iespell,inlinepopups,insertdatetime,layer,legacyoutput,lists,media,nonbreaking,noneditable,pagebreak,paste,preview,print,save,searchreplace,spellchecker,style,tabfocus,table,template,visualblocks,visualchars,wordcount,xhtmlxtras",
                mode    : "specific_textareas",
                editor_selector : "tmceEditor",
                width   : "640",
                height  : "300",
                    theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,|,cut,copy,paste,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo",
                    theme_advanced_buttons2 : "link,unlink,image,|,forecolor,backcolor,|,hr,removeformat,visualaid,|,sub,sup,|,ltr,rtl",//,|,insertimage",
                skin : "o2k7",
                skin_variant : "silver"//,

                });
                ';
                break;
            default:
                $editorDefinition = '

//                  delete Array.prototype.toStr;
//                  delete Object.prototype.toStr;
//                  delete Object.prototype.concat;
//                  delete Object.prototype.get_by_key;
//                  delete Object.prototype.expand;
//                  delete Object.prototype.setParent;
//                  delete Object.prototype.isset_key;

                    tinyMCE.baseURL = "/js/tinymce/jscripts/tiny_mce"
                    tinyMCE.init({
                        theme   : "advanced",
                        plugins : "fullpage",
                        mode    : "specific_textareas",
                        editor_selector : "tmceEditor",
                        width   : "'. $this->width. '",
                        height  : "'. $this->height. '",
                        theme_advanced_buttons3_add : "fullpage"
                    });
                ';
                break;
        }

        return $editorDefinition;
    }
}