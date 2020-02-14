<?php

    if ($this->oDb->isFieldExists('sys_accounts', 'reffered'))
        $this->oDb->query("ALTER TABLE  `sys_accounts` CHANGE  `reffered`  `referred` VARCHAR( 255 ) NOT NULL");


    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'text'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `text` mediumtext NOT NULL AFTER `content`");

    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'text_updated'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `text_updated` int(11) NOT NULL AFTER `text`");

    if ($this->oDb->isIndexExists('sys_pages_blocks', 'object'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` DROP INDEX  `object`");
    $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD KEY `object` (`object`)");

    if ($this->oDb->isIndexExists('sys_pages_blocks', 'text'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` DROP INDEX  `text`");
    $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD FULLTEXT KEY `text` (`text`)");

    return true;
