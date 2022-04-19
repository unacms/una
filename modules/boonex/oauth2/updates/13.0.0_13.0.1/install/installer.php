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
            if(!$this->oDb->isFieldExists('bx_oauth_scopes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_oauth_scopes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
