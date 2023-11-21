<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxBasePageConnections extends BxTemplPage
{
    public function __construct($aObject, $oTemplate)
    {
        parent::__construct($aObject, $oTemplate);

        $iProfileId = bx_get_logged_profile_id();
        if(!$iProfileId)
            return;

        if(($oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu')) !== false) {
            $sMenuSubmenu = 'sys_con_submenu';
            $oMenuSubmenu->setObjectSubmenu($sMenuSubmenu, [
                'title' => _t($this->_aObject['title']), 
                'link' => '', 
                'icon' => ''
            ]);
            $oMenuSubmenu->setDisplayAddons(true);
        }

        $this->addMarkers([
            'profile_id' => $iProfileId
        ]);
    }
}

/** @} */
