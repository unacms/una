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

bx_import('BxTemplMenu');

/**
 * 'View Note' menu.
 */
class BxNotesMenuMy extends BxTemplMenu {

    protected $_oModule;

    public function __construct($aObject, $oTemplate = false) {
        parent::__construct($aObject, $oTemplate);

        bx_import('BxDolModule');
        $this->_oModule = BxDolModule::getInstance('bx_notes');
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */ 
    protected function _isVisible ($a) {

        $sFuncCheckAccess = false;
        switch ($a['name']) {
            case 'create-note':
                $sFuncCheckAccess = 'isAllowedAdd';
                break;
        }

        if ($sFuncCheckAccess && CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->$sFuncCheckAccess())
            return true;

        // default visible settings
        bx_import('BxDolAcl');
        return BxDolAcl::getInstance()->isMemberLevelInSet($a['visible_for_levels']);
    }
}

/** @} */
