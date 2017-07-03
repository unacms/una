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
			if(!$this->oDb->isFieldExists('bx_organizations_data', 'comments'))
        		$this->oDb->query("ALTER TABLE `bx_organizations_data` ADD `comments` int(11) NOT NULL default '0' AFTER `favorites`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
