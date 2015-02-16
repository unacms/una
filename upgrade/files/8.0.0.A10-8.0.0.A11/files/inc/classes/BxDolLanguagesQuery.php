<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxDolLanguagesQuery extends BxDolDb implements iBxDolSingleton
{
    function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolLanguagesQuery();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function getLanguageId($sName, $bFromCache = true)
    {
        $sSql = $this->prepare("SELECT `ID` FROM `sys_localization_languages` WHERE `Name`=? AND `Enabled`='1' LIMIT 1", $sName);

        if($bFromCache)
            return (int)$this->fromCache('checkLangExists_' . $sName, 'getOne', $sSql);
        else
            return (int)$this->getOne($sSql);
    }
    function getLanguages($bIdAsKey = false, $bActiveOnly = false)
    {
        $sSql = "SELECT * FROM `sys_localization_languages` WHERE 1 " . ($bActiveOnly ? " AND `Enabled`='1'" : "") . " ORDER BY `Title` ASC";
        return $this->getPairs($sSql, $bIdAsKey ? 'ID' : 'Name', 'Title');
    }

    function getLanguagesBy($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = " `tl`.`ID` ASC ";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare(" AND `tl`.`ID`=?", $aParams['value']);
                break;
            case 'by_name':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare(" AND `tl`.`Name`=?", $aParams['value']);
                break;
            case 'default':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare(" AND `tl`.`Name`=?", getParam('lang_default'));
                break;
            case 'active':
                $sWhereClause = " AND `tl`.`Enabled`='1'";
                break;
            case 'all':
                $sOrderClause = " `tl`.`Name` ASC ";
                break;
            case 'all_by_id':
                $sWhereClause .= $this->prepare(" AND `tl`.`ID`=?", $aParams['value']);
                $sOrderClause = " `tl`.`Name` ASC ";
                break;
			case 'all_by_name':
                $sWhereClause .= $this->prepare(" AND `tl`.`Name`=?", $aParams['value']);
                $sOrderClause = " `tl`.`Name` ASC ";
                break;
            case 'all_key_id':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'id';

                if(isset($aParams['language']) && (int)$aParams['language'] != 0)
                    $sWhereClause .= $this->prepare(" AND `tl`.`ID`=?", $aParams['language']);
                break;
        }

        $aMethod['params'][0] = "SELECT SQL_CALC_FOUND_ROWS
                `tl`.`ID` AS `id`,
                `tl`.`Name` AS `name`,
                `tl`.`Flag` AS `flag`,
                `tl`.`Title` AS `title`,
                `tl`.`Enabled` AS `enabled`" . $sSelectClause . "
            FROM `sys_localization_languages` AS `tl`" . $sJoinClause . "
            WHERE 1" . $sWhereClause . "
            ORDER BY" . $sOrderClause . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return count($aItems) > 0;

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function getKeys()
    {
        $sSql = "SELECT `ID`, `IDCategory`, `Key` FROM `sys_localization_keys`";
        return $this->getAllWithKey($sSql, 'ID');
    }

    function getKeysBy($aParams, &$aItems)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`tk`.`ID` AS `id`, `tk`.`IDCategory` AS `category_id`, `tk`.`Key` AS `key`";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = " `tk`.`Key` ASC ";

        switch($aParams['type']) {
            case 'by_ids':
                $sSelectClause .= ", `tc`.`Name` AS `category_name`, `ts`.`IDLanguage` AS `language_id`, `tl`.`Name` AS `language_name`, `tl`.`Title` AS `language_title`, `ts`.`String` AS `string`";
                $sJoinClause = " LEFT JOIN `sys_localization_categories` AS `tc` ON `tk`.`IDCategory`=`tc`.`ID` LEFT JOIN `sys_localization_strings` AS `ts` ON `tk`.`ID`=`ts`.`IDKey` LEFT JOIN `sys_localization_languages` AS `tl` ON `ts`.`IDLanguage`=`tl`.`ID` ";
                $sWhereClause .= " AND `tk`.`ID` IN ('" . implode("','", $aParams['value']) . "')";

                if(isset($aParams['language']) && (int)$aParams['language'] != 0)
                    $sWhereClause .= $this->prepare(" AND `tl`.`ID`=?", $aParams['language']);
                break;

            case 'by_name':
                $aMethod['name'] = 'getRow';
                $sWhereClause = $this->prepare(" AND `tk`.`Key`=? ", $aParams['value']);
                break;

			case 'by_language_name_key_key':
            	$aMethod['name'] = 'getAllWithKey';
            	$aMethod['params'][1] = 'key';

                $sSelectClause .= ", `ts`.`String` AS `string` ";
                $sJoinClause = " LEFT JOIN `sys_localization_strings` AS `ts` ON `tk`.`ID`=`ts`.`IDKey` LEFT JOIN `sys_localization_languages` AS `tl` ON `ts`.`IDLanguage`=`tl`.`ID` ";
                $sWhereClause = $this->prepare(" AND `tl`.`Name`=? ", $aParams['value']);
                break;

            case 'by_language_id_key_key':
            	$aMethod['name'] = 'getAllWithKey';
            	$aMethod['params'][1] = 'key';

                $sSelectClause .= ", `ts`.`String` AS `string` ";
                $sJoinClause = " LEFT JOIN `sys_localization_strings` AS `ts` ON `tk`.`ID`=`ts`.`IDKey` ";
                $sWhereClause = $this->prepare(" AND `ts`.`IDLanguage`=? ", (int)$aParams['value']);
                break;

            case 'search':
                $aMethod['name'] = 'getColumn';

                $sSelectClause = "DISTINCT `tk`.`ID` AS `id`";
                $sJoinClause = " LEFT JOIN `sys_localization_categories` AS `tc` ON `tk`.`IDCategory`=`tc`.`ID` LEFT JOIN `sys_localization_strings` AS `ts` ON `tk`.`ID`=`ts`.`IDKey` LEFT JOIN `sys_localization_languages` AS `tl` ON `ts`.`IDLanguage`=`tl`.`ID` ";

                if(isset($aParams['category']) && (int)$aParams['category'] != 0)
                    $sWhereClause .= $this->prepare(" AND `tc`.`ID`=?", $aParams['category']);

                if(isset($aParams['language']) && (int)$aParams['language'] != 0)
                    $sWhereClause .= $this->prepare(" AND `tl`.`ID`=?", $aParams['language']);

                if(isset($aParams['keyword']) && $aParams['keyword'] != '' && $aParams['keyword'] != _t('_adm_pgt_txt_keyword'))
                    $sWhereClause .= $this->prepare(" AND (`tk`.`Key` LIKE ? OR `ts`.`String` LIKE ?)", '%' . $aParams['keyword'] . '%', '%' . $aParams['keyword'] . '%');

                $iStart = 0;
                if(isset($aParams['start']) && (int)$aParams['start'] != 0)
                    $iStart = $aParams['start'];

                $iLength = 20;
                if(isset($aParams['length']) && (int)$aParams['length'] != 0)
                    $iLength = $aParams['length'];

                $sLimitClause .= $this->prepare(" LIMIT ?, ?", $iStart, $iLength);
                break;

            case 'counter_by_category':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'category_id';
                $aMethod['params'][2] = 'counter';
                $sSelectClause .= ", COUNT(*) AS `counter`";
                $sGroupClause = "GROUP BY `tk`.`IDCategory`";
                break;
        }

        $aMethod['params'][0] = "SELECT " . $sSelectClause . "
            FROM `sys_localization_keys` AS `tk`" . $sJoinClause . "
            WHERE 1" . $sWhereClause . " " . $sGroupClause . "
            ORDER BY" . $sOrderClause . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        return $aItems !== false;
    }

    function getCategories()
    {
        $sSql = "SELECT `ID`, `Name` FROM `sys_localization_categories` ORDER BY `Name`";
        return $this->getPairs($sSql, 'ID', 'Name');
    }

    function getCategoriesBy($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`tc`.`ID` AS `id`, `tc`.`Name` AS `name`";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = " `tc`.`ID` ASC ";

        switch($aParams['type']) {
            case 'id_by_name':
                $aMethod['name'] = 'getOne';
                $sSelectClause = "`tc`.`ID` AS `id`";
                $sWhereClause = $this->prepare(" AND `tc`.`Name`=?", $aParams['value']);
                break;
            case 'by_name':
                $aMethod['name'] = 'getRow';
                $sWhereClause = $this->prepare(" AND `tc`.`Name`=?", $aParams['value']);
                break;
            case 'all':
                $sOrderClause = " `tc`.`Name` ASC ";
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                " . $sSelectClause . "
            FROM `sys_localization_categories` AS `tc`" . $sJoinClause . "
            WHERE 1" . $sWhereClause . "
            ORDER BY" . $sOrderClause . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function getStringsBy($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "`ts`.`IDKey` ASC";

        switch($aParams['type']) {
            case 'by_key_language_id':
                $aMethod['name'] = 'getRow';
                $sJoinClause = "LEFT JOIN `sys_localization_keys` AS `tk` ON `ts`.`IDKey`=`tk`.`ID`";
                $sWhereClause .= $this->prepare("AND `tk`.`Key`=? AND `ts`.`IDLanguage`=?", $aParams['key'], $aParams['language_id']);
                break;

            case 'all_by_key_key_language_id':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'language_id';

                $sJoinClause = "LEFT JOIN `sys_localization_keys` AS `tk` ON `ts`.`IDKey`=`tk`.`ID`";
                $sWhereClause .= $this->prepare("AND `tk`.`Key`=?", $aParams['value']);
                break;
        }

        $aMethod['params'][0] = "SELECT SQL_CALC_FOUND_ROWS
                `ts`.`IDKey` AS `key_id`,
                `ts`.`IDLanguage` AS `language_id`,
                `ts`.`String` AS `string` " . $sSelectClause . "
            FROM `sys_localization_strings` AS `ts` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . "
            ORDER BY " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return count($aItems) > 0;

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }
}

/** @} */
