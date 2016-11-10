<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxEventsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_events_data', 'favorites'))
        		$this->oDb->query("ALTER TABLE `bx_events_data` ADD `favorites` int(11) NOT NULL default '0' AFTER `votes`");

			if ($this->isIndexExists('bx_events_data', 'event_name'))
				$this->oDb->query("ALTER TABLE `bx_events_data` DROP INDEX `event_name`");

			if ($this->isIndexExists('bx_events_data', 'search_fields'))
				$this->oDb->query("ALTER TABLE `bx_events_data` DROP INDEX `search_fields`");

			$this->oDb->query("ALTER TABLE `bx_events_data` ADD FULLTEXT KEY `search_fields` (`event_name`, `event_desc`)");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
