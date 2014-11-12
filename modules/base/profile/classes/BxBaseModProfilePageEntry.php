<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModGeneralPageEntry');
bx_import('BxDolModule');
bx_import('BxDolMenu');
bx_import('BxDolProfile');
bx_import('BxDolInformer');

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

        $aInformers = array ();

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
            'icon' => $this->_oProfile->getIcon(),
        ));

        // add replaceable markers
        $this->addMarkers($this->_aProfileInfo); // every content field can be used as marker
        $this->addMarkers(array('profile_id' => $this->_oProfile->id())); // profile id field is also suported
        $this->addMarkers(array('display_name' => $this->_oProfile->getDisplayName())); // profile display name is also suported

        // display message if profile isn't active
        if (bx_get_logged_profile_id() == $this->_oProfile->id() && !empty($CNF['INFORMERS']['status'])) {
            $sStatus = $this->_aContentInfo['profile_status'];
            if (isset($CNF['INFORMERS']['status']['map'][$sStatus]))
                $aInformers[] = array ('name' => $CNF['INFORMERS']['status']['name'], 'msg' => _t($CNF['INFORMERS']['status']['map'][$sStatus]), 'type' => BX_INFORMER_ALERT);
        }

        // display message if it is possible to switch to this profile
        $oProfile = $this->_aContentInfo ? BxDolProfile::getInstanceByContentTypeAccount($this->_aContentInfo['id'], $this->MODULE) : false;
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
    }

}

/** @} */
