<?php

    if (!$this->oDb->isFieldExists('sys_objects_search_extended', 'filter'))
        $this->oDb->query("ALTER TABLE `sys_objects_search_extended` ADD `filter` tinyint(4) NOT NULL DEFAULT '0' AFTER `title`");

    return true;
