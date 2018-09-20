<?php

    if (!$this->oDb->isFieldExists('sys_menu_items', 'visibility_custom'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `visibility_custom` text NOT NULL AFTER `visible_for_levels`");

    return true;
