<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxNtfsUpdater extends BxDolStudioUpdater
{
	protected $_aModule;

    function __construct($aConfig)
	{
        parent::__construct($aConfig);

        $this->_aModule = $this->oDb->getModuleByUri($this->_aConfig['module_uri']);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_notifications_events', 'object_owner_id'))
        		$this->oDb->query("ALTER TABLE `bx_notifications_events` ADD `object_owner_id` int(11) unsigned NOT NULL default '0' AFTER `object_id`");

			if(!$this->oDb->isFieldExists('bx_notifications_events', 'allow_view_event_to'))
        		$this->oDb->query("ALTER TABLE `bx_notifications_events` ADD `allow_view_event_to` varchar(32) NOT NULL default '3' AFTER `content`");

			if(!$this->oDb->isFieldExists('bx_notifications_handlers', 'privacy'))
        		$this->oDb->query("ALTER TABLE `bx_notifications_handlers` ADD `privacy` varchar(64) NOT NULL default '' AFTER `content`");
		}

    	return parent::actionExecuteSql($sOperation);
    }

	protected function actionClearDbCache($sOperation)
    {
    	if($sOperation == 'install') {
	    	$aModules = $this->oDb->getModulesBy(array('type' => 'all', 'active' => 1));
		    foreach($aModules as $aModule) {
		    	$aConfig = self::getModuleConfig($aModule);
				if(empty($aConfig['relations']) || !is_array($aConfig['relations']) || !in_array($this->_aModule['name'], $aConfig['relations'])) 
					continue;	

				BxDolService::call($this->_aModule['name'], 'delete_handlers', array($aModule['uri']));
				BxDolService::call($this->_aModule['name'], 'add_handlers', array($aModule['uri']));
			}
    	}

    	return parent::actionClearDbCache($sOperation);
    }
}
