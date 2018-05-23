<?php

    if (!$this->oDb->isFieldExists('sys_objects_cmts', 'Html'))
        $this->oDb->query("ALTER TABLE `sys_objects_cmts` ADD `Html` smallint(1) NOT NULL AFTER `CharsDisplayMax`");
    if (!$this->oDb->isFieldExists('sys_objects_cmts', 'ObjectReport'))
        $this->oDb->query("ALTER TABLE `sys_objects_cmts` ADD `ObjectReport` varchar(64) NOT NULL AFTER `ObjectVote`");
    if (!$this->oDb->isFieldExists('sys_objects_cmts', 'ObjectScore'))
        $this->oDb->query("ALTER TABLE `sys_objects_cmts` ADD `ObjectScore` varchar(64) NOT NULL AFTER `ObjectVote`");
    if ($this->oDb->isFieldExists('sys_objects_cmts', 'Nl2br'))
        $this->oDb->query("ALTER TABLE  `sys_objects_cmts` DROP  `Nl2br`");

    if ($this->oDb->isFieldExists('sys_queue_email', 'headers'))
        $this->oDb->query("ALTER TABLE  `sys_queue_email` DROP  `headers`");

    if (!$this->oDb->isFieldExists('sys_accounts', 'phone_confirmed'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD `phone_confirmed` tinyint(4) NOT NULL DEFAULT '0' AFTER `email_confirmed`");
    if (!$this->oDb->isFieldExists('sys_accounts', 'phone'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD `phone` varchar(255) NOT NULL AFTER `email_confirmed`");
    if (!$this->oDb->isFieldExists('sys_accounts', 'reffered'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD `reffered` varchar(255) NOT NULL DEFAULT '' AFTER `logged`");
    if (!$this->oDb->isFieldExists('sys_accounts', 'ip'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD `ip` varchar(40) NOT NULL DEFAULT '' AFTER `logged`");

    if (!$this->oDb->isFieldExists('sys_search_extended_fields', 'info'))
        $this->oDb->query("ALTER TABLE `sys_search_extended_fields` ADD `info` varchar(255) NOT NULL default '' AFTER `caption`");

    if (!$this->oDb->isFieldExists('sys_cmts_ids', 'reports'))
        $this->oDb->query("ALTER TABLE `sys_cmts_ids` ADD `reports` int(11) NOT NULL default '0' AFTER `votes`");
    if (!$this->oDb->isFieldExists('sys_cmts_ids', 'sc_down'))
        $this->oDb->query("ALTER TABLE `sys_cmts_ids` ADD `sc_down` int(11) NOT NULL default '0' AFTER `votes`");
    if (!$this->oDb->isFieldExists('sys_cmts_ids', 'sc_up'))
        $this->oDb->query("ALTER TABLE `sys_cmts_ids` ADD `sc_up` int(11) NOT NULL default '0' AFTER `votes`");
    if (!$this->oDb->isFieldExists('sys_cmts_ids', 'score'))
        $this->oDb->query("ALTER TABLE `sys_cmts_ids` ADD `score` int(11) NOT NULL default '0' AFTER `votes`");

    if (!$this->oDb->isFieldExists('sys_objects_privacy', 'spaces'))
        $this->oDb->query("ALTER TABLE `sys_objects_privacy` ADD `spaces` varchar(255) NOT NULL DEFAULT 'all' AFTER `default_group`");

    if (!$this->oDb->isFieldExists('sys_menu_items', 'hidden_on'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `hidden_on` varchar(255) NOT NULL DEFAULT '' AFTER `visible_for_levels`");
    
    return true;
