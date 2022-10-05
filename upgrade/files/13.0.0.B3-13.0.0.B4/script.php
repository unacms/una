<?php

    if (!$this->oDb->isFieldExists('sys_objects_vote', 'Pruning'))
        $this->oDb->query("ALTER TABLE `sys_objects_vote` ADD `Pruning` int(11) NOT NULL default '31536000' AFTER `MaxValue`");

    if (!$this->oDb->isFieldExists('sys_objects_score', 'pruning'))
        $this->oDb->query("ALTER TABLE `sys_objects_score` ADD `pruning` int(11) NOT NULL default '31536000' AFTER `post_timeout`");

    if (!$this->oDb->isFieldExists('sys_objects_report', 'pruning'))
        $this->oDb->query("ALTER TABLE `sys_objects_report` ADD `pruning` int(11) NOT NULL default '31536000' AFTER `table_track`");

    if (!$this->oDb->isFieldExists('sys_objects_view', 'pruning'))
        $this->oDb->query("ALTER TABLE `sys_objects_view` ADD `pruning` int(11) NOT NULL default '31536000' AFTER `period`");

    if (!$this->oDb->isFieldExists('sys_objects_favorite', 'pruning'))
        $this->oDb->query("ALTER TABLE `sys_objects_favorite` ADD `pruning` int(11) NOT NULL default '31536000' AFTER `table_lists`");

    if (!$this->oDb->isFieldExists('sys_grid_actions', 'active'))
        $this->oDb->query("ALTER TABLE `sys_grid_actions` ADD `active` tinyint(4) NOT NULL DEFAULT '1' AFTER `confirm`");

    $this->oDb->query("ALTER TABLE `sys_storage_user_quotas` CHANGE `current_size` `current_size` bigint(20) NOT NULL");

    $aProfiles = $this->oDb->getColumn("SELECT `profile_id` FROM `sys_storage_user_quotas` WHERE `current_size` >= 2147483647");
    foreach ($aProfiles as $iProfileId) {
        $iSize = 0;
        $iNum = 0;
        $aTables = $this->oDb->getColumn("SELECT `table_files` FROM `sys_objects_storage`");
        foreach ($aTables as $sTable) {
            $iSize += $this->oDb->getOne("SELECT SUM(`size`) FROM `$sTable` WHERE `profile_id` = ?", $iProfileId);
            ++$iNum;
        }
        $this->oDb->query("UPDATE `sys_storage_user_quotas` SET `current_size` = :size, `current_number` = :num WHERE `profile_id` = :id", ['id' => $iProfileId, 'size' => $iSize, 'num' => $iNum]);
    }

    return true;
