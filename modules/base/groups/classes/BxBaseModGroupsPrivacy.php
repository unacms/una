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

class BxBaseModGroupsPrivacy extends BxBaseModProfilePrivacy
{
    public function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);
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
        if ('c' == $mixedGroupId)
            return $this->isClosedGroupAccess($iObjectOwnerId, $iViewerId, $iObjectId);

        if ('s' == $mixedGroupId)
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
			array ('key' => '', 'value' => '----'),
			array ('key' => 'c', 'value' => _t('_bx_groups_privacy_group_closed')),
			array ('key' => 's', 'value' => _t('_bx_groups_privacy_group_secret')),
		);

        return parent::getGroupChooser($sObject, $iOwnerId, $aParams);
    }
}

/** @} */
