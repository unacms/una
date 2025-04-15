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
    function getPageMenu($aMenu = [], $aMarkers = [])
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = [];
        $aMenuItems = [
            BX_DOL_STUDIO_PRM_TYPE_LEVELS => ['icon' => 'mi-prm-levels.svg'],
            BX_DOL_STUDIO_PRM_TYPE_ACTIONS => ['icon' => 'mi-prm-actions.svg']
        ];

        foreach($aMenuItems as $sMenuItem => $aItem)
            $aMenu[] = [
                'name' => $sMenuItem,
                'icon' => $aItem['icon'],
                'icon_bg' => true,
                'link' => $this->sSubpageUrl . $sMenuItem,
                'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
                'selected' => $sMenuItem == $this->sPage
            ];

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
