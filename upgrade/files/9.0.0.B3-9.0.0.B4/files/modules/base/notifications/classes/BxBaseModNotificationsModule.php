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

define('BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT', 'insert');
define('BX_BASE_MOD_NTFS_HANDLER_TYPE_UPDATE', 'update');
define('BX_BASE_MOD_NTFS_HANDLER_TYPE_DELETE', 'delete');

define('BX_BASE_MOD_NTFS_TYPE_OWNER', 'owner');
define('BX_BASE_MOD_NTFS_TYPE_OBJECT_OWNER', 'object_owner');
define('BX_BASE_MOD_NTFS_TYPE_CONNECTIONS', 'connections');
define('BX_BASE_MOD_NTFS_TYPE_PUBLIC', 'public');

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

	public function serviceAddHandlers($sModuleUri)
    {
        $this->_updateModuleData('add_handlers', $sModuleUri);
    }

    public function serviceDeleteHandlers($sModuleUri)
    {
        $this->_updateModuleData('delete_handlers', $sModuleUri);
    }

	public function serviceDeleteModuleEvents($sModuleUri)
    {
        $this->_updateModuleData('delete_module_events', $sModuleUri);
    }

    function serviceGetActionsChecklist()
    {
    	$sLangPrefix = $this->_oConfig->getPrefix('language');
        $aHandlers = $this->_oConfig->getHandlers();

        $aResults = array();
        foreach($aHandlers as $aHandler) {
            if($aHandler['type'] != BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT)
                continue;

			$_sUnit = '_' . $aHandler['alert_unit'];
			$sUnit = _t($_sUnit);
			if(strcmp($_sUnit, $sUnit) === 0)
				$sUnit = _t($sLangPrefix . '_alert_module_' . $aHandler['alert_unit']);

			$sAction = '';
            if(!empty($aHandler['alert_action'])) {
            	$_sAction = '_' . $aHandler['alert_unit'] . '_alert_action_' . $aHandler['alert_action'];
            	$sAction = _t($_sAction);
            	if(strcmp($_sAction, $sAction) === 0)
					$sAction = _t($sLangPrefix . '_alert_action_' . $aHandler['alert_action']);

            	$sAction = ' (' . $sAction . ')';
            }

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

	protected function _updateModuleData($sAction, $sModuleUri)
    {
    	$sMethod = $this->_oConfig->getHandlersMethod();

        $aModule = $this->_oDb->getModuleByUri($sModuleUri);
		if(!BxDolRequest::serviceExists($aModule, $sMethod))
        	return;

		$aData = BxDolService::call($aModule['name'], $sMethod);
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
