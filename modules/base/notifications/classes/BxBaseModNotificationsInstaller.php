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

class BxBaseModNotificationsInstaller extends BxBaseModGeneralInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

	public function enable($aParams)
    {
    	$aResult = parent::enable($aParams);
        if($aResult['result'])
        	$this->_processHandlers('add_handlers');

        return $aResult;
    }

    public function disable($aParams)
    {
    	$this->_processHandlers('delete_handlers');

        return parent::disable($aParams);
    }

    protected function _processHandlers($sAction)
    {
    	$aModules = $this->oDb->getModules();
	    foreach($aModules as $aModule) {
	    	$aConfig = self::getModuleConfig($aModule);
			if(!empty($aConfig['relations']) && is_array($aConfig['relations']) && in_array($this->_aConfig['name'], $aConfig['relations']))
				BxDolService::call($this->_aConfig['name'], $sAction, array($aModule['uri']));
		}
    }
}

/** @} */
