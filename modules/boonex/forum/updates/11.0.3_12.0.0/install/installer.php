<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxForumUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isTableExists('bx_forum_covers'))
                $this->oDb->query("RENAME TABLE `bx_forum_files` TO `bx_forum_covers`");
            
            if((int)$this->oDb->getOne("SELECT `id` FROM `sys_objects_storage` WHERE `object`='bx_forum_covers' LIMIT 1") == 0) {
                $this->oDb->query("UPDATE `sys_objects_storage` SET `object`='bx_forum_covers', `table_files`='bx_forum_covers' WHERE `object`='bx_forum_files'");

                $this->oDb->query("UPDATE `sys_objects_transcoder` SET `source_params`='a:1:{s:6:\"object\";s:15:\"bx_forum_covers\";}' WHERE `object` IN ('bx_forum_preview', 'bx_forum_gallery', 'bx_forum_cover')");

                $this->oDb->query("UPDATE `sys_storage_ghosts` SET `object`='bx_forum_covers' WHERE `object`='bx_forum_files'");
            }

            if((int)$this->oDb->getOne("SELECT `id` FROM `sys_form_inputs` WHERE `object`='bx_forum' AND `name`='covers' LIMIT 1") == 0) {
                $this->oDb->query("UPDATE `sys_form_inputs` SET `name`='covers', `caption_system`='_bx_forum_form_entry_input_sys_covers', `caption`='_bx_forum_form_entry_input_covers' WHERE `object`='bx_forum' AND `name`='attachments'");

                $this->oDb->query("UPDATE `sys_form_display_inputs` SET `input_name`='covers' WHERE `display_name` LIKE 'bx_forum_entry%' AND `input_name`='attachments'");
            }

            if(!$this->oDb->isFieldExists('bx_forum_cmts', 'cmt_pinned'))
                $this->oDb->query("ALTER TABLE `bx_forum_cmts` ADD `cmt_pinned` int(11) NOT NULL default '0' AFTER `cmt_replies`");

            if(!$this->oDb->isFieldExists('bx_forum_favorites_track', 'list_id'))
                $this->oDb->query("ALTER TABLE `bx_forum_favorites_track` ADD `list_id` int(11) NOT NULL default '0' AFTER `author_id`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
