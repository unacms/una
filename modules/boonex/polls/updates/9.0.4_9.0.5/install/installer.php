<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPollsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_polls_entries', 'score'))
        		$this->oDb->query("ALTER TABLE `bx_polls_entries` ADD `score` int(11) NOT NULL default '0' AFTER `votes`");
			if(!$this->oDb->isFieldExists('bx_polls_entries', 'sc_up'))
        		$this->oDb->query("ALTER TABLE `bx_polls_entries` ADD `sc_up` int(11) NOT NULL default '0' AFTER `score`");
			if(!$this->oDb->isFieldExists('bx_polls_entries', 'sc_down'))
        		$this->oDb->query("ALTER TABLE `bx_polls_entries` ADD `sc_down` int(11) NOT NULL default '0' AFTER `sc_up`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
