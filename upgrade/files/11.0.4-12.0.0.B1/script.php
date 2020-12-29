<?php

    if (!$this->oDb->isFieldExists('sys_acl_levels_members', 'State'))
        $this->oDb->query("ALTER TABLE `sys_acl_levels_members` ADD `State` varchar(16) NOT NULL default '' AFTER `DateExpires`");


    if (!$this->oDb->isFieldExists('sys_badges', 'fontcolor'))
        $this->oDb->query("ALTER TABLE `sys_badges` ADD `fontcolor` varchar(32) NOT NULL default '' AFTER `color`");


    if (!$this->oDb->isFieldExists('sys_objects_report', 'module'))
        $this->oDb->query("ALTER TABLE `sys_objects_report` ADD `module` varchar(32) NOT NULL default '' AFTER `name`");
    $this->oDb->query("UPDATE `sys_objects_report` SET `module` = 'system' WHERE `name` = 'sys_cmts'");


    if (!$this->oDb->isFieldExists('sys_objects_report', 'object_comment'))
        $this->oDb->query("ALTER TABLE `sys_objects_report` ADD `object_comment` varchar(64) NOT NULL AFTER `base_url`");


    if (!$this->oDb->isFieldExists('sys_objects_favorite', 'table_lists'))
        $this->oDb->query("ALTER TABLE `sys_objects_favorite` ADD `table_lists` varchar(32) NOT NULL AFTER `table_track`");


    if (!$this->oDb->isFieldExists('sys_objects_feature', 'module'))
        $this->oDb->query("ALTER TABLE `sys_objects_feature` ADD `module` varchar(32) NOT NULL default '' AFTER `name`");


    if (!$this->oDb->isFieldExists('sys_objects_form', 'parent_form'))
        $this->oDb->query("ALTER TABLE `sys_objects_form` ADD `parent_form` varchar(64) NOT NULL DEFAULT '' AFTER `active`");


    if (!$this->oDb->isFieldExists('sys_form_inputs', 'privacy'))
        $this->oDb->query("ALTER TABLE `sys_form_inputs` ADD `privacy` tinyint(4) NOT NULL DEFAULT '0' AFTER `html`");


    if (!$this->oDb->isFieldExists('sys_form_inputs', 'rateable') && $this->oDb->isFieldExists('sys_form_inputs', 'privacy'))
        $this->oDb->query("ALTER TABLE `sys_form_inputs` ADD `rateable` varchar(32) NOT NULL DEFAULT '' AFTER `privacy`");


    if (!$this->oDb->isFieldExists('sys_menu_items', 'markers'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `markers` text NOT NULL AFTER `addon`");


    if (!$this->oDb->isFieldExists('sys_objects_grid', 'show_total_count'))
        $this->oDb->query("ALTER TABLE `sys_objects_grid` ADD `show_total_count` tinyint(4) NOT NULL DEFAULT '1' AFTER `responsive`");


    if (!$this->oDb->isFieldExists('sys_objects_page', 'sticky_columns'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `sticky_columns` tinyint(4) NOT NULL DEFAULT '0' AFTER `layout_id`");
    $this->oDb->query("UPDATE `sys_objects_page` SET `sticky_columns` = 1 WHERE `object` = 'sys_home'");


    if (!$this->oDb->isFieldExists('sys_objects_page', 'inj_head'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `inj_head` text NOT NULL AFTER `cache_editable`");


    if (!$this->oDb->isFieldExists('sys_objects_page', 'inj_footer') && $this->oDb->isFieldExists('sys_objects_page', 'inj_head'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `inj_footer` text NOT NULL AFTER `inj_head`");


    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'submenu'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `submenu` varchar(64) NOT NULL DEFAULT '' AFTER `designbox_id`");


    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'tabs') && $this->oDb->isFieldExists('sys_pages_blocks', 'submenu'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `tabs` tinyint(4) NOT NULL DEFAULT '0' AFTER `submenu`");
    $this->oDb->query("UPDATE `sys_pages_blocks` SET `tabs` = 1 WHERE `module` = 'system' AND `title_system` IN('_sys_page_block_title_sys_create_post', '_sys_page_block_title_sys_create_post_context', '_sys_page_block_title_sys_create_post_public')");


    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'async') && $this->oDb->isFieldExists('sys_pages_blocks', 'tabs'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `async` int(11) NOT NULL DEFAULT '0' AFTER `tabs`");
    $this->oDb->query("UPDATE `sys_pages_blocks` SET `async` = 4 WHERE `module` = 'system' AND `title_system` IN('_sys_page_block_title_sys_create_post', '_sys_page_block_title_sys_create_post_context', '_sys_page_block_title_sys_create_post_public')");


    if (!$this->oDb->isFieldExists('sys_objects_live_updates', 'init'))
        $this->oDb->query("ALTER TABLE `sys_objects_live_updates` ADD `init` tinyint(4) NOT NULL DEFAULT '0' AFTER `name`");


    if (!$this->oDb->isFieldExists('sys_std_widgets', 'type'))
        $this->oDb->query("ALTER TABLE `sys_std_widgets` ADD `type` varchar(32) NOT NULL default '' AFTER `module`");


    if (!$this->oDb->isFieldExists('sys_std_widgets', 'featured'))
        $this->oDb->query("ALTER TABLE `sys_std_widgets` ADD `featured` tinyint(4) unsigned NOT NULL default '0' AFTER `cnt_actions`");


    if ($this->oDb->isFieldExists('sys_std_widgets', 'bookmark'))
        $this->oDb->query("ALTER TABLE `sys_std_widgets` DROP `bookmark`");

    $aPathInfo = pathinfo(__FILE__);
    $sWidgetsSqlPath = $aPathInfo['dirname'] . '/widgets.sql';
    $this->oDb->executeSQL($sWidgetsSqlPath);

    return true;
