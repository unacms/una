<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     TridentModules
 * 
 * @{
 */

class BxBaseModGroupsGridConnections extends BxDolGridConnections
{
    protected $_iGroupProfileId;
    protected $_oModule;
    protected $_oConnection;
    protected $_aContentInfo = array();

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_oModule = BxDolModule::getInstance($this->_sContentModule);
        $this->_sObjectConnections = $this->_oModule->_oConfig->CNF['OBJECT_CONNECTIONS'];

        parent::__construct ($aOptions, $oTemplate);

        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId)
            return;

        $oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$oProfile)
            return;

        $aProfileInfo = $oProfile->getInfo();
        $this->_iGroupProfileId = $oProfile->id();

        $this->_aContentInfo = BxDolService::call($aProfileInfo['type'], 'get_content_info_by_id', array($aProfileInfo['content_id']));
        if (CHECK_ACTION_RESULT_ALLOWED === $this->_oModule->checkAllowedEdit($this->_aContentInfo) || $oProfile->id() == bx_get_logged_profile_id())
            $this->_bOwner = true;

        $aSQLParts = $this->_oConnection->getConnectedInitiatorsAsSQLParts('p', 'id', $this->_iGroupProfileId, $this->_bOwner ? false : true);
        $this->addMarkers(array(
            'profile_id' => $oProfile->id(),
            'join_connections' => $aSQLParts['join'],
            'content_module' => $this->_sContentModule,
        ));
    }

    protected function _getActionAccept ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (isset($aRow[$this->_aOptions['field_id']]))
            $a['attr']['bx_grid_action_data'] = $aRow[$this->_aOptions['field_id']] . ':' . $this->_iGroupProfileId;

        return parent::_getActionAccept ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (isset($aRow[$this->_aOptions['field_id']]))
            $a['attr']['bx_grid_action_data'] = $aRow[$this->_aOptions['field_id']] . ':' . $this->_iGroupProfileId;

        return parent::_getActionDelete ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionToAdmins ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if ($this->_oModule->_oDb->isAdmin($this->_iGroupProfileId, $aRow[$this->_aOptions['field_id']]))
            return '';

        return $this->_getAction_Admins ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionFromAdmins ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!$this->_oModule->_oDb->isAdmin($this->_iGroupProfileId, $aRow[$this->_aOptions['field_id']]))
            return '';

        return $this->_getAction_Admins ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getAction_Admins ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!$this->_oConnection->isConnected($aRow[$this->_aOptions['field_id']], $this->_iGroupProfileId, true))
            return '';

        if (CHECK_ACTION_RESULT_ALLOWED !== $this->_oModule->checkAllowedManageAdmins($this->_iGroupProfileId))
            return '';

        if (isset($aRow[$this->_aOptions['field_id']]))
            $a['attr']['bx_grid_action_data'] = $aRow[$this->_aOptions['field_id']] . ':' . $this->_iGroupProfileId;

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    /**
     * 'To Admins' action handler
     */
    public function performActionToAdmins()
    {
        $this->_performActionAdmins('toAdmins');
    }

    /**
     * 'From Admins' action handler
     */
    public function performActionFromAdmins()
    {
        $this->_performActionAdmins('fromAdmins');        
    }

    public function _performActionAdmins($sFunc)
    {
        list ($iId, $iGroupProfileId) = $this->_prepareIds();

        if (!$iId) {
            echoJson(array('msg' => _t('_sys_txt_error_occured')));
            exit;
        }

        if (CHECK_ACTION_RESULT_ALLOWED !== $this->_oModule->checkAllowedManageAdmins($iGroupProfileId)) {
            echoJson(array('msg' => _t('_sys_txt_access_denied')));
            exit;
        }
    
        if (!$this->_oModule->_oDb->$sFunc($iGroupProfileId, $iId)) {
            echoJson(array('msg' => _t('_sys_txt_error_occured')));
        } 
        else {

            $sEmailTemplate = 'toAdmins' == $sFunc ? $this->_oModule->_oConfig->CNF['EMAIL_FAN_BECOME_ADMIN'] : $this->_oModule->_oConfig->CNF['EMAIL_ADMIN_BECOME_FAN'];
            list($iGroupProfileId, $iProfileId) = $this->_prepareGroupProfileAndMemberProfile($iGroupProfileId, $iId);
            if (bx_get_logged_profile_id() != $iProfileId) {
                // notify about admin status
                sendMailTemplate($sEmailTemplate, 0, $iProfileId, array(
                    'EntryUrl' => BxDolProfile::getInstance($iGroupProfileId)->getUrl(),
                    'EntryTitle' => BxDolProfile::getInstance($iGroupProfileId)->getDisplayName(),
                ), BX_EMAIL_NOTIFY);
            }

            // subscribe admins automatically
            if ('toAdmins' == $sFunc && ($oConn = BxDolConnection::getObjectInstance('sys_profiles_subscriptions')))
                $oConn->addConnection($iProfileId, $iGroupProfileId);

            echoJson(array('grid' => $this->getCode(false), 'blink' => $iId));
        }
    }

    protected function _delete ($mixedId)
    {
        list ($iId, $iViewedId) = $this->_prepareIds();

        // send email notification
        $sEmailTemplate = $this->_oConnection->isConnected($iViewedId, $iId, true) ? $this->_oModule->_oConfig->CNF['EMAIL_FAN_REMOVE'] : $this->_oModule->_oConfig->CNF['EMAIL_JOIN_REJECT'];
        list($iGroupProfileId, $iProfileId) = $this->_prepareGroupProfileAndMemberProfile($iId, $iViewedId);
        if (bx_get_logged_profile_id() != $iProfileId) {
            sendMailTemplate($sEmailTemplate, 0, $iProfileId, array(
                'EntryUrl' => BxDolProfile::getInstance($iGroupProfileId)->getUrl(),
                'EntryTitle' => BxDolProfile::getInstance($iGroupProfileId)->getDisplayName(),
            ), BX_EMAIL_NOTIFY);
        }

        // delete admin associated with profile
        $this->_oModule->_oDb->deleteAdminsByProfileId($iProfileId);

        return parent::_delete ($mixedId);
    }

    /**
     * @return array where first element is group profile id and second element is some other persons profile id
     */
    protected function _prepareGroupProfileAndMemberProfile($iId1, $iId2)
    {
        if (BxDolProfile::getInstance($iId1)->getModule() == $this->_sContentModule)
            return array($iId1, $iId2);
        else
            return array($iId2, $iId1);
    }
}

/** @} */
