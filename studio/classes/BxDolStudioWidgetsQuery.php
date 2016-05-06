<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioWidgetsQuery extends BxDolStudioPageQuery implements iBxDolSingleton
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
    public static function getInstance()
    {
        $sClass = __CLASS__;
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new $sClass();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function reorder($sPage, $aItems)
    {
        $iResult = 0;
        foreach($aItems as $iKey => $iWidgetId) {
            $sSql = $this->prepare("UPDATE `sys_std_pages_widgets` AS `tpw`
                LEFT JOIN `sys_std_pages` AS `tp` ON `tpw`.`page_id`=`tp`.`id`
                SET `tpw`.`order`=?
                WHERE `tpw`.`widget_id`=?", $iKey + 1, $iWidgetId);
            $iResult += (int)$this->query($sSql);
        }

        return $iResult;
    }

    function getWidgets($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tw2p`.`order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );
                $sWhereClause .= "AND `tw`.`id`=:id";
                break;

            case 'by_page_id':
            	$aMethod['params'][1] = array(
                	'page_id' => $aParams['value']
                );
                $sWhereClause .= "AND `tw2p`.`page_id`=:page_id";
                break;

            case 'all_with_notices':
				$sWhereClause .= "AND `tw`.`cnt_notices`<>''";
            	break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tw`.`id` AS `id`,
                `tp`.`id` AS `page_id`,
                `tp`.`index` AS `page_index`,
                `tp`.`name` AS `page_name`,
                `tp`.`header` AS `page_header`,
                `tp`.`caption` AS `page_caption`,
                `tp`.`icon` AS `page_icon`,
                `tw`.`module` AS `module`,
                `tw`.`url` AS `url`,
                `tw`.`click` AS `click`,
                `tw`.`icon` AS `icon`,
                `tw`.`caption` AS `caption`,
                `tw`.`cnt_notices` AS `cnt_notices`,
                `tw`.`cnt_actions` AS `cnt_actions`,
                `tw`.`bookmark` AS `bookmark`,
                `tw2p`.`order` AS `order` " . $sSelectClause . "
            FROM `sys_std_widgets` AS `tw`
            LEFT JOIN `sys_std_pages` AS `tp` ON `tw`.`page_id`=`tp`.`id`
            LEFT JOIN `sys_std_pages_widgets` AS `tw2p` ON `tw`.`id`=`tw2p`.`widget_id` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }
}

/** @} */
