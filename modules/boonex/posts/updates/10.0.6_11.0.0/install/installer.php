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
            if(!$this->oDb->isFieldExists('bx_posts_posts', 'multicat'))
                $this->oDb->query("ALTER TABLE `bx_posts_posts` ADD `multicat` text NOT NULL AFTER `cat`");
            if(!$this->oDb->isFieldExists('bx_posts_posts', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_posts_posts` ADD `labels` text NOT NULL AFTER `text`");
            if(!$this->oDb->isFieldExists('bx_posts_posts', 'location'))
                $this->oDb->query("ALTER TABLE `bx_posts_posts` ADD `location` text NOT NULL AFTER `labels`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
