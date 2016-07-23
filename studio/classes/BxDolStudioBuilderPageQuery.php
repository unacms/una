<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

class BxDolStudioBuilderPageQuery extends BxDolStudioPageQuery
{
    function __construct()
    {
        parent::__construct();
    }

    function getPages($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tp`.`object` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sWhereClause = " AND `tp`.`id`=:id ";
                break;
            case 'by_object':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'object' => $aParams['value']
                );

                $sWhereClause = " AND `tp`.`object`=:object ";
                break;

			case 'by_uri':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'uri' => $aParams['value']
                );

                $sWhereClause = " AND `tp`.`uri`=:uri ";
                break;

            case 'by_object_full':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'object' => $aParams['value']
                );

                $sSelectClause = ", `tpl`.`name` AS `layout_name`, `tpl`.`icon` AS `layout_icon`, `tpl`.`title` AS `layout_title`, `tpl`.`template` AS `layout_template`, `tpl`.`cells_number` AS `layout_cells_number`";
                $sJoinClause = "LEFT JOIN `sys_pages_layouts` AS `tpl` ON `tp`.`layout_id`=`tpl`.`id`";
                $sWhereClause = " AND `tp`.`object`=:object ";
                break;

            case 'by_module':
            	$aMethod['params'][1] = array(
                	'module' => $aParams['value']
                );

                $sWhereClause = " AND `tp`.`module`=:module ";
                break;

            case 'all':
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tp`.`id` AS `id`,
                `tp`.`object` AS `object`,
                `tp`.`uri` AS `uri`,
                `tp`.`title_system` AS `title_system`,
                `tp`.`title` AS `title`,
                `tp`.`module` AS `module`,
                `tp`.`layout_id` AS `layout_id`,
                `tp`.`visible_for_levels` AS `visible_for_levels`,
                `tp`.`visible_for_levels_editable` AS `visible_for_levels_editable`,
                `tp`.`url` AS `url`,
                `tp`.`meta_description` AS `meta_description`,
                `tp`.`meta_keywords` AS `meta_keywords`,
                `tp`.`meta_robots` AS `meta_robots`,
                `tp`.`cache_lifetime` AS `cache_lifetime`,
                `tp`.`cache_editable` AS `cache_editable`,
                `tp`.`deletable` AS `deletable`,
                `tp`.`override_class_name` AS `override_class_name`,
                `tp`.`override_class_file` AS `override_class_file`" . $sSelectClause . "
            FROM `sys_objects_page` AS `tp` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function updatePage($iId, $aFields)
    {
        $sSql = "UPDATE `sys_objects_page` SET `" . implode("`=?, `", array_keys($aFields)) . "`=?  WHERE `id`=?";
        $sSql = call_user_func_array(array($this, 'prepare'), array_merge(array($sSql), array_values($aFields), array($iId)));
        return $this->query($sSql);
    }

    function deletePages($aParams)
    {
    	$aBindings = array();
        $sWhereClause = "";

        switch($aParams['type']) {
            case 'by_id':
            	$aBindings = array(
                	'id' => $aParams['value']
                );

                $sWhereClause = "AND `tp`.`id`=:id ";
                break;

            case 'by_object':
            	$aBindings = array(
                	'object' => $aParams['value']
                );

                $sWhereClause = "AND `tp`.`object`=:object ";
                break;

            case 'all':
                break;
        }

        $sSql = "DELETE FROM `tp` USING `sys_objects_page` AS `tp` WHERE 1 " . $sWhereClause;
        return (int)$this->query($sSql, $aBindings) > 0;
    }

    function isUniqUri($sUri)
    {
        $sSql = $this->prepare("SELECT `id` FROM `sys_objects_page` WHERE `uri`=? LIMIT 1", $sUri);
        return (int)$this->getOne($sSql) <= 0;
    }

    function getLayouts($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tpl`.`id` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sWhereClause = "AND `tpl`.`id`=:id ";
                break;

            case 'all':
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tpl`.`id` AS `id`,
                `tpl`.`name` AS `name`,
                `tpl`.`icon` AS `icon`,
                `tpl`.`title` AS `title`,
                `tpl`.`template` AS `template`,
                `tpl`.`cells_number` AS `cells_number`" . $sSelectClause . "
            FROM `sys_pages_layouts` AS `tpl` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function getDesignBoxes($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tpd`.`order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sWhereClause = "AND `tpd`.`id`=:id ";
                break;

            case 'ordered':
                $sWhereClause = "AND `tpd`.`order`<>0 ";
                break;

            case 'all':
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tpd`.`id` AS `id`,
                `tpd`.`title` AS `title`,
                `tpd`.`template` AS `template`" . $sSelectClause . "
            FROM `sys_pages_design_boxes` AS `tpd` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function getModulesWithCopyableBlocks()
    {
    	$sSql = $this->prepare("SELECT
				`tm`.`name` AS `module`
			FROM `sys_modules` AS `tm`
			LEFT JOIN `sys_pages_blocks` AS `tpb` ON `tm`.`name`=`tpb`.`module`
			WHERE `tm`.`type`=? AND `tpb`.`copyable`=?
			GROUP BY `tm`.`name`", BX_DOL_MODULE_TYPE_MODULE, 1);
    	return $this->getColumn($sSql);
    }

    function getBlocks($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tpb`.`order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sWhereClause = "AND `tpb`.`id`=:id ";
                break;

            case 'skeleton_by_type':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'module' => BX_DOL_STUDIO_BP_SKELETONS,
                	'type' => $aParams['value']
                );

                $sWhereClause = "AND `tpb`.`object`='' AND `tpb`.`module`=:module AND `tpb`.`type`=:type ";
                break;

            case 'by_ids':
                $sWhereClause = "AND `tpb`.`id` IN (" . $this->implode_escape($aParams['value']) . ")";
                break;

            case 'by_object':
            	$aMethod['params'][1] = array(
                	'object' => $aParams['value']
                );

                $sWhereClause = "AND `tpb`.`object`=:object";
                break;

            case 'by_object_cell':
            	$aMethod['params'][1] = array(
                	'object' => $aParams['object'],
            		'cell_id' => $aParams['cell']
                );

                $sWhereClause = "AND `tpb`.`object`=:object AND `tpb`.`cell_id`=:cell_id";
                break;

            case 'by_module_to_copy':
            	$aMethod['params'][1] = array(
                	'module' => $aParams['value']
                );

                $sWhereClause = "AND `tpb`.`module`=:module AND `tpb`.`copyable`=1";

                if($aParams['value'] == BX_DOL_STUDIO_BP_SKELETONS)
                    $sWhereClause .= " AND `tpb`.`object`=''";
                break;

            case 'counter_by_pages':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'object';
                $aMethod['params'][2] = 'counter';
                $sSelectClause = ", COUNT(*) AS `counter`";
                $sGroupClause = "GROUP BY `tpb`.`object`";
                break;

            case 'all':
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tpb`.`id` AS `id`,
                `tpb`.`object` AS `object`,
                `tpb`.`cell_id` AS `cell_id`,
                `tpb`.`module` AS `module`,
                `tpb`.`title_system` AS `title_system`,
                `tpb`.`title` AS `title`,
                `tpb`.`designbox_id` AS `designbox_id`,
                `tpb`.`hidden_on` AS `hidden_on`,
                `tpb`.`visible_for_levels` AS `visible_for_levels`,
                `tpb`.`type` AS `type`,
                `tpb`.`content` AS `content`,
                `tpb`.`deletable` AS `deletable`,
                `tpb`.`copyable` AS `copyable`,
                `tpb`.`active` AS `active`,
                `tpb`.`order` AS `order`" . $sSelectClause . "
            FROM `sys_pages_blocks` AS `tpb` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function insertBlock($aFields)
    {
        $aFields['order'] = $this->getBlockOrderMax($aFields['object']) + 1;

        $sSql = "INSERT INTO `sys_pages_blocks` SET " . $this->arrayToSQL($aFields);
        return (int)$this->query($sSql) > 0;
    }

    function updateBlock($iId, $aFields)
    {
        $sSql = "UPDATE `sys_pages_blocks` SET " . $this->arrayToSQL($aFields) . " WHERE `id`=:id";
        return $this->query($sSql, array('id' => $iId));
    }

    function deleteBlocks($aParams)
    {
    	$aBindings = array();
        $sWhereClause = "";

        switch($aParams['type']) {
            case 'by_id':
            	$aBindings = array(
                	'id' => $aParams['value']
                );

                $sWhereClause = "AND `tpb`.`id`=:id ";
                break;

            case 'by_object':
            	$aBindings = array(
                	'object' => $aParams['value']
                );

                $sWhereClause = "AND `tpb`.`object`=:object ";
                break;

            case 'all':
                break;
        }

        $sSql = "DELETE FROM `tpb` USING `sys_pages_blocks` AS `tpb` WHERE 1 " . $sWhereClause;
        return (int)$this->query($sSql, $aBindings) > 0;
    }

    function resetBlocksByPage($sObject, $iCellId)
    {
        $sSql = $this->prepare("UPDATE `sys_pages_blocks` SET `cell_id`=? WHERE `object`=? AND `cell_id`>?", $iCellId, $sObject, $iCellId);
        return $this->query($sSql);
    }

    function getBlockOrderMax($sObject, $iCellId = 1)
    {
        $sSql = $this->prepare("SELECT MAX(`order`) FROM `sys_pages_blocks` WHERE `object`=? AND `cell_id`=? LIMIT 1", $sObject, $iCellId);
        return (int)$this->getOne($sSql);
    }

    function deleteImage($aParams)
    {
        $sWhereClause = "";

        switch($aParams['type']) {
            case 'by_id':
            	$aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sWhereClause = "AND `tpb`.`id`=:id ";
                break;

            case 'all':
                break;
        }

        $sSql = "DELETE FROM `tpb` USING `sys_pages_blocks` AS `tpb` WHERE 1 " . $sWhereClause;
        return (int)$this->query($sSql) > 0;
    }

    function getMenus()
    {
        $sSql = "SELECT `object`, `title` FROM `sys_objects_menu` WHERE 1";
        return $this->getPairs($sSql, 'object', 'title');
    }
}

/** @} */
