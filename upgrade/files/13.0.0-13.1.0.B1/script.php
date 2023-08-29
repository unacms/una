<?php

    if (!$this->oDb->isFieldExists('sys_objects_vote', 'Module'))
        $this->oDb->query("ALTER TABLE `sys_objects_vote` ADD `Module` varchar(32) NOT NULL default '' AFTER `Name`");

    if ($this->oDb->isFieldExists('sys_objects_vote', 'Module'))
        $this->oDb->query("UPDATE `sys_objects_vote` SET `Module` = 'system' WHERE `Name` IN('sys_cmts', 'sys_cmts_reactions', 'sys_form_fields_votes', 'sys_form_fields_reaction')");


   if (!$this->oDb->isFieldExists('sys_menu_items', 'hidden_on_cxt'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `hidden_on_cxt` varchar(255) NOT NULL DEFAULT '' AFTER `hidden_on`");

   if (!$this->oDb->isFieldExists('sys_objects_page', 'cover_title'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `cover_title` varchar(255) NOT NULL DEFAULT '' AFTER `cover_image`");

   if (!$this->oDb->isFieldExists('sys_pages_blocks', 'content_empty'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `content_empty` varchar(255) NOT NULL DEFAULT '' AFTER `content`");

    return true;
