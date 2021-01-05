<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioPermissions extends BxDolStudioPermissions
{
    protected $sSubpageUrl;

    function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'builder_permissions.php?page=';
    }
    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('forms.css', 'paginate.css', 'permissions.css'));
    }
    function getPageJs()
    {
        return array_merge(parent::getPageJs(), array('permissions.js'));
    }
    function getPageJsObject()
    {
        return 'oBxDolStudioPermissions';
    }
    function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        $aMenuItems = array(
            BX_DOL_STUDIO_PRM_TYPE_LEVELS => array('icon' => 'sliders-h'),
            BX_DOL_STUDIO_PRM_TYPE_ACTIONS => array('icon' => 'exchange-alt')
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

    protected function getLevels()
    {
        return $this->getGrid('sys_studio_acl');
    }

    protected function getActions()
    {
        return $this->getGrid('sys_studio_acl_actions');
    }

    protected function getPrices()
    {
        return $this->getGrid('sys_studio_acl_prices');
    }

    protected function getGrid($sObjectName)
    {
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('permissions.html', array(
            'js_object' => $this->getPageJsObject(),
            'content' => $oGrid->getCode()
        ));
    }
}

/** @} */
