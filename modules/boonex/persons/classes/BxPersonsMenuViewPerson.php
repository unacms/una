<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplMenu');

/**
 * 'View person' menu.
 */
class BxPersonsMenuViewPerson extends BxTemplMenu {

    protected $_aContentInfo;

    public function __construct($aObject, $oTemplate = false) {
        parent::__construct($aObject, $oTemplate);

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        $oModuleMain = BxDolModule::getInstance('bx_persons');
        $this->_aContentInfo = $oModuleMain->_oDb->getContentInfoById($iContentId);
        if ($this->_aContentInfo)
            $this->addMarkers(array('content_id' => $this->_aContentInfo['id']));
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */ 
    protected function _isVisible ($a) {

        bx_import('BxDolProfile');
        $oAccountProfile = BxDolProfile::getInstanceAccountProfile();
        $iAccountProfileId = $oAccountProfile->id();

        // TODO: separate checking for every menu item

        // all links are visible for owner
        if ($this->_aContentInfo['author'] == $iAccountProfileId)
            return true;

        // all links are visible for admin/moderator
        $aCheck = checkActionModule($iAccountProfileId, 'edit any person profile', 'bx_persons'); 
        if ($aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)
            return true;

        return false; // TODO: since default visible settings are overriden - hide it from builder.

        // default visible settings
        bx_import('BxDolAcl');
        return BxDolAcl::getInstance()->isMemberLevelInSet($a['visible_for_levels']);
    }

}

/** @} */
