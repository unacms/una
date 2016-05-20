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
    protected $_oConnection;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
        $this->_sDefaultSortingOrder = 'DESC';

        $this->_oConnection = BxDolConnection::getObjectInstance($this->_sObjectConnections);
        if (!$this->_oConnection)
            return;

        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId)
            return;

        $oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$oProfile)
            return;

        if ($oProfile->id() == bx_get_logged_profile_id())
            $this->_bOwner = true;

        $aSQLParts = $this->_oConnection->getConnectedInitiatorsAsSQLParts('p', 'id', $oProfile->id(), $this->_bOwner ? false : true);

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
        list ($iId, $iViewedId) = $this->_prepareIds();

        if (!$iId) {
            echoJson(array('msg' => _t('_sys_txt_error_occured')));
            exit;
        }

        $a = $this->_oConnection->actionAdd($iId, $iViewedId);
        if (isset($a['err']) && $a['err'])
            echoJson(array('msg' => $a['msg']));
        else
            echoJson(array('grid' => $this->getCode(false), 'blink' => $iId));
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
        list ($iId, $iViewedId) = $this->_prepareIds();

        if ($this->_oConnection->isConnected($iViewedId, $iId, true))
            $a = $this->_oConnection->actionRemove($iId, $iViewedId);
        else
            $a = $this->_oConnection->actionReject($iId, $iViewedId);

        if (isset($a['err']) && $a['err'])
            return false;

        return true;
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

        // for friend requests display mutual friends
        if ($this->_bOwner && !$aRow['mutual']) {
            $a = $this->_oConnection->getCommonContent($aRow['id'], bx_get_logged_profile_id(), true);
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
            $a = $this->_oConnection->getConnectedContent($aRow['id'], true);
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

        if ($this->_oConnection->isConnected($aRow['id'], bx_get_logged_profile_id()) || $this->_oConnection->isConnected(bx_get_logged_profile_id(), $aRow['id']))
            return '';

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _prepareIds ()
    {
        $iViewedId = false;
        $iId = 0;
        $aIds = bx_get('ids');
        if ($aIds && is_array($aIds))
            $mixedId = array_pop($aIds);

        if (false === strpos($mixedId, ':')) {
            $iId = (int)$mixedId;
        }
        else {
            list ($iId, $iViewedId) = explode (':', $mixedId);
            $iId = (int)$iId;
            $iViewedId = (int)$iViewedId;
        }
        return array($iId, $iViewedId);
    }
}

/** @} */
