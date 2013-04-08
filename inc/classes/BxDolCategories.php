<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolTags');

define("CATEGORIES_DIVIDER", ';');

class BxDolCategories extends BxDolTags {
    var $sAutoApprovePrefix;

    function BxDolCategories ($iPossOwner = 0) {
        parent::BxDolTags();
        $this->iViewer = (int)$iPossOwner > 0 ? (int)$iPossOwner : $this->iViewer;
        $this->sCacheFile = 'sys_objects_categories';
        $this->sNonParseParams = 'tags_non_parsable';
        $this->sCacheTable = 'sys_objects_categories';
        $this->sTagTable = 'sys_categories';
        $this->aTagFields = array(
            'id' => 'ID',
            'type' => 'Type',
            'tag' => 'Category',
            'owner' => 'Owner',
            'status' => 'Status',
            'date' => 'Date'
        );
        $this->sAutoApprovePrefix = 'category_auto_app_';
        $this->bToLower = false;
    }

    function getGroupChooser ($sType, $iOwnerId = 0, $bForm = false, $sCustomValues = '') {
        $a = $this->getCategoriesList ($sType, $iOwnerId, $bForm);
        $a = array('' => _t('_Please_Select_')) + (is_array($a) ? $a : array());

        $aCustomValues = $this->explodeTags($sCustomValues);
        foreach($aCustomValues as $iIndex => $sValue)
            $a[$sValue] = $sValue;

        return array(
            'type' => 'select_box',
                'name' => 'Categories',
                'caption' => _t('_Categories'),
                'values' => $a,
                'required' => true,
                'checker' => array (
                    'func' => 'avail',
                    'error' => _t ('_sys_err_categories'),
                ),
                'db' => array (
                    'pass' => 'Categories',
                ),
            );
    }

