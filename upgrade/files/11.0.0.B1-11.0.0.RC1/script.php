<?php

    if (!$this->oDb->isFieldExists('sys_objects_page', 'author'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `author` int(11) NOT NULL DEFAULT '0' AFTER `id`");

    if (!$this->oDb->isFieldExists('sys_objects_page', 'added'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `added` int(11) NOT NULL DEFAULT '0' AFTER `author`");

    $a = $this->oDb->getAll("SELECT `id`, `content` FROM `sys_pages_blocks` WHERE `type` IN('html','raw') AND `text` = ''");
    foreach ($a as $r) {
        $this->oDb->query("UPDATE `sys_pages_blocks` SET `text` = :text, `text_updated` = :ts WHERE `id` = :id", array(
            'text' => trim(strip_tags($r['content'])),
            'ts' => time(),
            'id' => $r['id'],
        ));
    }

    return true;
