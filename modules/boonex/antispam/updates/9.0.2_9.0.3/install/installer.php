<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxAntispamUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
			if ($this->oDb->isIndexExists('bx_antispam_disposable_email_domains', 'domain'))
				$this->oDb->query("ALTER TABLE `bx_antispam_disposable_email_domains` DROP INDEX `domain`");

			$this->oDb->query("ALTER TABLE `bx_antispam_disposable_email_domains` ADD UNIQUE KEY `domain` (`domain`(191))");
    	}

    	return parent::actionExecuteSql($sOperation);
    }
}
