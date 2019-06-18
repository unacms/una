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
            if(!$this->oDb->isFieldExists('bx_posts_posts', 'rrate'))
        	        $this->oDb->query("ALTER TABLE `bx_posts_posts` ADD `rrate` float NOT NULL default '0' AFTER `votes`");
            if(!$this->oDb->isFieldExists('bx_posts_posts', 'rvotes'))
        	        $this->oDb->query("ALTER TABLE `bx_posts_posts` ADD `rvotes` int(11) NOT NULL default '0' AFTER `rrate`");
            if(!$this->oDb->isFieldExists('bx_posts_posts', 'disable_comments'))
        	        $this->oDb->query("ALTER TABLE `bx_posts_posts` ADD `disable_comments` tinyint(4) NOT NULL DEFAULT '0' AFTER `allow_view_to`");

            if($this->oDb->isTableExists('bx_posts_files') && !$this->oDb->isTableExists('bx_posts_covers')) {
                $this->oDb->query("RENAME TABLE `bx_posts_files` TO `bx_posts_covers`");

                $this->oDb->query("UPDATE `sys_objects_storage` SET `object`='bx_posts_covers', `table_files`='bx_posts_covers' WHERE `object`='bx_posts_files'");
                $this->oDb->query("UPDATE `sys_storage_deletions` SET `object`='bx_posts_covers' WHERE `object`='bx_posts_files'");
                $this->oDb->query("UPDATE `sys_storage_ghosts` SET `object`='bx_posts_covers' WHERE `object`='bx_posts_files'");

            }
        }

        return parent::actionExecuteSql($sOperation);
    }
}
