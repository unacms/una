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

define('BX_BASE_MOD_GROUPS_PG_PARTICIPANTS', 'p');
define('BX_BASE_MOD_GROUPS_PG_FOLLOWERS', 'f');

class BxBaseModGroupsPrivacyPost extends BxBaseModProfilePrivacyPost
{
    public function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->_aGroupsExclude = [BX_DOL_PG_FRIENDS, BX_DOL_PG_FRIENDS_SELECTED, BX_DOL_PG_RELATIONS, BX_DOL_PG_RELATIONS_SELECTED];
    }

    /**
     * Check whethere viewer is a member of dynamic group.
     *
     * @param  mixed   $mixedGroupId   dynamic group ID.
     * @param  integer $iObjectOwnerId object owner ID.
     * @param  integer $iViewerId      viewer ID.
     * @return boolean result of operation.
     */
    public function isDynamicGroupMember($mixedGroupId, $iObjectOwnerId, $iViewerId, $iObjectId)
    {
        if($mixedGroupId == BX_BASE_MOD_GROUPS_PG_PARTICIPANTS)
            return $this->isParticipantAccess($iObjectOwnerId, $iViewerId, $iObjectId);

        if($mixedGroupId == BX_BASE_MOD_GROUPS_PG_FOLLOWERS)
            return $this->isFollowersAccess($iObjectOwnerId, $iViewerId, $iObjectId);

        return false;
    }

    public function isParticipantAccess ($iObjectOwnerId, $iViewerId, $iObjectId)
    {
        $oConnection = BxDolConnection::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_CONNECTIONS']);
        if(!$oConnection)
            return false;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iObjectId, $this->_sModule);
        return $oConnection->isConnected($iViewerId, $oGroupProfile->id(), true);
    }

    public function isFollowersAccess ($iObjectOwnerId, $iViewerId, $iObjectId)
    {
        $oConnection = BxDolConnection::getObjectInstance('sys_profiles_subscriptions');
        if(!$oConnection)
            return false;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iObjectId, $this->_sModule);
        return $oConnection->isConnected($iViewerId, $oGroupProfile->id());
    }

    static function getGroupChooser ($sObject, $iOwnerId = 0, $aParams = array())
    {
        $oPrivacy = BxDolPrivacy::getObjectInstance($sObject);
        if(empty($oPrivacy))
            return array();

        $CNF = &$oPrivacy->_oModule->_oConfig->CNF;

        if (!$iOwnerId)
            $iOwnerId = bx_get_logged_profile_id();

        $aParams['dynamic_groups'] = array(
            array ('key' => '', 'value' => '----'),
            array ('key' => BX_BASE_MOD_GROUPS_PG_PARTICIPANTS, 'value' => _t(!empty($CNF['T']['txt_group_participants']) ? $CNF['T']['txt_group_participants'] : '_sys_ps_group_title_participants')),
            array ('key' => BX_BASE_MOD_GROUPS_PG_FOLLOWERS, 'value' => _t(!empty($CNF['T']['txt_group_followers']) ? $CNF['T']['txt_group_followers'] : '_sys_ps_group_title_followers'))
        );

        return parent::getGroupChooser($sObject, $iOwnerId, $aParams);
    }

    protected function getObjectInfo($sAction, $iObjectId)
    {
        return BxDolPrivacy::getObjectInfo($sAction, $iObjectId);
    }
}

/** @} */
