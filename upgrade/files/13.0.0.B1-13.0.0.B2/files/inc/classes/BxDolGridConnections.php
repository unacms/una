<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxDolGridConnections extends BxTemplGrid
{
    protected $_bInit = false;
    protected $_bOwner = false;
    protected $_sObjectConnections = 'sys_profiles_friends';
    protected $_oProfile;
    protected $_oConnection;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
        $this->_sDefaultSortingOrder = 'DESC';

        $this->_aQueryAppendExclude[] = 'join_connections';

        if(($iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT)) !== false)
            $this->setProfile($iProfileId);
    }

    public function init()
    {
        if(!$this->_oProfile)
            return false;

        if($this->_oProfile->id() == bx_get_logged_profile_id())
            $this->_bOwner = true;

        $this->_oConnection = BxDolConnection::getObjectInstance($this->_sObjectConnections);
        if(!$this->_oConnection)
            return false;

        $aSQLParts = $this->_oConnection->getConnectedInitiatorsAsSQLParts('p', 'id', $this->_oProfile->id(), $this->_bOwner ? false : true);

        $this->addMarkers(array(
            'profile_id' => $this->_oProfile->id(),
            'join_connections' => $aSQLParts['join']
        ));

        return true;
    }

    public function setProfile($iProfileId)
    {
        $this->_oProfile = BxDolProfile::getInstance((int)$iProfileId);

        $this->_bInit = $this->init();
    }

    public function getCode ($isDisplayHeader = true)
    {
        if(!$this->_bInit)
            return '';

        return parent::getCode($isDisplayHeader);        
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

        $sBadges = bx_srv($oProfile->getModule(), 'get_badges', array($oProfile->getContentId()));
        return parent::_getCellDefault($oProfile->getUnit(0, array('template' => array('vars' => array('addon' => $sBadges)))), $sKey, $aField, $aRow);
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
        if (!isLogged() || $this->_bOwner || $aRow['id'] == bx_get_logged_profile_id())
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
