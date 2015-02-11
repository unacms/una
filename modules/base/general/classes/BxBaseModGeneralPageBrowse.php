<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Browse entries pages.
 */
class BxBaseModGeneralPageBrowse extends BxTemplPage
{
    protected $MODULE;

    protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);

        // select module submenu
        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        $oMenuSubmenu->setObjectSubmenu($this->_oModule->_oConfig->CNF['OBJECT_MENU_SUBMENU']);
    }

}

/** @} */
