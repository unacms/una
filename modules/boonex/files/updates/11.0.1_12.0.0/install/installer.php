<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxFilesUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_files_main', 'type'))
                $this->oDb->query("ALTER TABLE `bx_files_main` ADD `type` enum('file', 'folder') NOT NULL DEFAULT 'file' AFTER `status_admin`");
            if(!$this->oDb->isFieldExists('bx_files_main', 'parent_folder_id'))
                $this->oDb->query("ALTER TABLE `bx_files_main` ADD `parent_folder_id` int(10) unsigned NOT NULL DEFAULT 0 AFTER `type`");

            if(!$this->oDb->isFieldExists('bx_files_cmts', 'cmt_pinned'))
                $this->oDb->query("ALTER TABLE `bx_files_cmts` ADD `cmt_pinned` int(11) NOT NULL default '0' AFTER `cmt_replies`");

            if(!$this->oDb->isFieldExists('bx_files_favorites_track', 'list_id'))
                $this->oDb->query("ALTER TABLE `bx_files_favorites_track` ADD `list_id` int(11) NOT NULL default '0' AFTER `author_id`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
