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

bx_import ('BxDolModule');

define('BX_BASE_MOD_NTFS_HANDLER_TYPE_INSERT', 'insert');
define('BX_BASE_MOD_NTFS_HANDLER_TYPE_UPDATE', 'update');
define('BX_BASE_MOD_NTFS_HANDLER_TYPE_DELETE', 'delete');

define('BX_BASE_MOD_NTFS_TYPE_OWNER', 'owner');
define('BX_BASE_MOD_NTFS_TYPE_CONNECTIONS', 'connections');
define('BX_BASE_MOD_NTFS_TYPE_PUBLIC', 'public');

/**
 * Base module class.
 */
class BxBaseModNotificationsModule extends BxDolModule
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

			$aModule = array();
			if(!empty($aHandler['module_name']))
            	$aModule = $this->_oDb->getModuleByName($aHandler['module_name']);

            if(empty($aModule))
                $aModule['title'] = _t($sLangPrefix . '_alert_module_' . $aHandler['alert_unit']);

			$sAction = !empty($aHandler['alert_action']) ? ' (' . _t($sLangPrefix . '_alert_action_' . $aHandler['alert_action']) . ')' : '';
            $aResults[$aHandler['id']] = $aModule['title'] . $sAction;
        }

        asort($aResults);
        return $aResults;
    }

    public function isAllowedView($bPerform = false)
    {
		return true;
    }

    public function getOwnerId()
    {
    	return $this->_iOwnerId;
    }

	public function getUserId()
    {
        return isLogged() ? bx_get_logged_profile_id() : 0;
    }

    public function getUserIp()
    {
        return getVisitorIP();
    }

    public function getUserInfo($iUserId = 0)
    {
        bx_import('BxDolProfile');
        $oProfile = BxDolProfile::getInstance($iUserId);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }

        return array(
            $oProfile->getDisplayName(),
            $oProfile->getUrl(),
            $oProfile->getThumb(),
            $oProfile->getUnit()
        );
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

    protected function _echoResultJson($a, $isAutoWrapForFormFileSubmit = false)
    {
        header('Content-type: text/html; charset=utf-8');

        $s = json_encode($a);
        if ($isAutoWrapForFormFileSubmit && !empty($_FILES))
            $s = '<textarea>' . $s . '</textarea>';
        echo $s;
    }
}

/** @} */
