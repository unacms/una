<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxBasePageHome
 */
class BxTemplPageHome extends BxBasePageHome
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
		$oMenuSubmenu->setObjectSubmenu('bx_lucid_homepage_submenu', array());

		$sSelName = 'home';
		if(bx_get('i') !== false)
		    $sSelName = bx_process_input(bx_get('i'));

		BxDolMenu::setSelectedGlobal('bx_lucid', $sSelName);
    }
}

/** @} */
