<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPostsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_posts_posts', 'thumb_data'))
                $this->oDb->query("ALTER TABLE `bx_posts_posts` ADD `thumb_data` varchar(50) NOT NULL AFTER `thumb`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
