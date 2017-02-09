<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxAclUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_acl_level_prices', 'trial'))
        		$this->oDb->query("ALTER TABLE `bx_acl_level_prices` ADD `trial` int(11) unsigned NOT NULL default '0' AFTER `period_unit`");
    	}

    	return parent::actionExecuteSql($sOperation);
    }
}
