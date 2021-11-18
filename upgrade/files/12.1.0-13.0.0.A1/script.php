<?php

class BxDolEditorMock {
    public function getObjectName () {
        return '';
    }    
    public function setCustomConf ($s) {
    }
    public function setCustomPlugins ($sPlugins) {
    }
    public function setCustomToolbarButtons ($sButtons) {
    }
    public function getWidth ($iViewMode) {
        return '';
    }
    public function getSkins($bFullInfo = false) {
        return [];
    }
    public function setSkin($sSkin) {
    }
    public function attachEditor ($sSelector, $iViewMode, $bDynamicMode = false) {
        return '';
    }
}

    $GLOBALS['bxDolClasses']['BxDolEditor!sys_quill'] = new BxDolEditorMock();

    if (!$this->oDb->isFieldExists('sys_modules', 'updated'))
        $this->oDb->query("ALTER TABLE `sys_modules` ADD `updated` int(11) unsigned NOT NULL default '0' AFTER `hash`");

    if (!$this->oDb->isFieldExists('sys_cron_jobs', 'ts'))
        $this->oDb->query("ALTER TABLE `sys_cron_jobs` ADD `ts` int(11) NOT NULL");

    if (!$this->oDb->isFieldExists('sys_cron_jobs', 'timing'))
        $this->oDb->query("ALTER TABLE `sys_cron_jobs` ADD `timing` float NOT NULL");

    if (!$this->oDb->isFieldExists('sys_storage_ghosts', 'order'))
        $this->oDb->query("ALTER TABLE `sys_storage_ghosts` ADD `order` int(11) NOT NULL default '0' AFTER `created`");

    if (!$this->oDb->isFieldExists('sys_form_inputs', 'help'))
        $this->oDb->query("ALTER TABLE `sys_form_inputs` ADD `help` varchar(255) NOT NULL AFTER `info`");

    if (!$this->oDb->isFieldExists('sys_menu_items', 'parent_id'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `parent_id` int(11) NOT NULL DEFAULT '0' AFTER `id`");

    if (!$this->oDb->isFieldExists('sys_menu_items', 'primary'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `primary` tinyint(4) NOT NULL DEFAULT '0' AFTER `hidden_on`");

    if (!$this->oDb->isFieldExists('sys_transcoder_queue', 'pid'))
        $this->oDb->query("ALTER TABLE `sys_transcoder_queue` ADD `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `status`");

    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'cache_lifetime'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `cache_lifetime` int(11) NOT NULL DEFAULT '0' AFTER `text_updated`");

    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'help'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `help` varchar(255) NOT NULL AFTER `text_updated`");

    return true;
