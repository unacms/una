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
bx_import('BxDolModule');

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

            if (isLogged()) {
                bx_import('BxDolConnection');
                $oConn = BxDolConnection::getObjectInstance('sys_profiles_friends');
                if ($oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $this->_oProfile->id()))
                    $this->addMarkers(array(
                        'title_add_friend' => _t('_bx_persons_menu_item_title_befriend_sent'), 
                        'title_remove_friend' => _t('_bx_persons_menu_item_title_unfriend_cancel_request'),
                    ));
                elseif ($oConn->isConnectedNotMutual($this->_oProfile->id(), bx_get_logged_profile_id()))
                    $this->addMarkers(array(
                        'title_add_friend' => _t('_bx_persons_menu_item_title_befriend_confirm'),
                        'title_remove_friend' => _t('_bx_persons_menu_item_title_unfriend_reject_request'),
                    ));
                else
                    $this->addMarkers(array(
                        'title_add_friend' => _t('_bx_persons_menu_item_title_befriend'),
                        'title_remove_friend' => _t('_bx_persons_menu_item_title_unfriend'),
                    ));
            }
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
//        if ('bx_persons_view_actions' == $this->_sObject && $this->_isSelected($a))
//            return false;

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
            case 'profile-friend-add':
                $sFuncCheckAccess = 'isAllowedFriendAdd';
                break;
            case 'profile-friend-remove':
                $sFuncCheckAccess = 'isAllowedFriendRemove';
                break;
            case 'profile-subscribe-add':
                $sFuncCheckAccess = 'isAllowedSubscribeAdd';
                break;
            case 'profile-subscribe-remove':
                $sFuncCheckAccess = 'isAllowedSubscribeRemove';
                break;
            case 'profile-actions-more':
                $sFuncCheckAccess = 'isAllowedViewMoreMenu';
                break;            
        }

        if (!$sFuncCheckAccess)
            return true;

        return $sFuncCheckAccess && $this->_oModule && CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->$sFuncCheckAccess($this->_aContentInfo) ? true : false;
    }

}

/** @} */
