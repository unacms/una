<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxGroupsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_groups_data', 'favorites'))
        		$this->oDb->query("ALTER TABLE `bx_groups_data` ADD `favorites` int(11) NOT NULL default '0' AFTER `votes`");

			if ($this->isIndexExists('bx_groups_data', 'group_name'))
				$this->oDb->query("ALTER TABLE `bx_groups_data` DROP INDEX `group_name`");

			if ($this->isIndexExists('bx_groups_data', 'search_fields'))
				$this->oDb->query("ALTER TABLE `bx_groups_data` DROP INDEX `search_fields`");

			$this->oDb->query("ALTER TABLE `bx_groups_data` ADD FULLTEXT KEY `search_fields` (`group_name`, `group_desc`)");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
