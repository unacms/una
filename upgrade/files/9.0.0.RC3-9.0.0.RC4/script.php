<?php

    if (!$this->oDb->isFieldExists('sys_localization_languages', 'Direction'))
        $this->oDb->query("ALTER TABLE `sys_localization_languages` ADD `Direction` enum('LTR','RTL') NOT NULL DEFAULT 'LTR' AFTER `Title`");

    if (!$this->oDb->isFieldExists('sys_localization_languages', 'LanguageCountry') && $this->oDb->isFieldExists('sys_localization_languages', 'Direction'))
        $this->oDb->query("ALTER TABLE `sys_localization_languages` ADD `LanguageCountry` varchar(8) NOT NULL AFTER `Direction`");

    if (!$this->oDb->isFieldExists('sys_search_extended_fields', 'pass'))
        $this->oDb->query("ALTER TABLE `sys_search_extended_fields` ADD `pass` varchar(32) NOT NULL AFTER `values`");
    
    return true;
