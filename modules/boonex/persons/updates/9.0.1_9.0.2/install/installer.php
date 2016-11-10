<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPersonsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
			if(!$this->oDb->isFieldExists('bx_persons_data', 'favorites'))
        		$this->oDb->query("ALTER TABLE `bx_persons_data` ADD `favorites` int(11) NOT NULL default '0' AFTER `views`");

			if(!$this->oDb->isFieldExists('bx_persons_data', 'reports'))
        		$this->oDb->query("ALTER TABLE `bx_persons_data` ADD `reports` int(11) NOT NULL default '0' AFTER `favorites`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
