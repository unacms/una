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

class BxBaseModNotificationsResponse extends BxDolAlertsResponse
{
    protected $_sModule;
    protected $_oModule;

    public function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    /**
     * Note. Check priority is IMPORTANT!
     * Don't swap the checks without a REAL need.
     */
    protected function _getObjectOwnerId($aExtras)
    {
        if(!is_array($aExtras))
            return 0;

        if(isset($aExtras['parent_author_id']))
    		return (int)$aExtras['parent_author_id'];

        if(isset($aExtras['object_author_id']))
    		return (int)$aExtras['object_author_id'];

        return 0;
    }

    protected function _getObjectPrivacyView($aExtras)
    {
        return $this->_oModule->getObjectPrivacyView($aExtras);
    }

    protected function _getObjectCf($aExtras)
    {
        return $this->_oModule->getObjectCf($aExtras);
    }

    /**
     * Note. Check priority is NOT IMPORTANT!
     */
    protected function _getSubObjectId($aExtras)
    {
        if(!is_array($aExtras))
            return 0;

    	if(isset($aExtras['comment_id']))
            return (int)$aExtras['comment_id'];

        if(isset($aExtras['vote_id']))
            return (int)$aExtras['vote_id'];

        if(isset($aExtras['score_id']))
            return (int)$aExtras['score_id'];

        if(isset($aExtras['repost_id']))
            return (int)$aExtras['repost_id'];

        if(isset($aExtras['timeline_post_id']))
            return (int)$aExtras['timeline_post_id'];

        if(isset($aExtras['performer_id']))
            return (int)$aExtras['performer_id'];
        
        if(isset($aExtras['subobject_id']))
            return (int)$aExtras['subobject_id'];

        if(isset($aExtras['subobjects_ids']))
            return is_array($aExtras['subobjects_ids']) ? $aExtras['subobjects_ids'] : array($aExtras['subobjects_ids']);

        return 0;
    }

    protected function _getEventUpdate(&$oAlert, &$aHandler)
    {
        $aHandlers = $this->_oModule->_oDb->getHandlers([
            'type' => 'by_group_key_type', 
            'group' => $aHandler['group']
        ]);

        return $this->_oModule->_oDb->getEvents([
            'browse' => 'descriptor', 
            'type' => $oAlert->sUnit, 
            'action' => $aHandlers[BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT]['alert_action'],
            'object_id' => $oAlert->iObject
        ]);
    }
}

/** @} */
