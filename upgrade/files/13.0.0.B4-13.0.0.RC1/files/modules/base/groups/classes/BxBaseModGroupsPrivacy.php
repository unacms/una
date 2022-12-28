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

define('BX_BASE_MOD_GROUPS_PG_CLOSED', 'c');
define('BX_BASE_MOD_GROUPS_PG_SECRET', 's');

class BxBaseModGroupsPrivacy extends BxBaseModProfilePrivacy
{
    public function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->_aGroupsExclude = [BX_DOL_PG_FRIENDS, BX_DOL_PG_FRIENDS_SELECTED, BX_DOL_PG_RELATIONS, BX_DOL_PG_RELATIONS_SELECTED];
        $this->_aPrivacyParticallyVisible = [BX_BASE_MOD_GROUPS_PG_CLOSED];
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
        if ($mixedGroupId == BX_BASE_MOD_GROUPS_PG_CLOSED)
            return $this->isClosedGroupAccess($iObjectOwnerId, $iViewerId, $iObjectId);

        if ($mixedGroupId == BX_BASE_MOD_GROUPS_PG_SECRET)
            return $this->isSecretGroupAccess($iObjectOwnerId, $iViewerId, $iObjectId);

        return false;
    }

    public function isClosedGroupAccess ($iObjectOwnerId, $iViewerId, $iObjectId)
    {
        if (!($oConnection = BxDolConnection::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_CONNECTIONS'])))
            return false;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iObjectId, $this->MODULE);
        return $oConnection->isConnected($iViewerId, $oGroupProfile->id(), true);
    }

    public function isSecretGroupAccess ($iObjectOwnerId, $iViewerId, $iObjectId)
    {
        return $this->isClosedGroupAccess($iObjectOwnerId, $iViewerId, $iObjectId);
    }

    static function getGroupChooser ($sObject, $iOwnerId = 0, $aParams = array())
    {
        if (!$iOwnerId)
            $iOwnerId = bx_get_logged_profile_id();

        $aParams['dynamic_groups'] = array(
            array ('key' => '', 'value' => '----', 'attrs' => array('disabled' => 'disabled')),
            array ('key' => BX_BASE_MOD_GROUPS_PG_CLOSED, 'value' => _t('_sys_ps_group_title_closed')),
            array ('key' => BX_BASE_MOD_GROUPS_PG_SECRET, 'value' => _t('_sys_ps_group_title_secret')),
        );

        return parent::getGroupChooser($sObject, $iOwnerId, $aParams);
    }

    protected function getObjectInfo($sAction, $iObjectId)
    {
        return BxDolPrivacy::getObjectInfo($sAction, $iObjectId);
    }
}

/** @} */
