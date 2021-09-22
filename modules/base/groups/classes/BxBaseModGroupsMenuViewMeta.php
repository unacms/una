<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry meta menu
 */
class BxBaseModGroupsMenuViewMeta extends BxBaseModProfileMenuViewMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemMembers($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!$this->_bContentPublic || !$this->_oContentProfile || empty($CNF['OBJECT_CONNECTIONS']))
            return false;

        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);
        if(!$oConnection)
            return false;

        $iMembers = $oConnection->getConnectedInitiatorsCount($this->_oContentProfile->id(), true);
        if(!$iMembers)
            return false;

        return $this->getUnitMetaItemText(_t(!empty($aItem['title']) ? $aItem['title'] : '_sys_menu_item_title_sm_members', $iMembers));
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
        if(!$iSubscribers)
            return false;

        return $this->getUnitMetaItemText(_t(!empty($aItem['title']) ? $aItem['title'] : '_sys_menu_item_title_sm_subscribers', $iSubscribers));
    }
}

/** @} */
