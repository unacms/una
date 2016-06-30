<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxTimelineUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_timeline_events', 'reports'))
        		$this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `reports` int(11) unsigned NOT NULL default '0' AFTER `comments`");

			if(!$this->oDb->isFieldExists('bx_timeline_events', 'pinned'))
        		$this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `pinned` int(11) NOT NULL default '0' AFTER `hidden`");

			if(!$this->oDb->isFieldExists('bx_timeline_handlers', 'privacy'))
        		$this->oDb->query("ALTER TABLE `bx_timeline_handlers` ADD `privacy` varchar(64) NOT NULL default '' AFTER `content`");
		}

    	return parent::actionExecuteSql($sOperation);
    }

	protected function actionUpdateRelations($sOperation)
    {
        if(!in_array($sOperation, array('install'))) 
        	return BX_DOL_STUDIO_INSTALLER_FAILED;

		if(empty($this->_aConfig['relations']) || !is_array($this->_aConfig['relations']))
            return BX_DOL_STUDIO_INSTALLER_SUCCESS;

		foreach($this->_aConfig['relations'] as $sModule) {
			if(!$this->oDb->isModuleByName($sModule))
				continue;

			$aRelation = $this->oDb->getRelationsBy(array('type' => 'module', 'value' => $sModule));
			if(empty($aRelation) || empty($aRelation['on_enable']) || empty($aRelation['on_disable']) || !BxDolRequest::serviceExists($aRelation['module'], $aRelation['on_enable']) || !BxDolRequest::serviceExists($aRelation['module'], $aRelation['on_disable']))
				continue;

			BxDolService::call($aRelation['module'], $aRelation['on_disable'], array($this->_aConfig['module_uri']));
			BxDolService::call($aRelation['module'], $aRelation['on_enable'], array($this->_aConfig['module_uri']));
		}

        return BX_DOL_STUDIO_INSTALLER_SUCCESS;
    }
}
