<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioLanguagesUtilsQuery extends BxDolLanguagesQuery
{
    function __construct()
    {
        parent::__construct();
    }

    function deleteLanguage($iId = 0)
    {
        $iId = (int)$iId;
        if($iId <= 0)
            return false;

        $sSql = $this->prepare("SELECT `ID`, `Name` FROM `sys_localization_languages` WHERE `ID`=?", $iId);
        $aLanguage = $this->getRow($sSql);
        if(empty($aLanguage))
            return false;

        $sSql = $this->prepare("SELECT COUNT(`IDKey`) FROM `sys_localization_strings` WHERE `IDLanguage`=?", $iId);
        $iStrings = (int)$this->getOne();

        $sSql = $this->prepare("DELETE FROM `sys_localization_strings` WHERE `IDLanguage`=?", $iId);
        if((int)$this->query($sSql) < $iStrings)
            return false;

        $sSql = $this->prepare("DELETE FROM `sys_localization_languages` WHERE `ID`=?", $iId);
        if((int)$this->query($sSql) <= 0)
            return false;

        @unlink( BX_DIRECTORY_PATH_ROOT . 'langs/lang-' . $aLanguage['Name'] . '.php');

        $sSql = $this->prepare("DELETE FROM `sys_email_templates` WHERE `LangID`=?", $iId);
        $this->query($sSql);

        return true;
    }

    function addKey($iCategoryId, $sKey)
    {
        $sSql = $this->prepare("SELECT `ID`, `IDCategory` FROM `sys_localization_keys` WHERE `Key`=? LIMIT 1", $sKey);
        $aKey = $this->getRow($sSql);
        if(!empty($aKey) && is_array($aKey)) {
        	if((int)$aKey['IDCategory'] != $iCategoryId)
        		$this->updateKeys(array('IDCategory' => $iCategoryId), array('ID' => $aKey['ID']));

            return $aKey['ID'];
        }

        $sSql = $this->prepare("INSERT INTO `sys_localization_keys` SET `IDCategory`=?, `Key`=?", $iCategoryId, $sKey);
        return (int)$this->query($sSql) > 0 ? $this->lastId() : false;
    }

    function addKeys($iLanguageId, $iCategoryId, &$aKeys)
    {
        foreach($aKeys as $sKey => $sValue) {
            $sQuery = $this->prepare("SELECT `ID` FROM `sys_localization_keys` WHERE `IDCategory`=? AND `Key`=? LIMIT 1", $iCategoryId, $sKey);
            $iKeyId = (int)$this->getOne($sQuery);

            if($iKeyId == 0) {
                $sQuery = $this->prepare("INSERT INTO `sys_localization_keys`(`IDCategory`, `Key`) VALUES(?, ?)", $iCategoryId, $sKey);
                if((int)$this->query($sQuery) <= 0)
                    continue;

                $iKeyId = (int)$this->lastId();
            }

            $sQuery = $this->prepare("INSERT IGNORE INTO `sys_localization_strings`(`IDKey`, `IDLanguage`, `String`) VALUES(?, ?, ?)", $iKeyId, $iLanguageId, $sValue);
            $this->query($sQuery);
        }
    }

    /**
     * Remove language key ONLY. Language strings should be already removed.
     * @param integer $iKeyId language key ID
     */
    function deleteKey($iKeyId)
    {
        $sQuery = $this->prepare("DELETE FROM `sys_localization_keys` WHERE `ID`=? LIMIT 1", $iKeyId);
        return (int)$this->query($sQuery) > 0;
    }

    function deleteKeys($aKeys)
    {
    	$iResult = 0;

        foreach($aKeys as $sKey => $sValue) {
            $sQuery = $this->prepare("DELETE FROM `tk`, `ts` USING `sys_localization_keys` AS `tk` LEFT JOIN `sys_localization_strings` AS `ts` ON `tk`.`ID`=`ts`.`IDKey` WHERE `tk`.`Key`=?", $sKey);
            $iResult += (int)$this->query($sQuery);
        }

        return $iResult;
    }

    function deleteKeysBy($aParams = array())
    {
    	$aBindings = array();
        $sWhereClause = "";

        switch($aParams['type']) {
            case 'by_key_id':
            	$aBindings['id'] = $aParams['value'];

                $sWhereClause = " AND `tk`.`ID`=:id";
                break;

            case 'by_cat_id':
            	$aBindings['id'] = $aParams['value'];

                $sWhereClause .= " AND `tc`.`ID`=:id";
                break;
        }

        $sSql = "DELETE FROM `tk`, `ts`
                USING
                    `sys_localization_categories` AS `tc`,
                    `sys_localization_keys` AS `tk`,
                    `sys_localization_strings` AS `ts`
                WHERE `tk`.`ID`=`ts`.`IDKey` AND `tk`.`IDCategory`=`tc`.`ID`" . $sWhereClause;
        return (int)$this->query($sSql, $aBindings);
    }

    function updateKeys($aParamsSet, $aParamsWhere)
    {
        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        $sSql = "UPDATE `sys_localization_keys` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return $this->query($sSql);
    }

    function addString($iKeyId, $iLanguageId, $sString)
    {
        $sSql = $this->prepare("INSERT IGNORE INTO `sys_localization_strings` SET `IDKey`=?, `IDLanguage`=?, `String`=?", $iKeyId, $iLanguageId, $sString);
        return (int)$this->query($sSql) > 0;
    }

    function updateString($iKeyId, $iLanguageId, $sString)
    {
        $sSql = $this->prepare("SELECT `IDKey` FROM `sys_localization_strings` WHERE `IDKey`=? AND `IDLanguage`=?", $iKeyId, $iLanguageId);
        $iKeyIdDb = (int)$this->getOne($sSql);

        if($iKeyIdDb != 0)
            $sSql = $this->prepare("UPDATE `sys_localization_strings` SET `String`=? WHERE `IDKey`=? AND `IDLanguage`=?", $sString, $iKeyId, $iLanguageId);
        else
            $sSql = $this->prepare("INSERT INTO `sys_localization_strings` SET `IDKey`=?, `IDLanguage`=?, `String`=?", $iKeyId, $iLanguageId, $sString);
        return (int)$this->query($sSql) > 0;
    }

    function deleteString($iKeyId, $iLanguageId)
    {
        $sSql = $this->prepare("DELETE FROM `sys_localization_strings` WHERE `IDKey`=? AND `IDLanguage`=?", $iKeyId, $iLanguageId);
        return (int)$this->query($sSql) > 0;
    }

    function deleteStringsBy($aParams = array())
    {
    	$aBindings = array();
        $sWhereClause = "";

        switch($aParams['type']) {
            case 'by_lang':
            	$aBindings = array(
            		'language_id' => $aParams['language_id']
        		);

                $sWhereClause .= " AND `tl`.`ID`=:language_id";
                break;

            case 'by_cat_and_lang':
            	$aBindings = array(
            		'category_id' => $aParams['category_id'],
            		'language_id' => $aParams['language_id']
            	);

                $sWhereClause .= " AND `tc`.`ID`=:category_id AND `tl`.`ID`=:language_id";
                break;

            case 'by_key_and_lang':
            	$aBindings = array(
            		'key' => $aParams['key'],
            		'language_id' => $aParams['language_id']
        		);

            	$sWhereClause .= " AND `tk`.`Key`=:key AND `tl`.`ID`=:language_id";
                break;
        }

        $sSql = "DELETE FROM `ts`
                USING `sys_localization_keys` AS `tk` 
				LEFT JOIN `sys_localization_categories` AS `tc` ON `tk`.`IDCategory`=`tc`.`ID` 
				LEFT JOIN `sys_localization_strings` AS `ts` ON `tk`.`ID`=`ts`.`IDKey` 
				LEFT JOIN `sys_localization_languages` AS `tl` ON `ts`.`IDLanguage`=`tl`.`ID`
                WHERE 1" . $sWhereClause;
        return (int)$this->query($sSql, $aBindings);
    }

    function addCategory($sName)
    {
        $sQuery = $this->prepare("INSERT IGNORE INTO `sys_localization_categories` SET `Name`=?", $sName);
        if((int)$this->query($sQuery) > 0)
            return (int)$this->lastId();

        $iCategoryId = 0;
        $this->getCategoriesBy(array('type' => 'id_by_name', 'value' => $sName), $iCategoryId);
        return $iCategoryId;
    }

    function deleteCategory($sName)
    {
        $sQuery = $this->prepare("DELETE FROM `sys_localization_categories` WHERE `Name`=?", $sName);
        $this->query($sQuery);
    }
}

/** @} */
