<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseNotifications Base classes for Notifications like modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModNotificationsPrivacy extends BxTemplPrivacy
{
	public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
    }

    protected function convertActionToField($sAction)
    {
    	return strtolower('object_privacy_' . $sAction);
    }
}

/** @} */
