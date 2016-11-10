<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxForumUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_forum_discussions', 'rate'))
        		$this->oDb->query("bx_forum_discussions` ADD `rate` float NOT NULL default '0' AFTER `views`");

    		if(!$this->oDb->isFieldExists('bx_forum_discussions', 'votes'))
        		$this->oDb->query("bx_forum_discussions` ADD `votes` int(11) NOT NULL default '0' AFTER `rate`");

    		if(!$this->oDb->isFieldExists('bx_forum_discussions', 'favorites'))
        		$this->oDb->query("bx_forum_discussions` ADD `favorites` int(11) NOT NULL default '0' AFTER `votes`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
