<?php

    if (!$this->oDb->isIndexExists('sys_accounts', 'logged'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD KEY `logged` (`logged`)");

    return true;
