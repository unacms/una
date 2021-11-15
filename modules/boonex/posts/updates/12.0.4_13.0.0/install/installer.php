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
            if($this->oDb->isFieldExists('bx_posts_posts', 'disable_comments')) {
                $this->oDb->query("ALTER TABLE `bx_posts_posts` CHANGE `disable_comments` `allow_comments` tinyint(4) NOT NULL DEFAULT '1'");
                $this->oDb->query("UPDATE `bx_posts_posts` SET `allow_comments`=1-`allow_comments`");
            }

            if(!$this->oDb->isFieldExists('bx_posts_reports_track', 'checked_by'))
                $this->oDb->query("ALTER TABLE `bx_posts_reports_track` ADD `checked_by` int(11) NOT NULL default '0' AFTER `date`");
            if(!$this->oDb->isFieldExists('bx_posts_reports_track', 'status'))
                $this->oDb->query("ALTER TABLE `bx_posts_reports_track` ADD `status` tinyint(11) NOT NULL default '0' AFTER `checked_by`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
