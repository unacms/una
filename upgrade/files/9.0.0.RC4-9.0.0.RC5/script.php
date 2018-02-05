<?php

    // FIELDS

    if (!$this->oDb->isFieldExists('sys_objects_grid', 'order_get_field'))
        $this->oDb->query("ALTER TABLE `sys_objects_grid` ADD `order_get_field` varchar(255) NOT NULL DEFAULT 'order_field'");

    if (!$this->oDb->isFieldExists('sys_objects_grid', 'order_get_dir'))
        $this->oDb->query("ALTER TABLE `sys_objects_grid` ADD `order_get_dir` varchar(255) NOT NULL DEFAULT 'order_dir'");

    if (!$this->oDb->isFieldExists('sys_objects_grid', 'filter_get'))
        $this->oDb->query("ALTER TABLE `sys_objects_grid` ADD `filter_get` varchar(255) NOT NULL DEFAULT 'filter'");
    
    // INDEXES
        
    if ($this->oDb->isIndexExists('sys_localization_categories', 'Name'))
        $this->oDb->query("ALTER TABLE `sys_localization_categories` DROP INDEX  `Name`");
    $this->oDb->query("ALTER TABLE `sys_localization_categories` ADD UNIQUE KEY `Name` (`Name`(191))");

    if ($this->oDb->isIndexExists('sys_localization_keys', 'Key'))
        $this->oDb->query("ALTER TABLE `sys_localization_keys` DROP INDEX  `Key`");
    $this->oDb->query("ALTER TABLE `sys_localization_keys` ADD UNIQUE KEY `Key` (`Key`(191))");

    if ($this->oDb->isIndexExists('sys_localization_keys', 'KeyFilter'))
        $this->oDb->query("ALTER TABLE `sys_localization_keys` DROP INDEX  `KeyFilter`");
    $this->oDb->query("ALTER TABLE `sys_localization_keys` ADD FULLTEXT KEY `KeyFilter` (`Key`(191))");
    
    if ($this->oDb->isIndexExists('sys_accounts', 'email'))
        $this->oDb->query("ALTER TABLE `sys_accounts` DROP INDEX  `email`");
    $this->oDb->query("ALTER TABLE `sys_accounts` ADD UNIQUE KEY `email` (`email`(191))");

    if ($this->oDb->isIndexExists('sys_search_extended_fields', 'field'))
        $this->oDb->query("ALTER TABLE `sys_search_extended_fields` DROP INDEX  `field`");
    $this->oDb->query("ALTER TABLE `sys_search_extended_fields` ADD UNIQUE KEY `field` (`object`(64), `name`(127))");

    if ($this->oDb->isIndexExists('sys_modules', 'path'))
        $this->oDb->query("ALTER TABLE `sys_modules` DROP INDEX  `path`");
    $this->oDb->query("ALTER TABLE `sys_modules` ADD UNIQUE KEY `path` (`path`(191))");

    if ($this->oDb->isIndexExists('sys_permalinks', 'check'))
        $this->oDb->query("ALTER TABLE `sys_permalinks` DROP INDEX  `check`");
    $this->oDb->query("ALTER TABLE `sys_permalinks` ADD UNIQUE KEY `check` (`standard`(80),`permalink`(80),`check`(30))");

    if ($this->oDb->isIndexExists('sys_objects_privacy', 'action'))
        $this->oDb->query("ALTER TABLE `sys_objects_privacy` DROP INDEX  `action`");
    $this->oDb->query("ALTER TABLE `sys_objects_privacy` ADD UNIQUE KEY `action` (`module`(64), `action`(127))");

    if ($this->oDb->isIndexExists('sys_form_inputs', 'display_name'))
        $this->oDb->query("ALTER TABLE `sys_form_inputs` DROP INDEX  `display_name`");
    $this->oDb->query("ALTER TABLE `sys_form_inputs` ADD UNIQUE KEY `display_name` (`object`(64),`name`(127))");

    if ($this->oDb->isIndexExists('sys_form_pre_lists', 'key'))
        $this->oDb->query("ALTER TABLE `sys_form_pre_lists` DROP INDEX  `key`");
    $this->oDb->query("ALTER TABLE `sys_form_pre_lists` ADD UNIQUE KEY `key` (`key`(191))");

    if ($this->oDb->isIndexExists('sys_form_pre_lists', 'ModuleAndKey'))
        $this->oDb->query("ALTER TABLE `sys_form_pre_lists` DROP INDEX  `ModuleAndKey`");
    $this->oDb->query("ALTER TABLE `sys_form_pre_lists` ADD FULLTEXT KEY `ModuleAndKey` (`module`(32), `key`(159))");

    if ($this->oDb->isIndexExists('sys_grid_fields', 'object_name'))
        $this->oDb->query("ALTER TABLE `sys_grid_fields` DROP INDEX  `object_name`");
    $this->oDb->query("ALTER TABLE `sys_grid_fields` ADD UNIQUE KEY `object_name` (`object`(64),`name`(127))");

    if ($this->oDb->isIndexExists('sys_grid_actions', 'object_name_type'))
        $this->oDb->query("ALTER TABLE `sys_grid_actions` DROP INDEX  `object_name_type`");
    $this->oDb->query("ALTER TABLE `sys_grid_actions` ADD UNIQUE KEY `object_name_type` (`object`(64),`type`,`name`(123))");

    if ($this->oDb->isIndexExists('sys_transcoder_images_files', 'transcoder_object'))
        $this->oDb->query("ALTER TABLE `sys_transcoder_images_files` DROP INDEX  `transcoder_object`");
    $this->oDb->query("ALTER TABLE `sys_transcoder_images_files` ADD UNIQUE KEY `transcoder_object` (`transcoder_object`(64),`handler`(127))");

    if ($this->oDb->isIndexExists('sys_transcoder_videos_files', 'transcoder_object'))
        $this->oDb->query("ALTER TABLE `sys_transcoder_videos_files` DROP INDEX  `transcoder_object`");
    $this->oDb->query("ALTER TABLE `sys_transcoder_videos_files` ADD UNIQUE KEY `transcoder_object` (`transcoder_object`(64),`handler`(127))");
    
    if ($this->oDb->isIndexExists('sys_transcoder_queue', 'transcoder_object'))
        $this->oDb->query("ALTER TABLE `sys_transcoder_queue` DROP INDEX  `transcoder_object`");
    $this->oDb->query("ALTER TABLE `sys_transcoder_queue` ADD UNIQUE KEY `transcoder_object` (`transcoder_object`(64),`file_id_source`(127))");

    if ($this->oDb->isIndexExists('sys_objects_page', 'uri'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` DROP INDEX  `uri`");
    $this->oDb->query("ALTER TABLE `sys_objects_page` ADD UNIQUE KEY `uri` (`uri`(191))");

    if ($this->oDb->isIndexExists('sys_objects_category', 'form_object'))
        $this->oDb->query("ALTER TABLE `sys_objects_category` DROP INDEX  `form_object`");
    $this->oDb->query("ALTER TABLE `sys_objects_category` ADD UNIQUE KEY `form_object` (`form_object`(64),`list_name`(127))");

    if ($this->oDb->isIndexExists('sys_std_widgets', 'widget-page'))
        $this->oDb->query("ALTER TABLE `sys_std_widgets` DROP INDEX  `widget-page`");
    $this->oDb->query("ALTER TABLE `sys_std_widgets` ADD UNIQUE KEY `widget-page` (`id`, `page_id`(187))");

    // CHANGE COLLATION

    $aPathInfo = pathinfo(__FILE__);
    $sChangeCollationSqlPath = $aPathInfo['dirname'] . '/change_collation.sql');
    $this->oDb->executeSQL($sChangeCollationSqlPath);

    return true;
