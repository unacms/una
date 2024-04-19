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

/**
 * View entry all actions menu
 */
class BxBaseModProfileMenuViewActionsAll extends BxBaseModGeneralMenuViewActions
{
    protected $_oProfile;
    protected $_aProfileInfo;

    protected $_aConnectionToFunctionCheck;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_aConnectionToFunctionCheck = $this->_oModule->_oConfig->getConnectionToFunctionCheck();

        if(empty($this->_iContentId) && bx_get('profile_id') !== false)
            $this->setContentId(BxDolProfile::getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT))->getContentId());
    }
    
    public function setContentId($iContentId)
    {
        parent::setContentId($iContentId);

        $this->_oProfile = BxDolProfile::getInstanceByContentAndType($this->_iContentId, $this->_sModule);
        if(!$this->_oProfile) 
            return;

        $this->_aProfileInfo = $this->_oProfile->getInfo();     

        $this->addMarkers($this->_aProfileInfo);
        $this->addMarkers(array(
            'profile_id' => $this->_oProfile->id()
        ));
    }

    protected function getMenuItemTitleByConnection($sConnection, $sAction, $iContentProfile, $iInitiatorProfile)
    {
        return '';
    }

    protected function _getMenuItemConnectionApi($sConnection, $sAction, &$aItem)
    {
        if(!isLogged() || (isset($this->_aConnectionToFunctionCheck[$sConnection]) && $this->_oModule->{$this->_aConnectionToFunctionCheck[$sConnection][$sAction]}($this->_aContentInfo) !== CHECK_ACTION_RESULT_ALLOWED))
            return false;

        $iInitiatorProfile = bx_get_logged_profile_id();
        $iContentProfile = $this->_oProfile->id();
        $sTitle = $this->_oModule->getMenuItemTitleByConnection($sConnection, $sAction, $iContentProfile, $iInitiatorProfile);
        
        if(empty($sTitle))
            $sTitle = $this->getMenuItemTitleByConnection($sConnection, $sAction, $iContentProfile, $iInitiatorProfile);
        if(empty($sTitle))
            return false;
        
        return [
            'id' => $aItem['id'],
            'name' => $aItem['name'],
            'title' => $sTitle,
            'display_type' => 'element',
            'data' => [
                'type' => 'connections',
                'o' => $sConnection,
                'a' => $sAction,
                'iid' => $iInitiatorProfile,
                'cid' => $iContentProfile,
                'title' => $sTitle,
                'primary' => !empty($aItem['primary']),
            ]
        ];
    }

    protected function _getMenuItemProfileFriendAdd($aItem)
    {
        if($this->_bIsApi)
            return $this->_getMenuItemConnectionApi('sys_profiles_friends', 'add', $aItem);

        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemProfileFriendRemove($aItem)
    {
        if($this->_bIsApi)
            return $this->_getMenuItemConnectionApi('sys_profiles_friends', 'remove', $aItem);

        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemProfileRelationAdd($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemProfileRelationRemove($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemProfileSubscribeAdd($aItem)
    {
        if($this->_bIsApi)
            return $this->_getMenuItemConnectionApi('sys_profiles_subscriptions', 'add', $aItem);
        
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemProfileSubscribeRemove($aItem)
    {
        if($this->_bIsApi)
            return $this->_getMenuItemConnectionApi('sys_profiles_subscriptions', 'remove', $aItem);

        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemProfileSetAclLevel($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemView($aItem, $aParams = array())
    {
        return parent::_getMenuItemView($aItem, array_merge($aParams, [
            'object_options' => ['show_counter' => false]
        ]));
    }

    protected function _getMenuItemComment($aItem, $aParams = array())
    {
        return parent::_getMenuItemComment($aItem, array_merge($aParams, [
            'object_options' => ['show_counter' => false]
        ]));
    }

    protected function _getMenuItemVote($aItem, $aParams = array())
    {
        return parent::_getMenuItemVote($aItem, array_merge($aParams, [
            'object_options' => ['show_counter' => false]
        ]));
    }

    protected function _getMenuItemReaction($aItem, $aParams = array())
    {
        return parent::_getMenuItemReaction($aItem, array_merge($aParams, [
            'object_options' => ['show_counter' => false]
        ]));
    }

    protected function _getMenuItemScore($aItem, $aParams = array())
    {
        return parent::_getMenuItemScore($aItem, array_merge($aParams, [
            'object_options' => ['show_counter' => false]
        ]));
    }

    protected function _getMenuItemMessenger($aItem, $aParams = array())
    {
        $aItem = BxTemplMenu::_getMenuItem($aItem);
        if($aItem === false)
            return false;

        if(!$this->_bIsApi)
            return $this->_getMenuItemDefault($aItem);

        $sModule = 'bx_messenger';
        $sMethod = 'find_convo';
        if(!bx_is_srv($sModule, $sMethod))
            return false;

        return [
            'id' => $aItem['id'],
            'name' => $aItem['name'],
            'title' => $aItem['title'],
            'display_type' => 'callback',
            'data' => [
                'request_url' => $sModule . '/get_convo_url/Services&params[]=' . json_encode(['recipient' => $this->_oProfile->id()]),
                'on_callback' => 'redirect'
            ]
        ];
    }
}

/** @} */
