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
			if(!$this->oDb->isFieldExists('bx_timeline_meta_locations', 'street'))
					$this->oDb->query("ALTER TABLE `bx_timeline_meta_locations` ADD `street` varchar(255) NOT NULL AFTER `zip`");

			if(!$this->oDb->isFieldExists('bx_timeline_meta_locations', 'street_number'))
					$this->oDb->query("ALTER TABLE `bx_timeline_meta_locations` ADD `street_number` varchar(255) NOT NULL AFTER `street`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
