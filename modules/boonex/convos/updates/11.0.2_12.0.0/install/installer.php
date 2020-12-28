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
            if(!$this->oDb->isFieldExists('bx_convos_cmts', 'cmt_pinned'))
                $this->oDb->query("ALTER TABLE `bx_convos_cmts` ADD `cmt_pinned` int(11) NOT NULL default '0' AFTER `cmt_replies`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
