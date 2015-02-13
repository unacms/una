<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioPageQuery extends BxDolDb
{
    function __construct()
    {
        parent::__construct();
    }

    function getPages($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tp`.`id` ASC";

        switch($aParams['type']) {
            case 'all':
                break;
            case 'by_page_id':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare("AND `tp`.`id`=?", $aParams['value']);
                $sOrderClause = "";
                $sLimitClause = "LIMIT 1";
                break;
            case 'by_page_id_full':
                $aMethod['name'] = 'getRow';
                $sSelectClause .= ", `tw`.`id` AS `wid_id`, `tw`.`module` AS `wid_module`, `tw`.`url` AS `wid_url`, `tw`.`click` AS `wid_click`, `tw`.`icon` AS `wid_icon`, `tw`.`caption` AS `wid_caption`, `tw`.`cnt_notices` AS `wid_cnt_notices`, `tw`.`cnt_actions` AS `wid_cnt_actions` ";
                $sJoinClause .= "LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id`=`tw`.`page_id` ";
                $sWhereClause .= $this->prepare("AND `tp`.`id`=?", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;
            case 'by_page_name':
                $aMethod['name'] = 'getRow';
                $sWhereClause .= $this->prepare("AND `tp`.`name`=?", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;
            case 'by_page_name_full':
                $aMethod['name'] = 'getRow';
                $sSelectClause .= ", `tw`.`id` AS `wid_id`, `tw`.`module` AS `wid_module`, `tw`.`url` AS `wid_url`, `tw`.`click` AS `wid_click`, `tw`.`icon` AS `wid_icon`, `tw`.`caption` AS `wid_caption`, `tw`.`cnt_notices` AS `wid_cnt_notices`, `tw`.`cnt_actions` AS `wid_cnt_actions` ";
                $sJoinClause .= "LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id`=`tw`.`page_id` ";
                $sWhereClause .= $this->prepare("AND `tp`.`name`=?", $aParams['value']);
                $sLimitClause = "LIMIT 1";
                break;
            case 'by_page_names_full':
                $aMethod['name'] = 'getAll';
                $sSelectClause .= ", `tw`.`id` AS `wid_id`, `tw`.`module` AS `wid_module`, `tw`.`url` AS `wid_url`, `tw`.`click` AS `wid_click`, `tw`.`icon` AS `wid_icon`, `tw`.`caption` AS `wid_caption`, `tw`.`cnt_notices` AS `wid_cnt_notices`, `tw`.`cnt_actions` AS `wid_cnt_actions` ";
                $sJoinClause .= "LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id`=`tw`.`page_id` ";
                foreach($aParams['value'] as $sValue)
                    $sWhereClause .= $this->prepare(" OR `tp`.`name`=?", $sValue);
                $sWhereClause = "AND (0" . $sWhereClause . ")";
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tp`.`id` AS `id`,
                `tp`.`index` AS `index`,
                `tp`.`name` AS `name`,
                `tp`.`header` AS `header`,
                `tp`.`caption` AS `caption`,
                `tp`.`icon` AS `icon`" . $sSelectClause . "
            FROM `sys_std_pages` AS `tp` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }
    function isBookmarked($aPage)
    {
        if(empty($aPage['wid_id']))
            return false;

        $sSql = $this->prepare("SELECT `bookmark` FROM `sys_std_widgets` WHERE `id`=? LIMIT 1", $aPage['wid_id']);
        if((int)$this->getOne($sSql) == 0)
            return false;

        return true;
    }
    function bookmark(&$aPage)
    {
        if(empty($aPage['wid_id']))
            return false;

        $aPageHome = array();
        $iPageHome = $this->getPages(array('type' => 'by_page_name', 'value' => BX_DOL_STUDIO_PAGE_HOME), $aPageHome);
        if($iPageHome != 1)
            return false;

        $sSql = $this->prepare("UPDATE `sys_std_widgets` SET `bookmark`=? WHERE `id`=?", $aPage['bookmarked'] ? 0 : 1, $aPage['wid_id']);
        if(($bResult = (int)$this->query($sSql) > 0) === true)
            $aPage['bookmarked'] = !$aPage['bookmarked'];

        return $bResult;
    }
}

/** @} */
