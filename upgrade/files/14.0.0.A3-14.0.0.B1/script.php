<?php

    if (!$this->oDb->isFieldExists('sys_cmts_images', 'dimensions'))
        $this->oDb->query("ALTER TABLE `sys_cmts_images` ADD `dimensions` varchar(24) NOT NULL AFTER `size`");

    if (!$this->oDb->isFieldExists('sys_objects_menu', 'persistent'))
        $this->oDb->query("ALTER TABLE `sys_objects_menu` ADD `persistent` tinyint(4) NOT NULL DEFAULT '0' AFTER `template_id`");

    return true;
