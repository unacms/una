<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

bx_import('BxDolStudioTemplate');
bx_import('BxDolStudioSettings');
bx_import('BxTemplStudioFormView');

class BxBaseStudioSettings extends BxDolStudioSettings
{
    public function __construct($sType = '', $sCategory = '')
    {
        parent::__construct($sType, $sCategory);
    }
    public function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('forms.css'));
    }
    public function getPageJs()
    {
        return array_merge(parent::getPageJs(), array('settings.js'));
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

        $aCategories = array();
        $iCategories = $this->oDb->getCategories(array('type' => 'by_type_name_key_name', 'type_name' => $this->sType, 'category_name' => $this->sCategory, 'hidden' => 0), $aCategories);
        if($iCategories > 0)
            $aCategories = array_keys($aCategories);

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
                $aFields[$aOption['name']] = $this->field($aOption);

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

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            echo $this->saveChanges($oForm);
            exit;
        }

        return $oTemplate->parseHtmlByName('settings.html', array('js_object' => $this->getPageJsObject(), 'form' => $oForm->getCode()));
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

    protected function field($aItem)
    {
        $aField = array();
        switch($aItem['type']) {
            case 'digit':
                $aField = array(
                    'type' => 'text',
                    'name' => $aItem['name'],
                    'caption' => _t($aItem['caption']),
                    'value' => $aItem['value'],
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
                    'value' => $aItem['value'],
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
                    'checked' => $aItem['value'] == 'on',
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
                    'value' => !empty($aItem['value']) ? explode(',', $aItem['value']) : array(),
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
                    'value' => $aItem['value'],
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
                    'value' => $aItem['value'],
                    'db' => array (
                        'pass' => 'Xss'
                    )
                );
                break;
        }
        return $aField;
    }
    protected function getMenuIcon($sGroup, &$aType)
    {
        bx_import('BxDolStudioUtils');
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
