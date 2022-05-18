<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxStrmUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_stream_recordings_seq', 'tries'))
                $this->oDb->query("ALTER TABLE `bx_stream_recordings_seq` ADD `tries` tinyint(4) NOT NULL DEFAULT 0 AFTER `content_id`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
