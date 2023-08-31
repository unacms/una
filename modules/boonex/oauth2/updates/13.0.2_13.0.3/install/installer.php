<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
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
            if(!$this->oDb->isFieldExists('bx_oauth_authorization_codes', 'id_token'))
                $this->oDb->query("ALTER TABLE `bx_oauth_authorization_codes` ADD `id_token` varchar(1000) DEFAULT NULL AFTER `scope`");
            if(!$this->oDb->isFieldExists('bx_oauth_authorization_codes', 'code_challenge'))
                $this->oDb->query("ALTER TABLE `bx_oauth_authorization_codes` ADD `code_challenge` varchar(1000) DEFAULT NULL AFTER `id_token`");
            if(!$this->oDb->isFieldExists('bx_oauth_authorization_codes', 'code_challenge_method'))
                $this->oDb->query("ALTER TABLE `bx_oauth_authorization_codes` ADD `code_challenge_method` varchar(20) DEFAULT NULL AFTER `code_challenge`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
