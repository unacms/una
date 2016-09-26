<?php

    if (!$this->oDb->isFieldExists('sys_options_mixes', 'editable')) {
        $this->oDb->query("ALTER TABLE `sys_options_mixes` ADD `editable` tinyint(1) NOT NULL default '1' AFTER `active`");
    }

    if (!$this->oDb->isFieldExists('sys_menu_templates', 'visible')) {
        $this->oDb->query("ALTER TABLE `sys_menu_templates` ADD `visible` tinyint(4) NOT NULL DEFAULT '1' AFTER `title`");
        $this->oDb->query("UPDATE `sys_menu_templates` SET `visible` = 0 WHERE `template` IN('menu_footer.html', 'menu_toolbar.html', 'menu_main_submenu.html', 'menu_account_popup.html', 'menu_account_notifications.html', 'menu_custom.html')");
    }

    if (!$this->oDb->isFieldExists('sys_objects_page', 'cover')) {
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `cover` tinyint(4) NOT NULL DEFAULT '1' AFTER `module`");
    }
    if (!$this->oDb->isFieldExists('sys_objects_page', 'cover_image')) {
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `cover_image` int(11) NOT NULL DEFAULT '0' AFTER `cover`");
    }
    
    return true;
