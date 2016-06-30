<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxOrgsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_organizations_data', 'allow_view_to'))
        		$this->oDb->query("ALTER TABLE `bx_organizations_data` ADD `allow_view_to` int(11) NOT NULL DEFAULT '3' AFTER `views`");

			$sFile = BX_DIRECTORY_PATH_TMP . $this->_aConfig['home_uri'] . '_processed.txt';
			if(!file_exists($sFile)) {
				$aEntries = $this->oDb->getAll('SELECT * FROM `bx_organizations_data`');
				foreach($aEntries as $aEntry)
					$this->oDb->query('UPDATE `bx_organizations_data` SET `org_desc`=:org_desc WHERE `id`=:id', array(
						'org_desc' => nl2br(htmlspecialchars_adv($aEntry['org_desc'])), 
						'id' => $aEntry['id']
					));

				$oHandler = fopen($sFile, 'w');
				if($oHandler) {
					fwrite($oHandler, 'processed');
					fclose($oHandler);
				}
			}
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
