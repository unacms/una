<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxOrgsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_organizations_data', 'join_confirmation'))
        		$this->oDb->query("ALTER TABLE `bx_organizations_data` ADD `join_confirmation` tinyint(4) NOT NULL DEFAULT '1' AFTER `featured`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
