<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
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
			if(!$this->oDb->isFieldExists('bx_timeline_events', 'views'))
        		$this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `views` int(11) unsigned NOT NULL default '0' AFTER `description`");

			if($this->oDb->isFieldExists('bx_timeline_events', 'shares'))
        		$this->oDb->query("ALTER TABLE `bx_timeline_events` CHANGE `shares` `reposts` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0'");

			if($this->isTableExists('bx_timeline_shares_track') && !$this->isTableExists('bx_timeline_reposts_track'))
				$this->oDb->query("RENAME TABLE `bx_timeline_shares_track` TO `bx_timeline_reposts_track`");

			$bRepostsTrack = $this->isTableExists('bx_timeline_reposts_track');
			if($bRepostsTrack && $this->oDb->isFieldExists('bx_timeline_reposts_track', 'shared_id'))
        		$this->oDb->query("ALTER TABLE `bx_timeline_reposts_track` CHANGE `shared_id` `reposted_id` INT( 11 ) NOT NULL DEFAULT '0'");

			if($bRepostsTrack && $this->oDb->isIndexExists('bx_timeline_reposts_track', 'share'))
				$this->oDb->query("ALTER TABLE `bx_timeline_reposts_track` DROP INDEX `share`");

			if($bRepostsTrack && !$this->oDb->isIndexExists('bx_timeline_reposts_track', 'repost'))
				$this->oDb->query("ALTER TABLE `bx_timeline_reposts_track` ADD INDEX `repost` (`reposted_id` , `author_nip`)");
		}

    	return parent::actionExecuteSql($sOperation);
    }

	public function update($aParams)
    {
		$aResult = parent::update($aParams);
		if(!$aResult['result'])
			return $aResult;

		$aModule = $this->oDb->getModuleByUri($this->_aConfig['module_uri']);
		if(!empty($aModule) && is_array($aModule) && (int)$aModule['enabled'] == 1)
			$this->updateParamAET(true);

		return $aResult;
	}

	protected function isTableExists($sTable)
    {
        $aTableNames = $this->oDb->listTables();
        foreach($aTableNames as $iKey => $sTableName)
            $aTableNames[$iKey] = strtoupper($sTableName);

        return in_array(strtoupper($sTable), $aTableNames);
    }

	protected function updateParamAET($bAdd)
    {
        $sAetDivider = ',';
        $sAetName = 'bx_timeline_send';

        $sAetParam = 'sys_email_attachable_email_templates';
        $sAetParamValue = getParam($sAetParam);

        if($bAdd)
            $sAetParamValue = trim($sAetParamValue . $sAetDivider . $sAetName, $sAetDivider);
        else {
            $aAet = explode($sAetDivider, $sAetParamValue);

            $mixedKey = array_search($sAetName, $aAet);
            if($mixedKey !== false) {
                unset($aAet[$mixedKey]);
                $sAetParamValue = implode($sAetDivider, $aAet);
            }
        }

        return setParam($sAetParam, $sAetParamValue);
    }
}
