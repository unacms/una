<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Profile create/edit/delete pages.
 */
class BxBaseModProfilePageEntry extends BxBaseModGeneralPageEntry
{
    protected $_aProfileInfo;
    protected $_oProfile;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        // get profile info
        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if ($iProfileId)
            $this->_oProfile = BxDolProfile::getInstance($iProfileId);

        if (!$this->_oProfile && $iContentId)
            $this->_oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->MODULE);

        if ($this->_oProfile) {
            $this->_aProfileInfo = $this->_oProfile->getInfo();
            $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_aProfileInfo['content_id']);
        }

        if (!$this->_aContentInfo || !$this->_oProfile)
            return;

        // select view profile submenu
        $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
        $oMenuSubmenu->setObjectSubmenu($CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY'], array (
            'title' => $this->_oProfile->getDisplayName(),
            'link' => $this->_oProfile->getUrl(),
            'icon' => $CNF['ICON'],
        ));

        // add replaceable markers
        $this->addMarkers($this->_aProfileInfo); // every content field can be used as marker
        $this->addMarkers(array('profile_id' => $this->_oProfile->id())); // profile id field
        $this->addMarkers(array('display_name' => $this->_oProfile->getDisplayName())); // profile display name
        $this->addMarkers(array('profile_link' => $this->_oProfile->getUrl())); // profile link

        // display message if profile isn't active
        $aInformers = array ();
        $oInformer = BxDolInformer::getInstance($this->_oTemplate);
        if (bx_get_logged_profile_id() == $this->_oProfile->id() && !empty($CNF['INFORMERS']['status']) && $oInformer) {
            $sStatus = $this->_aContentInfo['profile_status'];
            if (isset($CNF['INFORMERS']['status']['map'][$sStatus]))
                $aInformers[] = array ('name' => $CNF['INFORMERS']['status']['name'], 'msg' => _t($CNF['INFORMERS']['status']['map'][$sStatus]), 'type' => BX_INFORMER_ALERT);
        }

        // display message if it is possible to switch to this profile
        if ($this->_oModule->serviceActAsProfile()) {
            $oProfile = $this->_aContentInfo ? BxDolProfile::getInstanceByContentTypeAccount($this->_aContentInfo['id'], $this->MODULE) : false;
            if ($oProfile)
                $oProfile->checkSwitchToProfile($this->_oTemplate);
        }

        // add informers
        if ($aInformers && $oInformer) {
            foreach ($aInformers as $a)
                $oInformer->add($a['name'], $this->_replaceMarkers($a['msg']), $a['type']);
        }

        // set cover
        $this->_oModule->_oTemplate->setCover($this->_aContentInfo);
    }

    protected function _processPermissionsCheck ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        bx_import('BxDolPrivacy');

        $mixedAllowView = $this->_oModule->checkAllowedView($this->_aContentInfo);
        if ($CNF['OBJECT_PAGE_VIEW_ENTRY'] == $this->_sObject) {
            if ((BX_DOL_PG_HIDDEN == $this->_aContentInfo['allow_view_to'] || BX_DOL_PG_MEONLY == $this->_aContentInfo['allow_view_to']) && CHECK_ACTION_RESULT_ALLOWED !== $mixedAllowView) {
                $this->_oTemplate->displayAccessDenied($mixedAllowView);
                exit;
            }
            elseif (CHECK_ACTION_RESULT_ALLOWED !== $mixedAllowView) {
                // replace current page with different set of blocks
                $aObject = BxDolPageQuery::getPageObject($CNF['OBJECT_PAGE_VIEW_ENTRY_CLOSED']);
                $this->_sObject = $aObject['object'];
                $this->_aObject = $aObject;
                $this->_oQuery = new BxDolPageQuery($this->_aObject);
            }
        } 
        elseif (CHECK_ACTION_RESULT_ALLOWED !== $mixedAllowView) {
            $this->_oTemplate->displayAccessDenied($mixedAllowView);
            exit;
        }

        $this->_oModule->checkAllowedView($this->_aContentInfo, true);
    }    
}

/** @} */
