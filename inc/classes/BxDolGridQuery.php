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
 * Database queries for grid.
 * @see BxDolGrid
 */
class BxDolGridQuery extends BxDolDb
{
    protected $_aObject;

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getGridObject ($sObject)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_grid` WHERE `object` = ?", $sObject);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject))
            return false;

        // paginate
        if (!empty($aObject['paginate_url']) && 0 != strncasecmp($aObject['paginate_url'], 'http://', 7) && 0 != strncasecmp($aObject['paginate_url'], 'https://', 8))
            $aObject['paginate_url'] = BX_DOL_URL_ROOT . $aObject['paginate_url'];

        // filter
        if ($aObject['filter_fields'])
            $aObject['filter_fields'] = array_map('trim', explode(',', $aObject['filter_fields']));

        if ($aObject['filter_fields_translatable'])
            $aObject['filter_fields_translatable'] = array_map('trim', explode(',', $aObject['filter_fields_translatable']));

        // sorting
        if ($aObject['sorting_fields'])
            $aObject['sorting_fields'] = array_map('trim', explode(',', $aObject['sorting_fields']));

        if ($aObject['sorting_fields_translatable']) {
            $aObject['sorting_fields_translatable'] = array_map('trim', explode(',', $aObject['sorting_fields_translatable']));
            $aObject['sorting_fields'] = array_merge ($aObject['sorting_fields'], $aObject['sorting_fields_translatable']);
        }

        // get fields
        $sQuery = $oDb->prepare("SELECT * FROM `sys_grid_fields` WHERE `object` = ? ORDER BY `order`", $sObject);
        $aFields = $oDb->getAllWithKey($sQuery, 'name');
        if (!$aFields || !is_array($aFields)) // it is impossible to have grid without any fields
            return false;
        foreach ($aFields as $sKey => $aRow) {
            $aObject['fields'][$sKey] = array(
                'title' => _t($aRow['title']),
                'width' => $aRow['width'],
                'translatable' => $aRow['translatable'],
                'chars_limit' => $aRow['chars_limit'],
            );
            if (empty($aRow['params']))
                continue;
            $aAdd = unserialize($aRow['params']);
            if (!empty($aAdd) && is_array($aAdd))
                $aObject['fields'][$sKey] = array_merge($aObject['fields'][$sKey], $aAdd);
        }
        // get actions
        $a = array('bulk','single','independent');
        foreach ($a as $sActionType) {
            $sActionField = 'actions_' . $sActionType;
            $aObject[$sActionField] = array();
            $sQuery = $oDb->prepare("SELECT * FROM `sys_grid_actions` WHERE `object` = ? AND `type` = ? ORDER BY `order`", $sObject, $sActionType);
            $aActions = $oDb->getAllWithKey($sQuery, 'name');
            if (!$aActions || !is_array($aActions))
                continue;
            foreach ($aActions as $sKey => $aRow)
                $aObject[$sActionField][$sKey] = $aRow['title'] || $aRow['icon'] ? array('title' => _t($aRow['title']), 'icon' => $aRow['icon'], 'confirm' => $aRow['confirm'], 'icon_only' => $aRow['icon_only']) : array();
        }

        return $aObject;
    }

}

/** @} */
