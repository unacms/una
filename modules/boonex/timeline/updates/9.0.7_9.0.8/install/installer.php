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
    		if(!$this->oDb->isFieldExists('bx_timeline_events', 'score'))
        		$this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `score` int(11) NOT NULL default '0' AFTER `votes`");
			if(!$this->oDb->isFieldExists('bx_timeline_events', 'sc_up'))
        		$this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `sc_up` int(11) NOT NULL default '0' AFTER `score`");
			if(!$this->oDb->isFieldExists('bx_timeline_events', 'sc_down'))
        		$this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `sc_down` int(11) NOT NULL default '0' AFTER `sc_up`");
			if(!$this->oDb->isFieldExists('bx_timeline_events', 'promoted'))
        		$this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `promoted` int(11) NOT NULL default '0' AFTER `pinned`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
