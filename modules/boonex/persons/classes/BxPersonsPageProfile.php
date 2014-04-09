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

bx_import('BxTemplPage');
bx_import('BxDolModule');
bx_import('BxDolMenu');
bx_import('BxDolProfile');
bx_import('BxDolInformer');

/**
 * Profile create/edit/delete pages.
 */
class BxPersonsPageProfile extends BxTemplPage {    
    
    protected $_aContentInfo;
    protected $_aProfileInfo;
    protected $_oProfile;
    protected $_oProfileAuthor;

    protected $_aMapStatus2LangKey = array (
        BX_PROFILE_STATUS_PENDING => '_bx_persons_txt_account_pending',
        BX_PROFILE_STATUS_SUSPENDED => '_bx_persons_txt_account_suspended',
    );

    public function __construct($aObject, $oTemplate = false) {
        parent::__construct($aObject, $oTemplate);

        $aInformers = array ();

        // get profile info
        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if ($iProfileId)
            $this->_oProfile = BxDolProfile::getInstance($iProfileId);

        if (!$this->_oProfile && $iContentId)
            $this->_oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, 'bx_persons');

        if ($this->_oProfile) {
            $this->_aProfileInfo = $this->_oProfile->getInfo();
            $oModuleMain = BxDolModule::getInstance('bx_persons');
            $this->_aContentInfo = $oModuleMain->_oDb->getContentInfoById($this->_aProfileInfo['content_id']);
            $this->_oProfileAuthor = $this->_aContentInfo ? BxDolProfile::getInstance($this->_aContentInfo[BxPersonsConfig::$FIELD_AUTHOR]) : false;
        }

        if (!$this->_aContentInfo || !$this->_oProfile)
            return;

        // select view profile submenu 
        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        $oMenuSubmenu->setObjectSubmenu('bx_persons_view_submenu', array (
            'title' => $this->_oProfile->getDisplayName(),
            'link' => $this->_oProfile->getUrl(),
            'icon' => $this->_oProfile->getIcon(),
        ));

        // add replaceable markers
        $this->addMarkers($this->_aProfileInfo); // every content field can be used as marker
        $this->addMarkers(array('profile_id' => $this->_oProfile->id())); // profile id field is also suported
        $this->addMarkers(array('display_name' => $this->_oProfile->getDisplayName())); // profile display name is also suported

        // display message if profile isn't active
        if (bx_get_logged_profile_id() == $this->_oProfileAuthor->id()) { 
            $sStatus = $this->_aContentInfo['profile_status'];
            if (isset($this->_aMapStatus2LangKey[$sStatus])) {
                $aInformers[] = array ('name' => 'bx-persons-status-not-active', 'msg' => _t($this->_aMapStatus2LangKey[$sStatus]), 'type' => BX_INFORMER_ALERT);
            }
        }
/*
        // display pending connetion requests
        if (isLogged()) {
            $aInformer = false;
            bx_import('BxDolConnection');
            $oConn = BxDolConnection::getObjectInstance('sys_profiles_friends');
            if ($oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $this->_oProfile->id()))
                $aInformer = array ('name' => 'bx-persons-friend-pending-initiator', 'msg' => _t('_bx_persons_txt_friend_pending_initiator'), 'type' => BX_INFORMER_ALERT);
            elseif ($oConn->isConnectedNotMutual($this->_oProfile->id(), bx_get_logged_profile_id()))
                $aInformer = array ('name' => 'bx-persons-friend-pending-content', 'msg' => _t('_bx_persons_txt_friend_pending_content'), 'type' => BX_INFORMER_ALERT);
            if ($aInformer)
                $aInformers[] = $aInformer;
        }
*/
        // display message if it is possible to switch to this profile
        $oProfile = $this->_aContentInfo ? BxDolProfile::getInstanceByContentTypeAccount($this->_aContentInfo['id'], 'bx_persons') : false;
        if ($oProfile)
            $oProfile->checkSwitchToProfile($this->_oTemplate);

        // add informers
        if ($aInformers) {            
            $oInformer = BxDolInformer::getInstance($this->_oTemplate);
            if ($oInformer) {
                foreach ($aInformers as $a)
                    $oInformer->add($a['name'], $this->_replaceMarkers($a['msg']), $a['type']);
            }
        }

		bx_import('BxDolView');
		BxDolView::getObjectInstance(BxPersonsConfig::$OBJECT_VIEWS, $iContentId)->doView();
    }

    public function getCode () {
        if (!$this->_aContentInfo) { // if profile is not found - display standard "404 page not found" page
            $this->_oTemplate->displayPageNotFound();
            exit;
        }
        return parent::getCode ();
    }

    protected function _getPageCacheParams () {
        return $this->_aContentInfo['id']; // cache is different for every account
    }

}

/** @} */
