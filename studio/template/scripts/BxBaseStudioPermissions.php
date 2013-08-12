<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolStudioPermissions');

class BxBaseStudioPermissions extends BxDolStudioPermissions {
    protected $sSubpageUrl;

    function BxBaseStudioPermissions($sPage = '') {
        parent::BxDolStudioPermissions($sPage);

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'builder_permissions.php?page=';
    }
    function getPageCss() {
        return array_merge(parent::getPageCss(), array('forms.css', 'paginate.css', 'permissions.css'));
    }
    function getPageJs() {
        return array_merge(parent::getPageJs(), array('permissions.js'));
    }
    function getPageJsObject() {
        return 'oBxDolStudioPermissions';
    }
    function getPageMenu($aMenu = array(), $aMarkers = array()) {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        $aMenuItems = array(
            BX_DOL_STUDIO_PRM_TYPE_LEVELS, 
            BX_DOL_STUDIO_PRM_TYPE_ACTIONS
        );

        foreach($aMenuItems as $sMenuItem)
            $aMenu[] = array(
                'name' => $sMenuItem,
                'icon' => 'mi-prm-' . $sMenuItem . '.png',
            	'link' => $this->sSubpageUrl . $sMenuItem,
            	'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
            	'selected' => $sMenuItem == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }
    function getPageCode($bHidden = false) {
        $sMethod = 'get' . ucfirst($this->sPage);
        if(!method_exists($this, $sMethod))
            return '';

        return $this->$sMethod();
    }

    protected function getLevels() {
        return $this->getGrid('sys_studio_acl');
    }

    protected function getActions() {
        return $this->getGrid('sys_studio_acl_actions');
    }

    protected function getPrices() {
        return $this->getGrid('sys_studio_acl_prices');
    }

    protected function getGrid($sObjectName) {
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

        return $oTemplate->parseHtmlByName('permissions.html', $aTmplVars);
    }
}
/** @} */
