<?php

    if (!$this->oDb->isFieldExists('sys_menu_items', 'active_api'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `active_api` tinyint(4) NOT NULL DEFAULT '0' AFTER `active`");

    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'active_api'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `active_api` tinyint(4) NOT NULL DEFAULT '0' AFTER `active`");

    return true;
