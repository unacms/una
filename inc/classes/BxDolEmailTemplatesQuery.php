<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolDb');

/**
 * Database queries for BxDolEmailTemplates object.
 * @see BxDolEmailTemplates
 */
class BxDolEmailTemplatesQuery extends BxDolDb {

    public function getTemplate ($sTemplateName, $iLangAccount, $iLangDefault, $iLangFallback) {

        // get email templates keys
        $sSql = $this->prepare("SELECT `Subject`, `Body` FROM `sys_email_templates` WHERE `Name`=? LIMIT 1", $sTemplateName);
        $a = $this->getRow($sSql);
        if (!$a)
            return false;

        // build languages idsto search for translation in 
        $aLangs = array ($iLangAccount);
        if ($iLangDefault != $iLangAccount)
            $aLangs[] = $iLangDefault;
        if ($iLangFallback != $iLangAccount && $iLangFallback != $iLangDefault)
            $aLangs[] = $iLangFallback;

        // get translation for subject
        $sSubject = false;
        foreach ($aLangs as $iLang)
            if ($sSubject = $this->getLangTranslation($a['Subject'], $iLang))
                break;
        if (!$sSubject)
            return false;

        // get translation for body
        $sBody = false;
        foreach ($aLangs as $iLang)
            if ($sBody = $this->getLangTranslation($a['Body'], $iLang))
                break;
        if (!$sBody)
            return false;

        // return result
        return array ('Subject' => $sSubject, 'Body' => $sBody);
    }

    public function getLangTranslation ($sKey, $iLangId) {
        $sSql = $this->prepare("SELECT `s`.`String` FROM `sys_localization_strings` as `s` INNER JOIN `sys_localization_keys` AS `k` ON (`s`.`IDKey` = `k`.`ID`) WHERE `k`.`Key` = ? AND `s`.`IDLanguage` = ? LIMIT 1", $sKey, $iLangId);
        return $this->getOne($sSql);
    }
}

/** @} */
