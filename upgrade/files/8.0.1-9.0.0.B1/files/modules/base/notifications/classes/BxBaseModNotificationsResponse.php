<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseNotifications Base classes for Notifications like modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModNotificationsResponse extends BxDolAlertsResponse
{
    protected $_oModule;

    function __construct()
    {
        parent::__construct();
    }

	protected function _getObjectOwnerId($aExtras)
    {
        return is_array($aExtras) && isset($aExtras['object_author_id']) ? (int)$aExtras['object_author_id'] : 0;
    }

    protected function _getObjectPrivacyView($aExtras)
    {
        return is_array($aExtras) && isset($aExtras['privacy_view']) ? (int)$aExtras['privacy_view'] : $this->_oModule->_oConfig->getPrivacyViewDefault('object');
    }

	protected function _getSubObjectId($aExtras)
    {
    	if(is_array($aExtras) && isset($aExtras['comment_id']))
    		return (int)$aExtras['comment_id'];

   		if(is_array($aExtras) && isset($aExtras['vote_id']))
    		return (int)$aExtras['vote_id'];

        if(is_array($aExtras) && isset($aExtras['notification_subobject_id']))
            return (int)$aExtras['notification_subobject_id'];
                
        return 0;
    }
}

/** @} */
