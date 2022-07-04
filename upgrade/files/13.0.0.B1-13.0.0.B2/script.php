<?php

    if (!$this->oDb->isFieldExists('sys_options_mixes', 'dark'))
        $this->oDb->query("ALTER TABLE `sys_options_mixes` ADD `dark` tinyint(1) NOT NULL default '0' AFTER `title`");

    return true;
