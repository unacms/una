<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplMenu');

/**
 * General class for module menu.
 */
class BxBaseModTextMenu extends BxTemplMenu 
{
    protected $MODULE;

    protected $_oModule;
    protected $_aContentInfo;

    public function __construct($aObject, $oTemplate = false) 
    {
        parent::__construct($aObject, $oTemplate);

        bx_import('BxDolModule');
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */ 
    protected function _isVisible ($a) 
    {
        $CNF = $this->_oModule->_oConfig->CNF;
        $sFuncCheckAccess = false;

        // get custom function name to check menu item visibility
        if (isset($CNF['MENU_ITEM_TO_METHOD'][$this->_sObject][$a['name']]))
            $sFuncCheckAccess = $CNF['MENU_ITEM_TO_METHOD'][$this->_sObject][$a['name']];

        // check custom visibility settings defined in module config class
        if ($sFuncCheckAccess && CHECK_ACTION_RESULT_ALLOWED === call_user_func_array(array($this->_oModule, $sFuncCheckAccess), isset($this->_aContentInfo) ? array(&$this->_aContentInfo) : array()))
            return true;

        // default visible settings
        bx_import('BxDolAcl');
        return BxDolAcl::getInstance()->isMemberLevelInSet($a['visible_for_levels']);
    }
}

/** @} */
