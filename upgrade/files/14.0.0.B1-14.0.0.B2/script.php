<?php

    if (!$this->oDb->isFieldExists('sys_objects_score', 'is_undo'))
        $this->oDb->query("ALTER TABLE `sys_objects_score` ADD `is_undo` tinyint(1) NOT NULL default '0' AFTER `pruning`");

    return true;
