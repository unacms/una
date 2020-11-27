<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioPageQuery extends BxDolDb implements iBxDolSingleton
{
    public function __construct()
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

    function getPages($aParams)
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
                $aMethod['params'][1] = array(
                    'id' => $aParams['value']
                );

                $sWhereClause .= "AND `tp`.`id`=:id";
                $sOrderClause = "";
                $sLimitClause = "LIMIT 1";
                break;

            case 'by_page_id_full':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['value']
                );

                $sSelectClause .= ", `tw`.`id` AS `wid_id`, `tw`.`module` AS `wid_module`, `tw`.`type` AS `wid_type`, `tw`.`url` AS `wid_url`, `tw`.`click` AS `wid_click`, `tw`.`icon` AS `wid_icon`, `tw`.`caption` AS `wid_caption`, `tw`.`cnt_notices` AS `wid_cnt_notices`, `tw`.`cnt_actions` AS `wid_cnt_actions` ";
                $sJoinClause .= "LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id`=`tw`.`page_id` ";
                $sWhereClause .= "AND `tp`.`id`=:id";
                $sLimitClause = "LIMIT 1";
                break;

            case 'by_page_name':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'name' => $aParams['value']
                );

                $sWhereClause .= "AND `tp`.`name`=:name";
                $sLimitClause = "LIMIT 1";
                break;

            case 'by_page_name_full':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'name' => $aParams['value'],
                );

                $sSelectClause .= ", `tw`.`id` AS `wid_id`, `tw`.`module` AS `wid_module`, `tw`.`type` AS `wid_type`, `tw`.`url` AS `wid_url`, `tw`.`click` AS `wid_click`, `tw`.`icon` AS `wid_icon`, `tw`.`caption` AS `wid_caption`, `tw`.`cnt_notices` AS `wid_cnt_notices`, `tw`.`cnt_actions` AS `wid_cnt_actions` ";
                $sJoinClause .= "LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id`=`tw`.`page_id` ";
                $sWhereClause .= "AND `tp`.`name`=:name";
                $sLimitClause = "LIMIT 1";
                break;

            case 'by_page_names_full':
                $aMethod['name'] = 'getAll';
                $aMethod['params'][1] = array();

                $sSelectClause .= ", `tw`.`id` AS `wid_id`, `tw`.`module` AS `wid_module`, `tw`.`url` AS `wid_url`, `tw`.`click` AS `wid_click`, `tw`.`icon` AS `wid_icon`, `tw`.`caption` AS `wid_caption`, `tw`.`cnt_notices` AS `wid_cnt_notices`, `tw`.`cnt_actions` AS `wid_cnt_actions` ";
                $sJoinClause .= "LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id`=`tw`.`page_id` ";
                $sWhereClause = "AND `tp`.`name` IN (" . $this->implode_escape($aParams['value']) . ")";
                break;
        }

        $aMethod['params'][0] = "SELECT 
                `tp`.`id` AS `id`,
                `tp`.`index` AS `index`,
                `tp`.`name` AS `name`,
                `tp`.`header` AS `header`,
                `tp`.`caption` AS `caption`,
                `tp`.`icon` AS `icon`" . $sSelectClause . "
            FROM `sys_std_pages` AS `tp` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    function isFeatured($aPage)
    {
        if(empty($aPage['wid_id']))
            return false;

        if((int)$this->getOne("SELECT `featured` FROM `sys_std_widgets` WHERE `id`=:id LIMIT 1", array('id' => $aPage['wid_id'])) == 0)
            return false;

        return true;
    }

    function featured(&$aPage)
    {
        if(empty($aPage['wid_id']))
            return false;

        $bResult = (int)$this->query("UPDATE `sys_std_widgets` SET `featured`=:featured WHERE `id`=:id", array(
            'featured' => $aPage['featured'] ? 0 : 1, 
            'id' => $aPage['wid_id']
        )) > 0;

        if($bResult)
            $aPage['featured'] = !$aPage['featured'];

        return $bResult;
    }

    function isBookmarked($aPage, $iProfileId)
    {
        if(empty($aPage['wid_id']))
            return false;

        $iBookmark = (int)$this->getOne("SELECT `bookmark` FROM `sys_std_widgets_bookmarks` WHERE `widget_id`=:widget_id AND `profile_id`=:profile_id LIMIT 1", array(
            'widget_id' => $aPage['wid_id'], 
            'profile_id' => $iProfileId
        ));

        return $iBookmark != 0;
    }

    function bookmark(&$aPage, $iProfileId)
    {
        if(empty($aPage['wid_id']))
            return false;

        $bResult = (int)$this->query("REPLACE INTO `sys_std_widgets_bookmarks` SET `widget_id`=:widget_id, `profile_id`=:profile_id, `bookmark`=:bookmark", array(
            'widget_id' => $aPage['wid_id'],
            'profile_id' => $iProfileId, 
            'bookmark' => $aPage['bookmark'] ? 0 : 1
        )) > 0;

        if($bResult)
            $aPage['bookmark'] = !$aPage['bookmark'];

        return $bResult;
    }
}

/** @} */
