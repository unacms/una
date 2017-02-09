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
    		if(!$this->oDb->isFieldExists('bx_events_data', 'featured'))
        		$this->oDb->query("ALTER TABLE `bx_events_data` ADD `featured` int(11) NOT NULL default '0' AFTER `reports`");

			if(!$this->oDb->isFieldExists('bx_events_data', 'reminder'))
        		$this->oDb->query("ALTER TABLE `bx_events_data` ADD `reminder` int(11) NOT NULL DEFAULT '1' AFTER `join_confirmation`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
