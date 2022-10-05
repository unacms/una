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

    return true;
