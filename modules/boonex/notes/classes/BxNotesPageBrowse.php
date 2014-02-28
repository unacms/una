<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notes Notes
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModule');
bx_import('BxTemplPage');

/**
 * Browse entries pages.
 */
class BxNotesPageBrowse extends BxTemplPage 
{   
    protected static $MODULE = 'bx_notes';
 
    public function __construct($aObject, $oTemplate = false) 
    {
        parent::__construct($aObject, $oTemplate);

        $oModule = BxDolModule::getInstance(self::$MODULE);

        // select module submenu
        bx_import('BxDolMenu');
        $oMenuSumbemu = BxDolMenu::getObjectInstance('sys_site_submenu');
        $oMenuSumbemu->setObjectSubmenu($oModule->_oConfig->CNF['OBJECT_MENU_SUBMENU']);
    }

}

/** @} */
