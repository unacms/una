<?php

    class BxDolTemplate2 extends BxDolTemplate {
        public static function getInstance()
        {
            if(!isset($GLOBALS['bxDolClasses']['BxDolTemplate'])) {
                $GLOBALS['bxDolClasses']['BxDolTemplate'] = new BxDolTemplate2();
                $GLOBALS['bxDolClasses']['BxDolTemplate']->init();
            }
            return $GLOBALS['bxDolClasses']['BxDolTemplate'];
        }
        function getCssClassName()
        {
            return str_replace('_', '-', $this->_sName);
        }
    }
    BxDolTemplate2::getInstance();


    if (!$this->oDb->isFieldExists('sys_keys', 'salt'))
        $this->oDb->query("ALTER TABLE `sys_keys` ADD `salt` varchar(255) NOT NULL AFTER `expire`");


    if (!$this->oDb->isFieldExists('sys_options_mixes2options', 'id'))
        $this->oDb->query("ALTER TABLE `sys_options_mixes2options` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_profiles', 'cfu_locked'))
        $this->oDb->query("ALTER TABLE `sys_profiles` ADD `cfu_locked` tinyint(4) NOT NULL DEFAULT '0' AFTER `content_id`");
    if (!$this->oDb->isFieldExists('sys_profiles', 'cfu_items'))
        $this->oDb->query("ALTER TABLE `sys_profiles` ADD `cfu_items` int(10) unsigned NOT NULL DEFAULT '2147483647' AFTER `content_id`");
    if (!$this->oDb->isFieldExists('sys_profiles', 'cfw_items'))
        $this->oDb->query("ALTER TABLE `sys_profiles` ADD `cfw_items` int(10) unsigned NOT NULL DEFAULT '2147483647' AFTER `content_id`");
    if (!$this->oDb->isFieldExists('sys_profiles', 'cfw_value'))
        $this->oDb->query("ALTER TABLE `sys_profiles` ADD `cfw_value` int(10) unsigned NOT NULL DEFAULT '2147483647' AFTER `content_id`");


    if (!$this->oDb->isFieldExists('sys_cmts_ids', 'status_admin'))
        $this->oDb->query("ALTER TABLE `sys_cmts_ids` ADD `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active' AFTER `reports`");


    if (!$this->oDb->isFieldExists('sys_cmts_meta_keywords', 'id'))
        $this->oDb->query("ALTER TABLE `sys_cmts_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_cmts_meta_mentions', 'id'))
        $this->oDb->query("ALTER TABLE `sys_cmts_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_cmts_votes', 'id'))
        $this->oDb->query("ALTER TABLE `sys_cmts_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_cmts_reactions', 'id'))
        $this->oDb->query("ALTER TABLE `sys_cmts_reactions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_form_fields_votes', 'id'))
        $this->oDb->query("ALTER TABLE `sys_form_fields_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_form_fields_reaction', 'id'))
        $this->oDb->query("ALTER TABLE `sys_form_fields_reaction` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_cmts_reports', 'id'))
        $this->oDb->query("ALTER TABLE `sys_cmts_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_cmts_reports_track', 'status'))
        $this->oDb->query("ALTER TABLE `sys_cmts_reports_track` ADD `status` tinyint(11) NOT NULL default '0' AFTER `date`");
    if (!$this->oDb->isFieldExists('sys_cmts_reports_track', 'checked_by'))
        $this->oDb->query("ALTER TABLE `sys_cmts_reports_track` ADD `checked_by` int(11) NOT NULL default '0' AFTER `date`");


    if (!$this->oDb->isFieldExists('sys_cmts_scores', 'id'))
        $this->oDb->query("ALTER TABLE `sys_cmts_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_alerts_handlers', 'active'))
        $this->oDb->query("ALTER TABLE `sys_alerts_handlers` ADD `active` tinyint(4) NOT NULL default '1' AFTER `service_call`");


    if (!$this->oDb->isFieldExists('sys_storage_tokens', 'iid'))
        $this->oDb->query("ALTER TABLE `sys_storage_tokens` ADD `iid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

    if (!$this->oDb->isFieldExists('sys_storage_ghosts', 'iid'))
        $this->oDb->query("ALTER TABLE `sys_storage_ghosts` ADD `iid` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_menu_items', 'hidden_on_col'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `hidden_on_col` int(11) NOT NULL DEFAULT '0' AFTER `hidden_on`");

    if (!$this->oDb->isFieldExists('sys_menu_items', 'hidden_on_pt'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `hidden_on_pt` int(11) NOT NULL DEFAULT '0' AFTER `hidden_on`");

    if (!$this->oDb->isFieldExists('sys_menu_items', 'collapsed'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `collapsed` tinyint(4) NOT NULL DEFAULT '0' AFTER `primary`");

    if ($this->oDb->isFieldExists('sys_menu_items', 'hidden_on_pt')) {
        $this->oDb->query("DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_toolbar_member' AND `name` = 'apps'");
        $this->oDb->query("INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `hidden_on_pt`, `active`, `copyable`, `order`) VALUES ('sys_toolbar_member', 'system', 'apps', '_sys_menu_item_title_system_apps', '', 'javascript:void(0);', '', '', 'qrcode', '', '', 0, 2147483646, 3, 1, 1, 0)");
    }


    if (!$this->oDb->isFieldExists('sys_grid_fields', 'id'))
        $this->oDb->query("ALTER TABLE `sys_grid_fields` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

    if (!$this->oDb->isFieldExists('sys_grid_actions', 'id'))
        $this->oDb->query("ALTER TABLE `sys_grid_actions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_objects_connection', 'profile_content'))
        $this->oDb->query("ALTER TABLE `sys_objects_connection` ADD `profile_content` tinyint(4) NOT NULL DEFAULT '0' AFTER `table`");

    if (!$this->oDb->isFieldExists('sys_objects_connection', 'profile_initiator'))
        $this->oDb->query("ALTER TABLE `sys_objects_connection` ADD `profile_initiator` tinyint(4) NOT NULL DEFAULT '1' AFTER `table`");

    if ($this->oDb->isFieldExists('sys_objects_connection', 'profile_content'))
        $this->oDb->query("UPDATE `sys_objects_connection` SET `profile_content` = 1 WHERE `object` IN('sys_profiles_friends', 'sys_profiles_subscriptions', 'sys_profiles_relations')");


    if (!$this->oDb->isFieldExists('sys_transcoder_images_files', 'id'))
        $this->oDb->query("ALTER TABLE `sys_transcoder_images_files` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

    if (!$this->oDb->isFieldExists('sys_transcoder_videos_files', 'id'))
        $this->oDb->query("ALTER TABLE `sys_transcoder_videos_files` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

    if (!$this->oDb->isFieldExists('sys_transcoder_audio_files', 'id'))
        $this->oDb->query("ALTER TABLE `sys_transcoder_audio_files` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

    if (!$this->oDb->isFieldExists('sys_transcoder_filters', 'id'))
        $this->oDb->query("ALTER TABLE `sys_transcoder_filters` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");


    if (!$this->oDb->isFieldExists('sys_objects_page', 'content_info'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `content_info` varchar(64) NOT NULL AFTER `url`");


    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'class'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `class` varchar(128) NOT NULL DEFAULT '' AFTER `designbox_id`");


    if (!$this->oDb->isFieldExists('sys_objects_metatags', 'module'))
        $this->oDb->query("ALTER TABLE `sys_objects_metatags` ADD `module` varchar(32) NOT NULL AFTER `object`");

    if (!$this->oDb->isFieldExists('sys_objects_category', 'module'))
        $this->oDb->query("ALTER TABLE `sys_objects_category` ADD `module` varchar(32) NOT NULL AFTER `object`");


    if (!$this->oDb->isFieldExists('sys_std_widgets_bookmarks', 'id'))
        $this->oDb->query("ALTER TABLE `sys_std_widgets_bookmarks` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

    return true;
