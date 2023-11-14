<?php

    if (!$this->oDb->isFieldExists('sys_objects_page', 'meta_title'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `meta_title` varchar(255) NOT NULL AFTER `content_info`");

    return true;
