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
class BxNotesMenuViewNote extends BxTemplMenu {

    protected $_aContentInfo;
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false) {
        parent::__construct($aObject, $oTemplate);

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        bx_import('BxDolModule');
        $this->_oModule = BxDolModule::getInstance('bx_notes');
        $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if ($this->_aContentInfo)
            $this->addMarkers(array('content_id' => $this->_aContentInfo['id']));
    }

    public function getCode () {
        if (!bx_get_logged_profile_id())
            return false;
        return parent::getCode ();
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */ 
    protected function _isVisible ($a) {

        $iProfileId = bx_get_logged_profile_id();

        // all links are visible for owner
        if ($this->_aContentInfo['author'] == $iProfileId)
            return true;

        $sFuncCheckAccess = false;
        switch ($a['name']) {
            case 'view-note':
                $sFuncCheckAccess = 'isAllowedView';
                break;
            case 'edit-note':
                $sFuncCheckAccess = 'isAllowedEdit';
                break;
            case 'delete-note':
                $sFuncCheckAccess = 'isAllowedDelete';
                break;
        }

        if ($sFuncCheckAccess && CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->$sFuncCheckAccess($this->_aContentInfo))
            return true;

        // default visible settings
        bx_import('BxDolAcl');
        return BxDolAcl::getInstance()->isMemberLevelInSet($a['visible_for_levels']);
    }

    protected function _addJsCss() {
        parent::_addJsCss();
        $this->_oModule->_oTemplate->addJs('forms.js');
    }

}

/** @} */
