<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxMassMailerUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_massmailer_campaigns', 'is_one_per_account'))
                $this->oDb->query("ALTER TABLE `bx_massmailer_campaigns` ADD `is_one_per_account` smallint(1) NOT NULL AFTER `email_list`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
