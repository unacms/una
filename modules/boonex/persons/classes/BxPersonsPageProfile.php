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

/**
 * Profile create/edit/delete pages.
 */
class BxPersonsPageProfile extends BxTemplPage {    
    
    protected $_aContentInfo;
    protected $_oProfile;
    protected $_oProfileAuthor;

    protected $_aMapStatus2LangKey = array (
        BX_PROFILE_STATUS_PENDING => '_bx_persons_txt_account_pending',
        BX_PROFILE_STATUS_SUSPENDED => '_bx_persons_txt_account_suspended',
    );

    public function __construct($aObject, $oTemplate = false) {
        parent::__construct($aObject, $oTemplate);

        // get profile info
        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if ($iContentId) {
            $oModuleMain = BxDolModule::getInstance('bx_persons');
            $this->_aContentInfo = $oModuleMain->_oDb->getContentInfoById($iContentId);
            $this->_oProfileAuthor = $this->_aContentInfo ? BxDolProfile::getInstance($this->_aContentInfo[BxPersonsConfig::$FIELD_AUTHOR]) : false;
            $this->_oProfile = $this->_oProfileAuthor ? BxDolProfile::getInstanceByContentTypeAccount($iContentId, 'bx_persons', $this->_oProfileAuthor->getAccountId()) : false;
        }

        if (!$this->_aContentInfo || !$this->_oProfile)
            return;

        // select view note submenu        
        $oMenuSumbemu = BxDolMenu::getObjectInstance('sys_site_submenu');
        $oMenuSumbemu->setObjectSubmenu('bx_persons_view_submenu', array (
            'title' => $this->_oProfile->getDisplayName(),
            'link' => $this->_oProfile->getUrl(),
            'icon' => $this->_oProfile->getIcon(),
        ));

        // set actions menu
        $oMenuAction = BxDolMenu::getObjectInstance('sys_site_action');
        $oMenuAction->setActionsMenu('bx_persons_view');        

        // add replacible markers
        $this->addMarkers($this->_aContentInfo); // every profile field can be used as marker

        // display message if profile isn't active
        if (bx_get_logged_profile_id() == $this->_oProfileAuthor->id()) { 
            $sStatus = $this->_aContentInfo['profile_status'];
            if (isset($this->_aMapStatus2LangKey[$sStatus])) {
                bx_import('BxDolInformer');
                $oInformer = BxDolInformer::getInstance($this->_oTemplate);
                if ($oInformer)
                    $oInformer->add('bx-persons-status-not-active', _t($this->_aMapStatus2LangKey[$sStatus]), BX_INFORMER_ALERT);
            }
        }

        // display message if it is possible to switch to this profile
        $oProfile = $this->_aContentInfo ? BxDolProfile::getInstanceByContentTypeAccount($this->_aContentInfo['id'], 'bx_persons') : false;
        if ($oProfile)
            $oProfile->checkSwitchToProfile($this->_oTemplate);
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
