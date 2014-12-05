<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */

bx_import('BxDolStudioForms');

class BxBaseStudioForms extends BxDolStudioForms
{
    protected $sSubpageUrl;
    protected $aGridObjects;

    function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'builder_forms.php?page=';

        $this->aGridObjects = array(
	        'forms' => 'sys_studio_forms',
	        'displays' => 'sys_studio_forms_displays',
	        'fields' => 'sys_studio_forms_fields',
	        'pre_lists' => 'sys_studio_forms_pre_lists',
	        'pre_values' => 'sys_studio_forms_pre_values'
    	);
    }
    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('forms.css', 'paginate.css', 'builder_forms.css'));
    }
    function getPageJs()
    {
        return array_merge(parent::getPageJs(), array());
    }
    function getPageJsObject()
    {
        return '';
    }
    function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        $aMenuItems = array(
            BX_DOL_STUDIO_FORM_TYPE_FORMS => array('icon' => 'list-alt'),
            BX_DOL_STUDIO_FORM_TYPE_DISPLAYS => array('icon' => 'desktop'),
            BX_DOL_STUDIO_FORM_TYPE_FIELDS => array('icon' => 'check-square'),
            BX_DOL_STUDIO_FORM_TYPE_PRE_LISTS => array('icon' => 'align-justify'),
            BX_DOL_STUDIO_FORM_TYPE_PRE_VALUES => array('icon' => 'indent'),
        );
        foreach($aMenuItems as $sMenuItem => $aItem)
            $aMenu[] = array(
                'name' => $sMenuItem,
                'icon' => $aItem['icon'], 'mi-form-' . $sMenuItem . '.png',
                'link' => $this->sSubpageUrl . $sMenuItem,
                'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
                'selected' => $sMenuItem == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }
    function getPageCode($bHidden = false)
    {
        $sMethod = 'get' .  $this->getClassName($this->sPage);
        if(!method_exists($this, $sMethod))
            return '';

        return $this->$sMethod();
    }

    function actionGetForms()
    {
        if(($sModule = bx_get('form_module')) === false)
            return array('code' => 2, 'message' => _t('_adm_form_err_missing_params'));

        $sModule = bx_process_input($sModule);
        return array('code' => 0, 'message' => '', 'content' => $this->getDisplaysObject()->getFormsSelector($sModule));
    }

    function actionGetDisplays()
    {
        if(($sModule = bx_get('form_module')) === false)
            return array('code' => 2, 'message' => _t('_adm_form_err_missing_params'));

        $sModule = bx_process_input($sModule);
        return array('code' => 0, 'message' => '', 'content' => $this->getFieldsObject()->getDisplaysSelector($sModule));
    }

    function actionGetLists()
    {
        if(($sModule = bx_get('form_module')) === false)
            return array('code' => 2, 'message' => _t('_adm_form_err_missing_params'));

        $sModule = bx_process_input($sModule);
        return array('code' => 0, 'message' => '', 'content' => $this->getPreValuesObject()->getListsSelector($sModule));
    }

    protected function getForms()
    {
        return $this->getGrid($this->aGridObjects['forms']);
    }

    protected function getFormsObject()
    {
        return $this->getGridObject($this->aGridObjects['forms']);
    }

    protected function getDisplays()
    {
        return $this->getGrid($this->aGridObjects['displays']);
    }

    protected function getDisplaysObject()
    {
        return $this->getGridObject($this->aGridObjects['displays']);
    }

    protected function getFields()
    {
        return $this->getGrid($this->aGridObjects['fields']);
    }

    protected function getFieldsObject()
    {
        return $this->getGridObject($this->aGridObjects['fields']);
    }

    protected function getPreLists()
    {
        return $this->getGrid($this->aGridObjects['pre_lists']);
    }

    protected function getPreValues()
    {
        return $this->getGrid($this->aGridObjects['pre_values']);
    }

    protected function getPreValuesObject()
    {
        return $this->getGridObject($this->aGridObjects['pre_values']);
    }

    protected function getGridObject($sObjectName)
    {
        bx_import('BxDolGrid');
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        return $oGrid;
    }

    protected function getGrid($sObjectName)
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        bx_import('BxDolGrid');
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        $aTmplVars = array(
            'js_object' => $this->getPageJsObject(),
            'bx_repeat:blocks' => array(
                array(
                    'caption' => '',
                    'panel_top' => '',
                    'items' => $oGrid->getCode(),
                    'panel_bottom' => ''
                )
            )
        );

        return $oTemplate->parseHtmlByName('forms.html', $aTmplVars);
    }

    protected function actionValuesList()
    {
        if(($sList = bx_get('form_list')) === false)
            return array();

        $sList = bx_process_input($sList);
        $bUseForSets = (int)bx_get('form_use_for_sets') == 1;

        bx_import('BxDolForm');
        $aValues = BxDolForm::getDataItems(trim($sList, BX_DATA_LISTS_KEY_PREFIX . ' '), $bUseForSets);

        $aTmplVars = array();
        foreach($aValues as $mixedValue => $sTitle)
            $aTmplVars[] = array('value' => $mixedValue, 'title' => $sTitle);

        return array('content' => BxDolStudioTemplate::getInstance()->parseHtmlByName('forms_select.html', array('bx_repeat:options' => $aTmplVars)));
    }
}

/** @} */
