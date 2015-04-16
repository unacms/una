<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioFormView extends BxDolStudioForm
{
    function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);
    }

    function addCssJs()
    {
        parent::addCssJs();

        $this->oTemplate->addJs(array(
            'forms.js'
        ));

        $this->oTemplate->addCss(array(
        	BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'flag-icon-css/css/|flag-icon.min.css'
        ));
    }

    function genRow(&$aInput)
    {
        $sRow = '';
        switch($aInput['type']) {
            case 'image_uploader':
                $sRow = $this->genRowImageUploader($aInput);
                break;

             default:
                $sRow = parent::genRow($aInput);
        }

        return $sRow;
    }

    function genInput(&$aInput)
    {
        $sInput = '';
        switch($aInput['type']) {
            case 'text_translatable':
                $sInput = $this->genInputTranslatable($aInput, 'text');
                break;

            case 'textarea_translatable':
                $aInput['html'] = 0;
                $sInput = $this->genInputTranslatable($aInput, 'textarea');
                break;

            default:
                $sInput = parent::genInput($aInput);
        }

        return $sInput;
    }

    function genRowImageUploader(&$aInput)
    {
        $aTmplVarsPreview = array(
            'bx_if:show_empty' => array(
                'condition' => true,
                'content' => array()
            ),
            'bx_if:show_image' => array(
                'condition' => false,
                'content' => array()
            )
        );

        if(($iId = (int)$aInput['value']) != 0) {

            $sFileUrl = false;
            if (!empty($aInput['transcoder_object'])) {
                $oTranscoder = BxDolTranscoderImage::getObjectInstance($aInput['transcoder_object']);
                $sFileUrl = $oTranscoder->getFileUrlNotReady($iId);
                if (isset($aInput['transcoder_image_width']) && $aInput['transcoder_image_width'] > 0)
                    $sFileUrl = bx_append_url_params($sFileUrl, array('x' => $aInput['transcoder_image_width']));
                if (isset($aInput['transcoder_image_height']) && $aInput['transcoder_image_height'] > 0)
                    $sFileUrl = bx_append_url_params($sFileUrl, array('y' => $aInput['transcoder_image_height']));
            } 
            else {
                $sStorage = isset($aInput['storage_object']) && $aInput['storage_object'] != '' ? $aInput['storage_object'] : BX_DOL_STORAGE_OBJ_IMAGES;
                $oStorage = BxDolStorage::getObjectInstance($sStorage);
                $sFileUrl = $oStorage->getFileUrlById($iId);
            }

            if($sFileUrl !== false) {
                $aTmplVarsPreview['bx_if:show_empty']['condition'] = false;
                $aTmplVarsPreview['bx_if:show_image'] = array(
                    'condition' => true,
                    'content' => array(
                        'url' => $sFileUrl,
                        'bx_if:show_action_delete' => array(
                            'condition' => isset($aInput['ajax_action_delete']) && $aInput['ajax_action_delete'] != "",
                            'content' => array(
                                'action' => $aInput['ajax_action_delete']
                            )
                        )
                    )
                );
            }
        }

        $sRow = '';
        $aInputPreview = array(
            'type' => 'custom',
            'name' => 'preview',
            'caption' => isset($aInput['caption_preview']) ? $aInput['caption_preview'] : _t('_adm_txt_form_view_iu_preview'),
            'content' => $this->oTemplate->parseHtmlByName('form_view_iu_preview.html', $aTmplVarsPreview)
        );
        $sRow .= $this->genRow($aInputPreview);

        $aInput['type'] = 'file';
        $sRow .= $this->genRow($aInput);

        return $sRow;
    }

    function genInputTranslatable(&$aInput, $sType = 'text')
    {
        $iValueLength = 20;
        $sInputIdPrefix = 'bx-form-input-';
        $aInputMethod = array('text' => 'genInputStandard', 'textarea' => 'genInputTextarea');

        $sInput = '';
        $sInputName = $aInput['name'];
        $aInputAttrs = isset($aInput['attrs']) && is_array($aInput['attrs']) ? $aInput['attrs'] : array();

        $oFunctions = BxTemplStudioFunctions::getInstance();

        $oLanguage = BxDolStudioLanguagesUtils::getInstance();
        $sLanguage = $oLanguage->getCurrentLangName(false);
        $aLanguages = $oLanguage->getLanguagesInfo();

        $aStrings = $oLanguage->getLanguageString($aInput['value']);

        $aInput['type'] = 'hidden';
        $aInput['attrs'] = array_merge($aInputAttrs, array(
            'id' => $sInputIdPrefix . $aInput['name']
        ));
        $sInput .= $this->genInputStandard($aInput);

        $aTmplVars = array();
        foreach($aLanguages as $aLanguage) {
            $bLanguage = $aLanguage['name'] == $sLanguage;

            $sValue = '';
            if(($bValue = key_exists($aLanguage['id'], $aStrings)) === true)
                $sValue = $aStrings[$aLanguage['id']]['string'];
            else if(isset($aInput['values'][$aLanguage['name']]))
                $sValue = $aInput['values'][$aLanguage['name']];

            $aInput['type'] = $sType;
            $aInput['name'] = $sInputName . '-' . $aLanguage['name'];
            $aInput['value'] = $sValue;
            $aInput['attrs'] = array_merge($aInputAttrs, array(
                'id' => $sInputIdPrefix . $aInput['name'],
                'style' => !$bLanguage ? 'display:none;' : ''
            ));
            $sInput .= $this->$aInputMethod[$sType]($aInput);

            $aTmplVarValue = array(
                'condition' => $bValue,
                'content' => array(
                    'value' => strmaxtextlen($sValue, $iValueLength)
                )
            );
            $aTmplVarMissing = array(
                'condition' => !$bValue || empty($sValue),
                'content' => array()
            );
            $aTmplVars[] = array(
                'name' => $sInputName,
                'lang_name' => $aLanguage['name'],
                'lang_flag' => $aLanguage['icon'],
                'lang_title' => $aLanguage['title'],
                'bx_if:hide_active' => array(
                    'condition' => !$bLanguage,
                    'content' => array()
                ),
                'bx_if:act_value' => $aTmplVarValue,
                'bx_if:act_missing' => $aTmplVarMissing,
                'bx_if:hide_passive' => array(
                    'condition' => $bLanguage,
                    'content' => array()
                ),
                'bx_if:pas_value' => $aTmplVarValue,
                'bx_if:pas_missing' => $aTmplVarMissing,
            );
        }

        $sPopup = $this->oTemplate->parseHtmlByName('form_view_translator.html', array('bx_repeat:languages' => $aTmplVars));
        $sPopup = $oFunctions->transBox('bx-form-field-translator-popup-' . $sInputName, $sPopup, true);

        $aLanguage = $oLanguage->getLanguageInfo($sLanguage);
        $sControl = $this->oTemplate->parseHtmlByName('form_view_translatable.html', array(
            'flag' => $aLanguage['icon'],
            'title' => $aLanguage['title'],
            'name' => $sInputName,
            'popup' => $sPopup
        ));

        return $sInput . $sControl;
    }

    /**
     * Generate Reverse Checkbox Set Element
     *
     * @param  array  $aInput
     * @return string
     */
    function genInputCheckboxSet(&$aInput)
    {
     	$aInput['value'] = isset($aInput['value']) && $aInput['value'] && is_array($aInput['value']) ? $aInput['value'] : array();
		if(!empty($aInput['reverse']))
			$aInput['value'] = array_diff(array_keys($aInput['values']), $aInput['value']);

        return parent::genInputCheckboxSet($aInput);
    }
}

/** @} */
