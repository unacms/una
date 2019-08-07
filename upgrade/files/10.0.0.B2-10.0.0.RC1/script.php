<?php

    if (!$this->oDb->isFieldExists('sys_objects_cmts', 'ObjectReaction'))
        $this->oDb->query("ALTER TABLE `sys_objects_cmts` ADD `ObjectReaction` varchar(64) NOT NULL default '' AFTER `ObjectVote`");


    if (!$this->oDb->isFieldExists('sys_cmts_ids', 'author_id'))
        $this->oDb->query("ALTER TABLE `sys_cmts_ids` ADD `author_id` int(11) NOT NULL DEFAULT '0' AFTER `cmt_id`");

    if (!$this->oDb->isFieldExists('sys_cmts_ids', 'rrate'))
        $this->oDb->query("ALTER TABLE `sys_cmts_ids` ADD `rrate` float NOT NULL default '0' AFTER `votes`");

    if (!$this->oDb->isFieldExists('sys_cmts_ids', 'rvotes') && $this->oDb->isFieldExists('sys_cmts_ids', 'rrate'))
        $this->oDb->query("ALTER TABLE `sys_cmts_ids` ADD `rvotes` int(11) NOT NULL default '0' AFTER `rrate`");
    
    return true;
