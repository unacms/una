<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAudit extends BxDolStudioAudit
{
    protected $sSubpageUrl;
    protected $aMenuItems;
    protected $aGridObjects;

    function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'audit.php?page=';

        $this->aMenuItems = array(
            BX_DOL_STUDIO_AUD_TYPE_GENERAL => array('icon' => 'search'),
            BX_DOL_STUDIO_AUD_TYPE_SETTINGS => array('icon' => 'cogs'),
        );
    }
	
    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('forms.css', 'paginate.css'));
    }
	
    function getPageJs()
    {
        return array_merge(parent::getPageJs(), array('settings.js'));
    }

    function getPageJsCode($aOptions = array(), $bWrap = true)
    {
        $aOptions = array_merge($aOptions, array(
            'sActionUrl' => BX_DOL_URL_STUDIO . 'audit.php'
        ));

        return parent::getPageJsCode($aOptions, $bWrap);
    }
    
    function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        foreach($this->aMenuItems as $sMenuItem => $aItem)
            $aMenu[] = array(
                'name' => $sMenuItem,
                'icon' => $aItem['icon'],
                'link' => $this->sSubpageUrl . $sMenuItem,
                'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
                'selected' => $sMenuItem == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }
    
    protected function getGeneral()
    {
        return $this->getGrid();
    }
    
    protected function getSettings()
    {
        $oPage = new BxTemplStudioSettings(BX_DOL_STUDIO_STG_TYPE_SYSTEM, BX_DOL_STUDIO_STG_CATEGORY_AUDIT);
        
        return BxDolStudioTemplate::getInstance()->parseHtmlByName('audit.html', array(
            'content' => $oPage->getFormCode(),
            'js_content' => ''
        ));
    }

    protected function getGrid()
    {
        $oGrid = BxDolGrid::getObjectInstance('sys_audit_administration');
        if(!$oGrid)
            return '';

        $oTemplate = BxDolStudioTemplate::getInstance();
        $oTemplate->addJs(array('BxDolAuditManageTools.js', 'BxDolGrid.js', 'jquery.form.min.js', 'jquery-ui/jquery-ui.custom.min.js' , 'jquery-ui/jquery.ui.sortable.min.js'));
        //$oForm = new BxTemplStudioFormView(array());
        $oTemplate->addCss('grid.css');
        $oTemplate->addJsTranslation(array('_sys_grid_search'));

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('audit.html', array(
            'content' => $oGrid->getCode(),
            'js_content' => ''
        ));
    }
}

/** @} */
