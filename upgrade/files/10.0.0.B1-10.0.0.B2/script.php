<?php

    if (!$this->oDb->isFieldExists('sys_objects_grid', 'responsive'))
        $this->oDb->query("ALTER TABLE `sys_objects_grid` ADD `responsive` tinyint(4) NOT NULL DEFAULT '1' AFTER `visible_for_levels`");

    if ($this->oDb->isFieldExists('sys_objects_grid', 'responsive'))
        $this->oDb->query("UPDATE `sys_objects_grid` SET `responsive` = 0 WHERE `object` IN('sys_grid_connections', 'sys_grid_connections_requests', 'sys_grid_subscriptions', 'sys_grid_subscribed_me', 'sys_grid_relations', 'sys_grid_related_me')");


    if (!$this->oDb->isFieldExists('sys_grid_fields', 'hidden_on'))
        $this->oDb->query("ALTER TABLE `sys_grid_fields` ADD `hidden_on` varchar(255) NOT NULL DEFAULT '' AFTER `params`");

    if ($this->oDb->isFieldExists('sys_grid_fields', 'hidden_on')) {
        $this->oDb->query("UPDATE `sys_grid_fields` SET `hidden_on` = '1' WHERE `object` = 'sys_grid_connections' AND `name` = 'info'");
        $this->oDb->query("UPDATE `sys_grid_fields` SET `hidden_on` = '1' WHERE `object` = 'sys_grid_connections_requests' AND `name` = 'info'");
    }

    return true;
