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
			if ($this->isIndexExists('bx_timeline_events', 'search_fields'))
				$this->oDb->query("ALTER TABLE `bx_timeline_events` DROP INDEX `search_fields`");

			$this->oDb->query("ALTER TABLE `bx_timeline_events` ADD FULLTEXT KEY `search_fields` (`description`)");

			if ($this->isIndexExists('bx_timeline_comments', 'search_fields'))
				$this->oDb->query("ALTER TABLE `bx_timeline_comments` DROP INDEX `search_fields`");

			$this->oDb->query("ALTER TABLE `bx_timeline_comments` ADD FULLTEXT KEY `search_fields` (`cmt_text`)");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
