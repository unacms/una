<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioSettings extends BxDolStudioSettings
{
    public function __construct($sType = '', $mixedCategory = '')
    {
        parent::__construct($sType, $mixedCategory);
    }
    public function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('forms.css', 'settings.css'));
    }
    public function getPageJs()
    {
        return array_merge(parent::getPageJs(), array('jquery.form.min.js', 'jquery.webForms.js', 'settings.js'));
    }
    public function getPageJsObject()
    {
        return 'oBxDolStudioSettings';
    }
    public function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $aTypes = $aMenu = array();
        if($this->oDb->getTypes(array('type' => 'all'), $aTypes) > 0 ) {
            $aTypesGrouped = array();
            foreach($aTypes as $aType)
                $aTypesGrouped[$aType['group']][] = $aType;

            foreach($aTypesGrouped as $sGroup => $aTypes)
                foreach($aTypes as $aType)
                    $aMenu[] = array(
                        'name' => $aType['name'],
                        'icon' => $this->getMenuIcon($sGroup, $aType),
                        'link' => BX_DOL_URL_STUDIO . 'settings.php?page=' . $aType['name'],
                        'title' => $aType['caption'],
                        'selected' => $aType['name'] == $this->sType
                    );
        }

        return parent::getPageMenu($aMenu);
    }
    public function getPageCode($sCategorySelected = '')
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        $sJsObject = $this->getPageJsObject();

        $aCategories = array();
        $iCategories = $this->oDb->getCategories(array('type' => 'by_type_name_key_name', 'type_name' => $this->sType, 'category_name' => $this->sCategory, 'hidden' => 0), $aCategories);
        if($iCategories > 0)
            $aCategories = array_keys($aCategories);

		$bMix = false;
		$aOptions2Mixes = array();
		if($this->bMixes) {
			if(is_string($this->sCategory))
        		$aMixesBrowse = array('type' => 'by_type_category', 'type' => $this->sType, 'category' => $this->sCategory, 'active' => 1);
        	else
        		$aMixesBrowse = array('type' => 'by_type', 'value' => $this->sType, 'active' => 1);

        	$aMix = array();
			$this->oDb->getMixes($aMixesBrowse, $aMix, false);

			$this->sMix = BX_DOL_STUDIO_STG_MIX_DEFAULT; 
			if(!empty($aMix) && is_array($aMix)) {
				$this->aMix = $aMix;
				$this->sMix = $aMix['name'];

				$bMix = true;
				$this->oDb->getMixesOptions(array('type' => 'by_mix_id_pair_option_value', 'value' => $this->aMix['id']), $aOptions2Mixes, false);
			}
		}

        $bWrap = count($aCategories) > 1;
        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-settings-form',
                'name' => 'adm-settings-form',
                'action' => BX_DOL_URL_STUDIO . 'settings.php?page=' . $this->sType,
                'method' => 'post',
                'enctype' => 'multipart/form-data',
                'target' => 'adm-settings-iframe'
            ),
            'params' => array(
                'db' => array(
                    'table' => 'sys_options',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'save'
                ),
            ),
            'inputs' => array()
        );

        if($bMix)
        	$aForm['inputs']['mix_id'] = array(
        		'type' => 'hidden',
        		'name' => 'mix_id',
        		'value' => $this->aMix['id'],
        		'db' => array (
					'pass' => 'Int',
				),
        	);

        foreach($aCategories as $sCategory) {
            $aFields = array();

            if(empty($sCategory))
                continue;

            $aCategory = array();
            $iCategory = $this->oDb->getCategories(array('type' => 'by_name', 'value' => $sCategory), $aCategory);
            if($iCategory != 1)
                continue;

            $aOptions = array();
            $iOptions = $this->oDb->getOptions(array('type' => 'by_category_id', 'value' => $aCategory['id']), $aOptions);

            foreach($aOptions as $aOption)
                $aFields[$aOption['name']] = $this->field($aOption, $aOptions2Mixes);

            if($bWrap) {
                $aCategory['selected'] = $aCategory['name'] == $sCategorySelected;
                $aFields = $this->header($aCategory, $aFields);
            }

            $aForm['inputs'] = array_merge($aForm['inputs'], $aFields);
        }
        $aForm['inputs'] = array_merge(
            $aForm['inputs'],

            (!$bWrap ? array() : array(
                'header_save' => array(
                    'type' => 'block_header',
                ),
            )),

            array(
                'categories' => array(
                    'type' => 'hidden',
                    'name' => 'categories',
                    'value' => implode(',', $aCategories),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'save' => array(
                    'type' => 'submit',
                    'name' => 'save',
                    'value' => _t("_adm_btn_settings_save"),
                )
            )
        );

        $oForm = new BxTemplStudioFormView($aForm, $oTemplate);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            echo $this->saveChanges($oForm);
            exit;
        }

        $bTmplVarsMixes = false;
        $aTmplVarsMixes = array();
        if($this->bMixes) {
        	if(is_string($this->sCategory))
        		$aMixesBrowse = array('type' => 'by_type_category', 'type' => $this->sType, 'category' => $this->sCategory);
        	else
        		$aMixesBrowse = array('type' => 'by_type', 'value' => $this->sType);

        	$aMixes = array();
			$this->oDb->getMixes($aMixesBrowse, $aMixes, false);
			$aMixes = array_merge(array(array('name' => BX_DOL_STUDIO_STG_MIX_DEFAULT, 'title' => _t('_adm_stg_txt_mix_' . BX_DOL_STUDIO_STG_MIX_DEFAULT))), $aMixes);

			foreach($aMixes as $aMix)
				$aTmplVarsMixes[] = array(
					'value' => $aMix['name'],
					'title' => $aMix['title'],
					'bx_if:show_checked_mix' => array(
						'condition' => !empty($this->sMix) && $aMix['name'] == $this->sMix,
						'content' => array()
					)
				);

			$bTmplVarsMixes = !empty($aTmplVarsMixes);
        }

        $bTmplVarsDeleteMix = !empty($this->sMix) && !empty($this->aMix);
        $aTmplVarsDeleteMix = array();
        if($bTmplVarsDeleteMix)
        	$aTmplVarsDeleteMix = array(
        		'js_object' => $sJsObject,
				'id' => $this->aMix['id']
        	);

        return $oTemplate->parseHtmlByName('settings.html', array(
        	'js_object' => $sJsObject,
        	'type' => $this->sType,
	        'category' => is_array($this->sCategory) ? json_encode($this->sCategory) : $this->sCategory,
	        'mix' => $this->sMix,
        	'bx_if:show_mixes' => array(
        		'condition' => $this->bMixes,
        		'content' => array(
        			'js_object' => $sJsObject,
        			'bx_if:show_select_mix' => array(
        				'condition' => $bTmplVarsMixes,
        				'content' => array(
        					'js_object' => $sJsObject,
        					'bx_repeat:mixes' => $aTmplVarsMixes,
        				) 
        			),
        			'bx_if:show_delete_mix' => array(
        				'condition' => !empty($this->sMix) && !empty($this->aMix),
        				'content' => $aTmplVarsDeleteMix
        			),
        		)
        	), 
        	'form' => $oForm->getCode()
        ));
    }

    public function getPopupCodeCreateMix()
    {
    	$oTemplate = BxDolStudioTemplate::getInstance();
    	$sJsObject = $this->getPageJsObject();

    	$sForm = 'adm-settings-create-mix-form';
    	$aForm = array(
            'form_attrs' => array(
                'id' => $sForm,
                'name' => $sForm,
                'action' => bx_append_url_params(BX_DOL_URL_STUDIO . 'settings.php', array('stg_action' => 'create-mix')),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ),
            'params' => array(
                'db' => array(
                    'table' => 'sys_options_mixes',
                    'key' => 'id',
                    'uri' => 'name',
                    'uri_title' => 'title',
                    'submit_name' => 'save'
                ),
            ),
            'inputs' => array(
            	'page' => array(
            		'type' => 'hidden',
                    'name' => 'page',
                    'value' => $this->sType
				),
				'category' => array(
            		'type' => 'hidden',
                    'name' => 'category',
                    'value' => is_array($this->sCategory) ? json_encode($this->sCategory) : $this->sCategory,
				),
            	'title' => array(
            		'type' => 'text',
                    'name' => 'title',
                    'caption' => _t('_adm_stg_txt_mix_title'),
                    'value' => '',
                    'db' => array (
                        'pass' => 'Xss',
            		)
				),
				'controls' => array(
					'type' => 'input_set',
	            	array(
	                    'type' => 'submit',
	                    'name' => 'save',
	                    'value' => _t('_adm_btn_settings_save'),
	                ),
	                array(
	                    'type' => 'button',
	                    'name' => 'cancel',
	                    'value' => _t('_adm_txt_confirm_cancel'),
	                	'attrs' => array(
	                		'class' => 'bx-def-margin-sec-left-auto',
	                		'onclick' => '$(".bx-popup-applied:visible").dolPopupHide()'
	                	)
	                )
				)
            )
        );

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
        	$iId = $oForm->insert(array(
        		'type' => $this->sType,
        		'category' => is_string($this->sCategory) ? $this->sCategory : '',
        		'name' => $oForm->generateUri()
        	));

        	if($iId !== false) {
        		$this->oDb->updateMixes(array('active' => 0), array(
        			'type' => $this->sType,
        			'category' => is_string($this->sCategory) ? $this->sCategory : '',
        			'active' => 1
        		));
        		$this->oDb->updateMixes(array('active' => 1), array('id' => $iId));

        		return array(
        			'eval' => $sJsObject . '.onMixCreate(oData);'
        		);
        	}
        }

		return array(
			'popup' => BxTemplStudioFunctions::getInstance()->popupBox('adm-stg-create-mix-popup', _t('_adm_stg_txt_create_mix_popup'), $oTemplate->parseHtmlByName('stg_create_mix.html', array(
				'js_object' => $sJsObject,
				'form_id' => $sForm,
				'form' => $oForm->getCode(true),
			)))
		);
    }

    protected function header($aCategory, $aFields)
    {
        return array_merge(
            array(
                'category_' . $aCategory['id'] . '_beg' => array(
                    'type' => 'block_header',
                    'name' => 'category_' . $aCategory['id'] . '_beg',
                    'caption' => _t($aCategory['caption']),
                    'collapsable' => true,
                    'collapsed' => !$aCategory['selected']
                )
            ),
            $aFields);
    }

    protected function field($aItem, $aItems2Mixes)
    {
    	$oTemplate = BxDolStudioTemplate::getInstance();

    	$mixedValue = isset($aItems2Mixes[$aItem['name']]) ? $aItems2Mixes[$aItem['name']] : $aItem['value'];

        $aField = array();
        switch($aItem['type']) {
            case 'digit':
                $aField = array(
                    'type' => 'text',
                    'name' => $aItem['name'],
                    'caption' => _t($aItem['caption']),
                    'value' => $mixedValue,
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                );
                break;
            case 'text':
                $aField = array(
                    'type' => 'textarea',
                    'name' => $aItem['name'],
                    'caption' => _t($aItem['caption']),
                    'value' => $mixedValue,
                    'db' => array (
                        'pass' => 'XssHtml',
                    ),
                );
                break;
            case 'checkbox':
                $aField = array(
                    'type' => 'checkbox',
                    'name' => $aItem['name'],
                    'caption' => _t($aItem['caption']),
                    'value' => 'on',
                    'checked' => $mixedValue == 'on',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                );
                break;
            case 'list':
            case 'rlist':
                $aField = array(
                    'type' => 'checkbox_set',
                    'name' => $aItem['name'],
                    'caption' => _t($aItem['caption']),
                    'value' => !empty($mixedValue) ? explode(',', $mixedValue) : array(),
                	'reverse' => $aItem['type'] == 'rlist',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                );

                if (BxDolService::isSerializedService($aItem['extra']))
                    $aField['values'] = BxDolService::callSerialized($aItem['extra']);
                else
                    foreach(explode(',', $aItem['extra']) as $sValue)
                        $aField['values'][$sValue] = $sValue;
                break;
            case 'select':
                $aField = array(
                    'type' => 'select',
                    'name' => $aItem['name'],
                    'caption' => _t($aItem['caption']),
                    'value' => $mixedValue,
                    'values' => array(),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                );

                if (BxDolService::isSerializedService($aItem['extra']))
                    $aField['values'] = BxDolService::callSerialized($aItem['extra']);
                else
                    foreach(explode(',', $aItem['extra']) as $sValue)
                        $aField['values'][] = array('key' => $sValue, 'value' => $sValue);
                break;
            case 'file':
                $aField = array(
                    'type' => 'file',
                    'name' => $aItem['name'],
                    'caption' => _t($aItem['caption']),
                    'value' => $mixedValue,
                    'db' => array (
                        'pass' => 'Xss'
                    )
                );
                break;
			case 'image':
				//--- Concatenation integer values as strings is required to get unique content id
				$iContentId = (int)($aItem['id'] . ($this->bMixes && isset($this->aMix['id']) ? $this->aMix['id'] : 0));

                $aField = array(
                    'type' => 'files',
                    'name' => $aItem['name'],
					'storage_object' => $this->sStorage,
 					'images_transcoder' => $this->sTranscoder,
 					'uploaders' => array('sys_settings_html5'),
                	'upload_buttons_titles' => array('HTML5' => _t('_sys_uploader_button_name_single')),
					'multiple' => false,
 					'content_id' => $iContentId,
 					'ghost_template' => array(
						'inputs' => array(),
					),
                    'caption' => _t($aItem['caption']),
                    'value' => $mixedValue,
                    'db' => array (
                        'pass' => 'Xss'
                    )
                );
                break;
			case 'rgb':
			case 'rgba':
                $aField = array(
                    'type' => $aItem['type'],
                    'name' => $aItem['name'],
                    'caption' => _t($aItem['caption']),
                    'value' => $mixedValue,
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                );
                break;
        }
        return $aField;
    }
    protected function getMenuIcon($sGroup, &$aType)
    {
        if(empty($aType['icon']) || ($sUrl = BxDolStudioTemplate::getInstance()->getIconUrl($aType['icon'])) == "")
            switch($sGroup) {
                case BX_DOL_STUDIO_STG_GROUP_MODULES:
                	$aType['icon'] = BxDolStudioUtils::getIconDefault(BX_DOL_MODULE_TYPE_MODULE);
                	break;

                case BX_DOL_STUDIO_STG_GROUP_LANGUAGES:
                	$aType['icon'] = BxDolStudioUtils::getIconDefault(BX_DOL_MODULE_TYPE_LANGUAGE);
                	break;

                case BX_DOL_STUDIO_STG_GROUP_TEMPLATES:
                    $aType['icon'] = BxDolStudioUtils::getIconDefault(BX_DOL_MODULE_TYPE_TEMPLATE);
                	break;
            }

        return $aType['icon'];
    }
}

/** @} */
