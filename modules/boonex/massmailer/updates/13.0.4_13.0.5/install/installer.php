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
            if(!$this->oDb->isFieldExists('bx_massmailer_campaigns', 'is_track_links'))
                $this->oDb->query("ALTER TABLE `bx_massmailer_campaigns` ADD `is_track_links` smallint(1) NOT NULL AFTER `is_one_per_account`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
