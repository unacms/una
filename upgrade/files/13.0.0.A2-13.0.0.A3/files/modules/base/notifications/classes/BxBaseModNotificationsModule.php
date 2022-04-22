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

/**
 * SLTMODE - Silent mode:
 * It is needed for alert sending module to tell that the alert should be ignored 
 * with Notifications module completely or partially. Available values: 
 * 1. disabled (value = 0) - all notifications are available;
 * 2. absolute (value = 1) - alert isn't registered which means that there is no notifications at all;
 * 
 * @see BxBaseModNotificationsResponse::response - 'silent_mode' parameter in Alerts Extras array.
 */
define('BX_BASE_MOD_NTFS_SLTMODE_DISABLED', 0);
define('BX_BASE_MOD_NTFS_SLTMODE_ABSOLUTE', 1);

define('BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT', 'insert');
define('BX_BASE_MOD_NTFS_HANDLER_TYPE_UPDATE', 'update');
define('BX_BASE_MOD_NTFS_HANDLER_TYPE_DELETE', 'delete');

define('BX_BASE_MOD_NTFS_TYPE_OWNER', 'owner');
define('BX_BASE_MOD_NTFS_TYPE_OBJECT_OWNER', 'object_owner');
define('BX_BASE_MOD_NTFS_TYPE_CONNECTIONS', 'connections');
define('BX_BASE_MOD_NTFS_TYPE_PUBLIC', 'public');

/**
 * DTYPE - Delivery Type:
 * 1. by Onsite notification,
 * 2. by Email message,
 * 3. by Push notification.
 */
define('BX_BASE_MOD_NTFS_DTYPE_SITE', 'site');
define('BX_BASE_MOD_NTFS_DTYPE_EMAIL', 'email');
define('BX_BASE_MOD_NTFS_DTYPE_PUSH', 'push');

/**
 * Base module class.
 */
class BxBaseModNotificationsModule extends BxBaseModGeneralModule
{
	public $_iOwnerId;

    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);

        $this->_iOwnerId = 0;
    }

    public function serviceGetSafeServices()
    {
        return array();
    }

    /**
     * @page service Service Calls
     * @section bx_base_notifications Base Notifications
     * @subsection bx_base_notifications-other Other
     * @subsubsection bx_base_notifications-add_handlers add_handlers
     * 
     * @code bx_srv('bx_notifications', 'add_handlers', [...]); @endcode
     * 
     * Register handlers for specified module.
     *
     * @param $sModuleUri string with module URI.
     * 
     * @see BxBaseModNotificationsModule::serviceAddHandlers
     */
    /** 
     * @ref bx_base_notifications-add_handlers "add_handlers"
     */
	public function serviceAddHandlers($sModuleUri)
    {
        $this->_updateModuleData('add_handlers', $sModuleUri);
    }

    /**
     * @page service Service Calls
     * @section bx_base_notifications Base Notifications
     * @subsection bx_base_notifications-other Other
     * @subsubsection bx_base_notifications-delete_handlers delete_handlers
     * 
     * @code bx_srv('bx_notifications', 'delete_handlers', [...]); @endcode
     * 
     * Unregister handlers for specified module.
     *
     * @param $sModuleUri string with module URI.
     * 
     * @see BxBaseModNotificationsModule::serviceDeleteHandlers
     */
    /** 
     * @ref bx_base_notifications-delete_handlers "delete_handlers"
     */
    public function serviceDeleteHandlers($sModuleUri)
    {
        $this->_updateModuleData('delete_handlers', $sModuleUri);
    }

    /**
     * @page service Service Calls
     * @section bx_base_notifications Base Notifications
     * @subsection bx_base_notifications-other Other
     * @subsubsection bx_base_notifications-delete_module_events delete_module_events
     * 
     * @code bx_srv('bx_notifications', 'delete_module_events', [...]); @endcode
     * 
     * Delete all events for specified module.
     *
     * @param $sModuleUri string with module URI.
     * 
     * @see BxBaseModNotificationsModule::serviceDeleteModuleEvents
     */
    /** 
     * @ref bx_base_notifications-delete_module_events "delete_module_events"
     */
	public function serviceDeleteModuleEvents($sModuleUri)
    {
        $this->_updateModuleData('delete_module_events', $sModuleUri);
    }

    /**
     * @page service Service Calls
     * @section bx_base_notifications Base Notifications
     * @subsection bx_base_notifications-other Other
     * @subsubsection bx_base_notifications-get_actions_checklist get_actions_checklist
     * 
     * @code bx_srv('bx_notifications', 'get_actions_checklist', [...]); @endcode
     * 
     * Get available actions for module settings in Studio.
     *
     * @return an array with available actions represented as key => value pairs.
     * 
     * @see BxBaseModNotificationsModule::serviceGetActionsChecklist
     */
    /** 
     * @ref bx_base_notifications-get_actions_checklist "get_actions_checklist"
     */
    function serviceGetActionsChecklist()
    {
    	$sLangPrefix = $this->_oConfig->getPrefix('language');
        $aHandlers = $this->_oConfig->getHandlers();

        $aResults = array();
        foreach($aHandlers as $aHandler) {
            if($aHandler['type'] != BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT)
                continue;

            $sUnit = _t($this->_oConfig->getHandlersUnitTitle($aHandler['alert_unit']));

            $sAction = '';
            if(!empty($aHandler['alert_action']))
            	$sAction = ' (' . _t($this->_oConfig->getHandlersActionTitle($aHandler['alert_unit'], $aHandler['alert_action'])) . ')';

            $aResults[$aHandler['id']] = $sUnit . $sAction;
        }

        asort($aResults);
        return $aResults;
    }

    public function isAllowedView($aEvent, $bPerform = false)
    {
        return true;
    }

    public function getOwnerId()
    {
    	return $this->_iOwnerId;
    }

    public function getSilentMode($aExtras)
    {
        if(isset($aExtras['silent_mode']))
            return (int)$aExtras['silent_mode'];

        return BX_BASE_MOD_NTFS_SLTMODE_DISABLED;
    }

    /*
     * Retrieve the Privacy from an array (Alert's Extras or Content field from DB) 
     */
    public function getObjectPrivacyView($aData)
    {
        return is_array($aData) && isset($aData['privacy_view']) ? $aData['privacy_view'] : $this->_oConfig->getPrivacyViewDefault('object');
    }

    /*
     * Retrieve the Content Filter from an array (Alert's Extras or Content field from DB) 
     */
    public function getObjectCf($aData)
    {
        return is_array($aData) && isset($aData['cf']) ? $aData['cf'] : $this->_oConfig->getCfDefault('object');
    }

    protected function _updateModuleData($sAction, $sModuleUri)
    {
        $sMethod = $this->_oConfig->getHandlersMethod();

        $aModule = $this->_oDb->getModuleByUri($sModuleUri);
        if(!BxDolRequest::serviceExists($aModule, $sMethod))
            return;

        $aData = bx_srv_ii($aModule['name'], $sMethod);
        if(empty($aData) || !is_array($aData))
            return;

        switch($sAction) {
            case 'add_handlers':
                $this->_oDb->insertData($aData);
                BxDolAlerts::cacheInvalidate();

                $this->_oDb->activateModuleEvents($aData, true);
                break;

            case 'delete_handlers':
                $this->_oDb->deleteData($aData);
                BxDolAlerts::cacheInvalidate();

                $this->_oDb->activateModuleEvents($aData, false);
                break;

            case 'delete_module_events':
                $this->_oDb->deleteModuleEvents($aData);
                break;
        }
    }
}

/** @} */
