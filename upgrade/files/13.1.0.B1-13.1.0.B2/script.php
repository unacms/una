<?php

    if (!$this->oDb->isFieldExists('sys_recommendation_data', 'item_type'))
        $this->oDb->query("ALTER TABLE `sys_recommendation_data` ADD `item_type` varchar(64) NOT NULL default '' AFTER `item_id`");

    if (!$this->oDb->isFieldExists('sys_form_inputs', 'icon'))
        $this->oDb->query("ALTER TABLE `sys_form_inputs` ADD `icon` text NOT NULL AFTER `help`");

    return true;
