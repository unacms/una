<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxCnvUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_convos_conversations', 'allow_edit'))
        		$this->oDb->query("ALTER TABLE `bx_convos_conversations` ADD `allow_edit` tinyint(4) NOT NULL DEFAULT '0' AFTER `text`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
