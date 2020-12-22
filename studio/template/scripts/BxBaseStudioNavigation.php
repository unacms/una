<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioNavigation extends BxDolStudioNavigation
{
    protected $sSubpageUrl;
    protected $aGridObjects;

    function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'builder_menu.php?page=';

        $this->aGridObjects = array(
	        'menus' => 'sys_studio_nav_menus',
	        'sets' => 'sys_studio_nav_sets',
	        'items' => 'sys_studio_nav_items'
	    );
    }
    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('forms.css', 'paginate.css', 'navigation.css'));
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
            BX_DOL_STUDIO_NAV_TYPE_MENUS => array('icon' => 'sitemap'),
            BX_DOL_STUDIO_NAV_TYPE_SETS => array('icon' => 'align-justify'),
            BX_DOL_STUDIO_NAV_TYPE_ITEMS => array('icon' => 'indent')
        );
        foreach($aMenuItems as $sMenuItem => $aItem)
            $aMenu[] = array(
                'name' => $sMenuItem,
                'icon' => $aItem['icon'],
                'link' => $this->sSubpageUrl . $sMenuItem,
                'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
                'selected' => $sMenuItem == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }

    function actionGetSets()
    {
        if(($sModule = bx_get('nav_module')) === false)
            return array('code' => 2, 'message' => _t('_adm_nav_err_missing_params'));

        $sModule = bx_process_input($sModule);
        return array('code' => 0, 'message' => '', 'content' => $this->getItemsObject()->getSetsSelector($sModule));
    }

    protected function getMenus()
    {
        return $this->getGrid($this->aGridObjects['menus']);
    }

    protected function getSets()
    {
        return $this->getGrid($this->aGridObjects['sets']);
    }

    protected function getItems()
    {
        return $this->getGrid($this->aGridObjects['items']);
    }

    protected function getItemsObject()
    {
        return $this->getGridObject($this->aGridObjects['items']);
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

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('navigation.html', array(
            'js_object' => $this->getPageJsObject(),
            'content' => $oGrid->getCode()
        ));
    }
}

/** @} */
