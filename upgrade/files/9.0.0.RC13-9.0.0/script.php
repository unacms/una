<?php

    if (!$this->oDb->isIndexExists('sys_accounts', 'profile_id'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD INDEX (`profile_id`)");

    return true;
