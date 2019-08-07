<?php

    if (!$this->oDb->isFieldExists('sys_objects_cmts', 'ObjectReaction'))
        $this->oDb->query("ALTER TABLE `sys_objects_cmts` ADD `ObjectReaction` varchar(64) NOT NULL default '' AFTER `ObjectVote`");


    if (!$this->oDb->isFieldExists('sys_cmts_ids', 'author_id'))
        $this->oDb->query("ALTER TABLE `sys_cmts_ids` ADD `author_id` int(11) NOT NULL DEFAULT '0' AFTER `cmt_id`");

    if (!$this->oDb->isFieldExists('sys_cmts_ids', 'rrate'))
        $this->oDb->query("ALTER TABLE `sys_cmts_ids` ADD `rrate` float NOT NULL default '0' AFTER `votes`");

    if (!$this->oDb->isFieldExists('sys_cmts_ids', 'rvotes') && $this->oDb->isFieldExists('sys_cmts_ids', 'rrate'))
        $this->oDb->query("ALTER TABLE `sys_cmts_ids` ADD `rvotes` int(11) NOT NULL default '0' AFTER `rrate`");



    $aSystems = $this->oDb->getPairs("SELECT `ID`, `Table` FROM `sys_objects_cmts` WHERE 1", "ID", "Table");
    $iUpdated = 0;
    $aComments = $this->oDb->getAll("SELECT * FROM `sys_cmts_ids` WHERE `author_id`='0'");
    foreach($aComments as $aComment) {
        if(!isset($aSystems[$aComment['system_id']]))
            continue;

        $iUpdated += $this->oDb->query("UPDATE `sys_cmts_ids` SET `author_id`=:author_id WHERE `id`=:id LIMIT 1", array(
            'id' => (int)$aComment['id'],
            'author_id' => (int)$this->oDb->getOne("SELECT `cmt_author_id` FROM `" . $aSystems[$aComment['system_id']] . "` WHERE `cmt_id`=:cmt_id", array(
                'cmt_id' => (int)$aComment['cmt_id']
            ))
        )) !== false ? 1 : 0;
    }

    return true;
