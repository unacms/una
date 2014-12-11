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

bx_import('BxBaseModGeneralMenuView');
bx_import('BxDolProfile');

/**
 * View profile entry menu
 */
class BxBaseModProfileMenuView extends BxBaseModGeneralMenuView
{
    protected $_oProfile;
    protected $_aContentInfo;
    protected $_aProfileInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $CNF = $this->_oModule->_oConfig->CNF;

        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if ($iProfileId)
            $this->_oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$this->_oProfile && $iContentId)
            $this->_oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->MODULE);

        if ($this->_oProfile) {
            $this->_aProfileInfo = $this->_oProfile->getInfo();

            $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_aProfileInfo['content_id']);

            $this->addMarkers($this->_aProfileInfo);
            $this->addMarkers(array('profile_id' => $this->_oProfile->id()));

            if (isLogged()) {
                bx_import('BxDolConnection');
                $oConn = BxDolConnection::getObjectInstance('sys_profiles_friends');
                if ($oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $this->_oProfile->id())) {
                    $this->addMarkers(array(
                        'title_add_friend' => _t($CNF['T']['menu_item_title_befriend_sent']),
                        'title_remove_friend' => _t($CNF['T']['menu_item_title_unfriend_cancel_request']),
                    ));
                } elseif ($oConn->isConnectedNotMutual($this->_oProfile->id(), bx_get_logged_profile_id())) {
                    $this->addMarkers(array(
                        'title_add_friend' => _t($CNF['T']['menu_item_title_befriend_confirm']),
                        'title_remove_friend' => _t($CNF['T']['menu_item_title_unfriend_reject_request']),
                    ));
                } else {
                    $this->addMarkers(array(
                        'title_add_friend' => _t($CNF['T']['menu_item_title_befriend']),
                        'title_remove_friend' => _t($CNF['T']['menu_item_title_unfriend']),
                    ));
                }
            }
        }
    }

}

/** @} */
