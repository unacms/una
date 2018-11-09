<?php

    if (!$this->oDb->isFieldExists('sys_objects_view', 'module'))
        $this->oDb->query("ALTER TABLE `sys_objects_view` ADD `module` varchar(32) NOT NULL default '' AFTER `name`");

    return true;
