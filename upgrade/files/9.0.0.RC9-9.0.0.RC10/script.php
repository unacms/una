<?php

    if (!$this->oDb->isFieldExists('sys_menu_items', 'visibility_custom'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `visibility_custom` text NOT NULL AFTER `visible_for_levels`");

    // update language keys in custom search table

    BxDolLanguages::getInstance();
    $oLang = BxDolStudioLanguagesUtils::getInstance();
    $aLangIds = array_keys($oLang->getLanguages(true));
    $fProcessKey = function ($sKey) use ($oLang, $aLangIds) {
        $sKeyNew = $sKey . '_' . time();        
        $aStrings = $oLang->getLanguageString($sKey);
        foreach($aLangIds as $iLangId) {
            if (isset($aStrings[$iLangId]) && isset($aStrings[$iLangId]['string']))
                $oLang->addLanguageString($sKeyNew, $aStrings[$iLangId]['string'], $iLangId, 0, false);
        }
        return $sKeyNew;
    };

    $aFieldsWithLangs = array('caption', 'info');
    $a = $this->oDb->getAll('SELECT * FROM `sys_search_extended_fields` ORDER BY `id`');
    foreach ($a as $r) {
        foreach ($aFieldsWithLangs as $sField) {
            if (!$r[$sField])
                continue;
            if (preg_match('/_[0-9]{10}$/', $r[$sField])) // skip processed fields
                continue;
            $sKeyNew = $fProcessKey($r[$sField]); // generate new translation
            $this->oDb->query("UPDATE `sys_search_extended_fields` SET `$sField` = :key WHERE `id` = :id", array('key' => $sKeyNew, 'id' => $r['id'])); // save new key
        }
    }

    $oLang->compileLanguage();

    return true;
