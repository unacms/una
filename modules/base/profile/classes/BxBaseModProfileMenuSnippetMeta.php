<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModProfileMenuSnippetMeta extends BxBaseModGeneralMenuSnippetMeta
{
    protected $_bContentPublic;
    protected $_oContentProfile;

    protected $_aConnectionToFunctionCheck;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_bShowZeros = true;
        $this->_bContentPublic = false;

        $this->_aConnectionToFunctionCheck = $this->_oModule->_oConfig->getConnectionToFunctionCheck();
    }

    public function setContentId($iContentId)
    {
        parent::setContentId($iContentId);

        if(!empty($this->_iContentId))
            $this->_oContentProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_sModule);
    }

    protected function getMenuItemConnectionJsCode($sConnection, $sAction, $iContentProfile, $aItem)
    {
        return 'bx_conn_action(this, \'' . $sConnection . '\', \'' . $sAction . '\', \'' . $iContentProfile . '\', false, function(oData, eLink) {$(eLink).parents(\'.bx-menu-item:first\').remove();})';
    }

    public function setContentPublic($bContentPublic)
    {
        $this->_bContentPublic = $bContentPublic;
    }

    protected function _getMenuItemBefriend($aItem)
    {
        return $this->_getMenuItemConnection('sys_profiles_friends', 'add', $aItem);
    }

    protected function _getMenuItemUnfriend($aItem)
    {
        return $this->_getMenuItemConnection('sys_profiles_friends', 'remove', $aItem);
    }

    protected function _getMenuItemSubscribe($aItem)
    {
        return $this->_getMenuItemConnection('sys_profiles_subscriptions', 'add', $aItem);
    }

    protected function _getMenuItemUnsubscribe($aItem)
    {
        return $this->_getMenuItemConnection('sys_profiles_subscriptions', 'remove', $aItem);
    }

    protected function _getMenuItemFriends($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!$this->_bContentPublic || !$this->_oContentProfile)
            return false;

        $oConnection = BxDolConnection::getObjectInstance('sys_profiles_friends');
        if(!$oConnection)
            return false;

        $iFriends = $oConnection->getConnectedInitiatorsCount($this->_oContentProfile->id(), true);
        if(!$iFriends && !$this->_bShowZeros)
            return false;

        $sTitle = _t('_sys_menu_item_title_sm_friends', $iFriends);

        if($this->_bIsApi)
            return $this->_getMenuItemAPI($aItem, 'text', [
                'title' => $sTitle
            ]);

        return $this->getUnitMetaItemText($sTitle);
    }

    protected function _getMenuItemFriendsMutual($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!$this->_bContentPublic || !$this->_oContentProfile)
            return false;

        $oConnection = BxDolConnection::getObjectInstance('sys_profiles_friends');
        if(!$oConnection)
            return false;

        $iFriends = $oConnection->getCommonContentCount($this->_oContentProfile->id(), bx_get_logged_profile_id(), true);
        if(!$iFriends && !$this->_bShowZeros)
            return false;

        $sTitle = _t('_sys_menu_item_title_sm_friends_mutual', $iFriends);

        if($this->_bIsApi)
            return $this->_getMenuItemAPI($aItem, 'text', [
                'title' => $sTitle
            ]);

        return $this->getUnitMetaItemText($sTitle);
    }

    protected function _getMenuItemSubscribers($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!$this->_bContentPublic || !$this->_oContentProfile)
            return false;

        $oConnection = BxDolConnection::getObjectInstance('sys_profiles_subscriptions');
        if(!$oConnection)
            return false;

        $iSubscribers = $oConnection->getConnectedInitiatorsCount($this->_oContentProfile->id());
        if(!$iSubscribers && !$this->_bShowZeros)
            return false;

        $sIcon = BxTemplFunctions::getInstanceWithTemplate($this->_oTemplate)->getIconAsHtml(!empty($aItem['icon']) ? $aItem['icon'] : '');

        if($this->_bIsApi)
            return false;

        return $this->getUnitMetaItemCustom($oConnection->getCounter($this->_oContentProfile->id(), false, [
            'caption' => '_sys_menu_item_title_sm_subscribers', 
            'custom_icon' => $sIcon
        ], BX_CONNECTIONS_CONTENT_TYPE_INITIATORS));
    }

    protected function _getMenuItemConnection($sConnection, $sAction, &$aItem)
    {
        if(!isLogged() || $this->_oModule->{$this->_aConnectionToFunctionCheck[$sConnection][$sAction]}($this->_aContentInfo) !== CHECK_ACTION_RESULT_ALLOWED)
            return false;

        $iContentProfile = $this->_oContentProfile->id();
        $sTitle = $this->_oModule->getMenuItemTitleByConnection($sConnection, $sAction, $iContentProfile);
        if(empty($sTitle))
            return false;

        if($this->_bIsApi)
            return $this->_getMenuItemAPI($aItem, ['display' => 'element'], [
                'title' => $sTitle,
                'data' => [
                    'type' => 'connections',
                    'o' => $sConnection,
                    'a' => $sAction,
                    'iid' => bx_get_logged_profile_id(),
                    'cid' => $iContentProfile,
                    'title' => $sTitle,
                    'primary' => !empty($aItem['primary']),
                ]
            ]);

        return [
            $this->getUnitMetaItemButton($sTitle, array(
                'class' => !empty($aItem['primary']) ? 'bx-btn-primary' : '',
                'onclick' => $this->getMenuItemConnectionJsCode($sConnection, $sAction, $iContentProfile, $aItem)
            )),
            'bx-menu-item-button'
        ];
    }

    protected function _getMenuItemMembership($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!$this->_bContentPublic || !$this->_oContentProfile)
            return false;

        $aMembership = BxDolAcl::getInstance()->getMemberMembershipInfo($this->_oContentProfile->id());
        if(empty($aMembership) || !is_array($aMembership))
            return false;

        $sTitle = _t($aMembership['name']);

        if($this->_bIsApi)
            return $this->_getMenuItemAPI($aItem, 'text', [
                'title' => $sTitle
            ]);

        return $this->getUnitMetaItemText($sTitle);
    }
}

/** @} */
