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
bx_import('BxDolProfile');

/**
 * 'View person' menu.
 */
class BxPersonsMenuViewPerson extends BxTemplMenu {

    protected $_oProfile;
    protected $_aContentInfo;
    protected $_aProfileInfo;
    protected $_oModule;

    public function __construct($aObject, $oTemplate = false) {
        parent::__construct($aObject, $oTemplate);

        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if ($iProfileId)
            $this->_oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$this->_oProfile && $iContentId)
            $this->_oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, 'bx_persons');

        if ($this->_oProfile) {
            $this->_aProfileInfo = $this->_oProfile->getInfo();

            $this->_oModule = BxDolModule::getInstance('bx_persons');
            $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_aProfileInfo['content_id']);

            $this->addMarkers($this->_aProfileInfo);
            $this->addMarkers(array('profile_id' => $this->_oProfile->id()));
        }
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */ 
    protected function _isVisible ($a) {

        // default visible settings
        bx_import('BxDolAcl');
        if (!BxDolAcl::getInstance()->isMemberLevelInSet($a['visible_for_levels']))
            return false;

        // don't show current item, also this will solve problem when only one view note item is visible
        if ('bx_persons_view' == $this->_sObject && $this->_isSelected($a))
            return false;

        $sFuncCheckAccess = false;
        switch ($a['name']) {
            case 'view-persons-profile':
                $sFuncCheckAccess = 'isAllowedView';
                break;
            case 'edit-persons-profile':
                $sFuncCheckAccess = 'isAllowedEdit';
                break;
            case 'delete-persons-profile':
                $sFuncCheckAccess = 'isAllowedDelete';
                break;
            case 'profile-connect-add':
                $sFuncCheckAccess = 'isAllowedConnectAdd';
                break;
            case 'profile-connect-remove':
                $sFuncCheckAccess = 'isAllowedConnectRemove';
                break;
            case 'profile-subscribe-add':
                $sFuncCheckAccess = 'isAllowedSubscribeAdd';
                break;
            case 'profile-subscribe-remove':
                $sFuncCheckAccess = 'isAllowedSubscribeRemove';
                break;
        }

        if (!$sFuncCheckAccess)
            return true;

        return $sFuncCheckAccess && CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->$sFuncCheckAccess($this->_aContentInfo) ? true : false;
    }

}

/** @} */
