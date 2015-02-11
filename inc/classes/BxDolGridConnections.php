<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxDolGridConnections extends BxTemplGrid
{
    protected $_bOwner = false;
    protected $_sContentModule = 'bx_persons';
    protected $_sObjectConnections = 'sys_profiles_friends';

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
        $this->_sDefaultSortingOrder = 'DESC';

        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId)
            return;

        $oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$oProfile)
            return;

        $oConnection = BxDolConnection::getObjectInstance($this->_sObjectConnections);
        if (!$oConnection)
            return;

        if ($oProfile->id() == bx_get_logged_profile_id())
            $this->_bOwner = true;

        $aSQLParts = $oConnection->getConnectedInitiatorsAsSQLParts('p', 'id', $oProfile->id(), $this->_bOwner ? false : true);

        $this->addMarkers(array(
            'profile_id' => $oProfile->id(),
            'join_connections' => $aSQLParts['join'],
            'content_module' => $this->_sContentModule,
        ));
    }

    /**
     * 'accept' action handler
     */
    public function performActionAccept()
    {
        $iId = 0;
        $aIds = bx_get('ids');
        if ($aIds && is_array($aIds))
            $iId = (int)array_pop($aIds);

        if (!$iId) {
            $this->_echoResultJson(array('msg' => _t('_sys_txt_error_occured')), true);
            exit;
        }

        $oConn = BxDolConnection::getObjectInstance($this->_sObjectConnections);

        $a = $oConn->actionAdd($iId);
        if (isset($a['err']) && $a['err'])
            $this->_echoResultJson(array('msg' => $a['msg']), true);
        else
            $this->_echoResultJson(array('grid' => $this->getCode(false), 'blink' => $iId), true);
    }

    /**
     * 'add friend' action handler
     */
    public function performActionAddFriend()
    {
        return $this->performActionAccept();
    }

    protected function _delete ($mixedId)
    {
        $oConn = BxDolConnection::getObjectInstance($this->_sObjectConnections);

        if ($oConn->isConnected(bx_get_logged_profile_id(), (int)$mixedId, true))
            $a = $oConn->actionRemove($mixedId);
        else
            $a = $oConn->actionReject($mixedId);

        return isset($a['err']) && $a['err'] ? false : true;
    }

    protected function _getCellName ($mixedValue, $sKey, $aField, $aRow)
    {
        $oProfile = BxDolProfile::getInstance($aRow['id']);
        if (!$oProfile)
            return _t('_sys_txt_error_occured');

        return parent::_getCellDefault ($oProfile->getUnit(), $sKey, $aField, $aRow);
    }

    protected function _getCellInfo ($mixedValue, $sKey, $aField, $aRow)
    {
        $s = '';
        $oConn = BxDolConnection::getObjectInstance($this->_sObjectConnections);

        // for friend requests display mutual friends
        if ($this->_bOwner && !$aRow['mutual']) {
            $a = $oConn->getCommonContent($aRow['id'], bx_get_logged_profile_id(), true);
            $i = count($a);
            if (1 == $i) {
                $iProfileId = array_pop($a);
                $oProfile = BxDolProfile::getInstance($iProfileId);
                $s = _t('_sys_txt_one_mutual_friend', $oProfile->getUrl(), $oProfile->getDisplayName());
            } elseif ($i) {
                $s = _t('_sys_txt_n_mutual_friends', $i);
            }
        }

        // display friends number if no other info is available
        if (!$s) {
            $a = $oConn->getConnectedContent($aRow['id'], true);
            $i = count($a);
            $s = _t('_sys_txt_n_friends', $i);
        }

        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!$this->_bOwner)
            return '';
        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionAccept ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!$this->_bOwner || $aRow['mutual'])
            return '';

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionAddFriend ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if ($this->_bOwner || $aRow['id'] == bx_get_logged_profile_id())
            return '';

        $oConn = BxDolConnection::getObjectInstance($this->_sObjectConnections);
        if ($oConn->isConnected($aRow['id'], bx_get_logged_profile_id()) || $oConn->isConnected(bx_get_logged_profile_id(), $aRow['id']))
            return '';

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
}

/** @} */
