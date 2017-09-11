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
			if(!$this->oDb->isFieldExists('bx_persons_data', 'gender'))
        		$this->oDb->query("ALTER TABLE `bx_persons_data` ADD `gender` varchar(255) DEFAULT NULL AFTER `description`");

			if(!$this->oDb->isFieldExists('bx_persons_data', 'birthday'))
        		$this->oDb->query("ALTER TABLE `bx_persons_data` ADD `birthday` date DEFAULT NULL AFTER `gender`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
