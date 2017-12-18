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
    		if(!$this->oDb->isFieldExists('bx_forum_discussions', 'thumb'))
        		$this->oDb->query("ALTER TABLE `bx_forum_discussions` ADD `thumb` int(11) NOT NULL AFTER `changed`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
