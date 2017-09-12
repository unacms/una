<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

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
	        'pre_values' => 'sys_studio_forms_pre_values',
        	'search_forms' => 'sys_studio_search_forms',
        	'search_fields' => 'sys_studio_search_forms_fields',
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
            BX_DOL_STUDIO_FORM_TYPE_SEARCH_FORMS => array('icon' => 'search'),
            BX_DOL_STUDIO_FORM_TYPE_SEARCH_FIELDS => array('icon' => 'check-square'),
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

    function actionGetSearchForms()
    {
        if(($sModule = bx_get('form_module')) === false)
            return array('code' => 2, 'message' => _t('_adm_form_err_missing_params'));

        $sModule = bx_process_input($sModule);
        return array('code' => 0, 'message' => '', 'content' => $this->getSearchFieldsObject()->getFormsSelector($sModule));
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

    protected function getSearchForms()
    {
        return $this->getGrid($this->aGridObjects['search_forms']);
    }

    protected function getSearchFormsObject()
    {
        return $this->getGridObject($this->aGridObjects['search_forms']);
    }

    protected function getSearchFields()
    {
        return $this->getGrid($this->aGridObjects['search_fields']);
    }

    protected function getSearchFieldsObject()
    {
        return $this->getGridObject($this->aGridObjects['search_fields']);
    }

    protected function getGridObject($sObjectName)
    {
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        return $oGrid;
    }

    protected function getGrid($sObjectName)
    {
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('forms.html', array(
            'js_object' => $this->getPageJsObject(),
            'content' => $this->getBlockCode(array(
				'items' => $oGrid->getCode()
			))
        ));
    }

    protected function actionValuesList()
    {
        if(($sList = bx_get('form_list')) === false)
            return array();

        $sList = bx_process_input($sList);
        $bUseForSets = (int)bx_get('form_use_for_sets') == 1;

        $aValues = BxDolForm::getDataItems(trim($sList, BX_DATA_LISTS_KEY_PREFIX . ' '), $bUseForSets);

        $aTmplVars = array(
            array('value' => '', 'title' => _t('_adm_form_txt_field_value_select_value'))
        );
        foreach($aValues as $mixedValue => $sTitle)
            $aTmplVars[] = array('value' => $mixedValue, 'title' => $sTitle);

        return array('content' => BxDolStudioTemplate::getInstance()->parseHtmlByName('forms_select.html', array('bx_repeat:options' => $aTmplVars)));
    }
}

/** @} */
