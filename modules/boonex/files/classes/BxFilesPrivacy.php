<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFilesPrivacy extends BxTemplPrivacy
{
    protected $_oModule;

    public function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_files');
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
        $oGroupProfile = BxDolProfile::getInstance($iObjectOwnerId);
        if (!BxDolService::call($oGroupProfile->getModule(), 'is_group_profile'))
            return false;

        if ('a' == $mixedGroupId)
            return true;

        if ('m' == $mixedGroupId)
            return BxDolService::call($oGroupProfile->getModule(), 'is_fan', array($oGroupProfile->id(), $iViewerId));

        return false;
    }

    static function getGroupChooser ($sObject, $iOwnerId = 0, $aParams = array())
    {
        if (!$iOwnerId)
    		$iOwnerId = bx_get_logged_profile_id();

        $aParams['dynamic_groups'] = array(
			array ('key' => 'a', 'value' => _t('_bx_files_privacy_public')),
			array ('key' => 'm', 'value' => _t('_bx_files_privacy_participants')),
		);

        return parent::getGroupChooser($sObject, $iOwnerId, $aParams);
    }

    protected function getGroups() 
    {
        return array();
    }
}

/** @} */
