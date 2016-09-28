<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxOAuthUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_oauth_clients', 'parent_id'))
        		$this->oDb->query("ALTER TABLE `bx_oauth_clients` ADD `parent_id` int(10) unsigned DEFAULT 0 AFTER `scope`");
    	}

    	return parent::actionExecuteSql($sOperation);
    }
}
