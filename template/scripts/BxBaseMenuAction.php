<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxTemplMenu');

/**
 * Actions menu for displaying some contex actions.
 * @see BxDolMenu
 */
class BxBaseMenuAction extends BxTemplMenu 
{
    protected $_sMenu = false;

    public function __construct ($aObject, $oTemplate) 
    {
        parent::__construct ($aObject, $oTemplate);
    }

    /**
     * Set current submenu object
     * @param $sMenuObject menu object name
     * @param $sForceMainMenuSelection force main menu item selection by meni item name
     */
    public function setActionsMenu ($sMenu)
    { 
        $this->_sMenu = $sMenu;
    }

    protected function _getTemplateVars () {
        if (!$this->_sMenu)
            return false;

        $a = parent::_getTemplateVars ();

        $oMenu = BxTemplMenu::getObjectInstance($this->_sMenu);
        $sMenu = $oMenu ? $oMenu->getCode () : '';

        $a['popup'] = BxTemplFunctions::getInstance()->transBox('<div class="bx-def-padding bx-def-color-bg-block">' . $sMenu . '</div>');
        
        return $a;
    }
    
}

/** @} */