    function getCategoriesList ($sType, $iOwnerId = 0, $bForm = false) {
        $oDb = BxDolDb::getInstance();
        $this->getTagObjectConfig();
        $sType = array_key_exists($sType, $this->aTagObjects) === true ? $sType : 'bx_photos';
        $iOwnerId = (int)$iOwnerId;
        $sqlQuery = $oDb->prepare("SELECT `cat`.`{$this->aTagFields['tag']}`
                     FROM `{$this->sTagTable}` `cat`
                     WHERE (`cat`.`{$this->aTagFields['owner']}` = 0 OR `cat`.`{$this->aTagFields['owner']}` = ?)
                     AND `cat`.`{$this->aTagFields['type']}` = ? AND `cat`.`{$this->aTagFields['status']}` = 'active'
                     __sqlAdd__
                     GROUP BY `cat`.`{$this->aTagFields['tag']}`", $iOwnerId, $sType);

        $aAddSql = array();

        if (getParam($this->sAutoApprovePrefix . $sType) != 'on')
            $aAddSql[] = " AND `cat`.`{$this->aTagFields['status']}` = 'active'";

        $sqlAdd = '';
        foreach ($aAddSql as $sValue)
            $sqlAdd .= $sValue;

        $sqlQuery = str_replace('__sqlAdd__', $sqlAdd, $sqlQuery);

        $a = $oDb->getAll($sqlQuery);
        foreach ($a as $aList) {
            if ($bForm)
                $aCatList[$aList[$this->aTagFields['tag']]] = $aList[$this->aTagFields['tag']];
            else
                $aCatList[] = $aList[$this->aTagFields['tag']];
        }
        return $aCatList;
    }

    function getTagList($aParam) {
        $oDb = BxDolDb::getInstance();
        $sLimit = '';
        $aTotalTags = array();
        $sGroupBy = "GROUP BY `{$this->aTagFields['tag']}`";

        if (isset($aParam['limit']))
        {
            $sLimit = 'LIMIT ';
            if (isset($aParam['start']))
                $sLimit .= (int)$aParam['start'] . ', ';
            $sLimit .= (int)$aParam['limit'];
        }

        $sCondition = $this->_getSelectCondition($aParam);

        if (isset($aParam['orderby']))
        {
            if ($aParam['orderby'] == 'popular')
                $sGroupBy .= " ORDER BY `count` DESC, `{$this->aTagFields['tag']}` ASC";
            else if ($aParam['orderby'] == 'recent')
                $sGroupBy .= " ORDER BY `{$this->aTagFields['date']}` DESC, `{$this->aTagFields['tag']}` ASC";
        }

        $sDiffCount = '';

        $sqlQuery = "SELECT
            `tgs`.`{$this->aTagFields['tag']}` as `{$this->aTagFields['tag']}`,
            `tgs`.`{$this->aTagFields['date']}` as `{$this->aTagFields['date']}`,
            COUNT(`tgs`.`{$this->aTagFields['id']}`) AS `count`
            FROM `{$this->sTagTable}` `tgs` $sCondition $sGroupBy $sLimit";

        $aTags = $oDb->getAll($sqlQuery);
        foreach ($aTags as $aTag) {
            if ((int)$aTag['count'] > 0)
                $aTotalTags[$aTag[$this->aTagFields['tag']]] = (int)$aTag['count'];
        }

        return $aTotalTags;
    }

    function getTagsCount($aParam) {

        $sCondition = $this->_getSelectCondition($aParam);
        $sqlQuery = "SELECT count(DISTINCT `tgs`.`{$this->aTagFields['tag']}`) AS `count` FROM
            `{$this->sTagTable}` `tgs` {$sCondition}";

        $oDb = BxDolDb::getInstance();
        return $oDb->getOne($sqlQuery);
    }

    function _getSelectCondition($aParam)
    {
        $sCondition = "WHERE `tgs`.`{$this->aTagFields['owner']}` != 0";

        if (!$aParam)
            return $sCondition;

        $oDb = BxDolDb::getInstance();

        if (isset($aParam['common'])) {
            $aUnitsCommon = $this->_getCommonCategories($aParam['type']);
            $sCondition .= " AND `tgs`.`{$this->aTagFields['tag']}` " . (!$aParam['common'] ? 'NOT' : '') .
                " IN (" . $oDb->implode_escape($aUnitsCommon) . ")";
        }

        if (isset($aParam['type']) && $aParam['type'])
            $sCondition .= $oDb->prepare(" AND `tgs`.`{$this->aTagFields['type']}` = ?", $aParam['type']);

        if (isset($aParam['status']) && $aParam['status'])
            $sCondition .= $oDb->prepare(" AND tgs.`{$this->aTagFields['status']}` = ?", $aParam['status']);
        else
            $sCondition .= " AND tgs.`{$this->aTagFields['status']}` = 'active'";

        if (isset($aParam['filter']) && $aParam['filter'])
            $sCondition .= " AND `tgs`.`{$this->aTagFields['tag']}` LIKE '%" . $oDb->escape($aParam['filter']) . "%'";

        if (isset($aParam['date']) && $aParam['date'])
            $sCondition .= $oDb->prepare(" AND DATE(`tgs`.`{$this->aTagFields['date']}`) = DATE(?)", $aParam['date']['year'] . '-' . $aParam['date']['month'] . '-' . $aParam['date']['day']);

        return $sCondition;
    }

    function _getCommonCategories($sModule = '') {
        $oDb = BxDolDb::getInstance();

        $sCondModule = $sModule ? $oDb->prepare(" AND `Type` = ?", $sModule) : '';
        $aCtegories = $oDb->getAll("SELECT `Category` FROM `sys_categories` WHERE `Owner` = 0 $sCondModule");

        $aResult = array();
        foreach ($aCtegories as $aCategory)
            $aResult[] = $aCategory['Category'];
        return $aResult;
    }

    function _insertTags ($aTagsSet) {
        $oDb = BxDolDb::getInstance();

        $aTags = $this->explodeTags($aTagsSet['tagString']);
        if( !$aTags )
            return;
        $sFields = '';
        foreach ($this->aTagFields as $sKey => $sValue)
            $sFields .= $sValue .', ';

        $aCommonCat = $this->_getCommonCategories($aTagsSet['type']);
        $bAutoApprove = getParam($this->sAutoApprovePrefix . $aTagsSet['type']) == 'on';
        $aTagsSet['owner'] = $this->iViewer;
        $sFields = trim($sFields, ', ');
        $sValues = '';
        foreach( $aTags as $sTag )
        {
            $aTagsSet['tag'] = addslashes( $sTag );
            $aTagsSet['status'] = $bAutoApprove || in_array($aTagsSet['tag'], $aCommonCat) ? 'active' : 'passive';
            $sValues .= $oDb->prepare("(?, ?, ?, ?, ?, CURRENT_TIMESTAMP), ", $aTagsSet['id'], $aTagsSet['type'], $aTagsSet['tag'], $aTagsSet['owner'], $aTagsSet['status']);
        }
        $sValues = trim($sValues, ', ');

        $sqlQuery = "INSERT INTO `{$this->sTagTable}` ($sFields) VALUES $sValues";
        $oDb->res($sqlQuery);
    }
}

