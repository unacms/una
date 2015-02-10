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
 * View entry menu
 */
class BxBaseModGeneralMenuView extends BxTemplMenu
{
    protected $MODULE;

    protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

    /**
     * Check if menu items is visible with extended checking linked to "allow*" method of particular module
     * Associated "allow*" method with particular menu item is stored in module config in MENU_ITEM_TO_METHOD array.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        // default visible settings
        if (!BxDolAcl::getInstance()->isMemberLevelInSet($a['visible_for_levels']))
            return false;

        $CNF = $this->_oModule->_oConfig->CNF;

        // get custom function name to check menu item visibility
        $sFuncCheckAccess = false;
        if (isset($CNF['MENU_ITEM_TO_METHOD'][$this->_sObject][$a['name']]))
            $sFuncCheckAccess = $CNF['MENU_ITEM_TO_METHOD'][$this->_sObject][$a['name']];

        // check custom visibility settings defined in module config class
        if ($sFuncCheckAccess && CHECK_ACTION_RESULT_ALLOWED !== call_user_func_array(array($this->_oModule, $sFuncCheckAccess), isset($this->_aContentInfo) ? array(&$this->_aContentInfo) : array()))
            return false;

        return true;
    }
}

/** @} */
