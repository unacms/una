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
}
