<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

define('BX_SYS_PRE_VALUES_TABLE', 'sys_pre_values');


$oCache = BxDolDb::getInstance()->getDbCacheObject();
$GLOBALS['aPreValues'] = $oCache->getData(BxDolDb::getInstance()->genDbCacheKey('sys_pre_values'));
if (null === $GLOBALS['aPreValues'])
    compilePreValues();


function getPreKeys () {
    return BxDolDb::getInstance()->fromCache('sys_prevalues_keys', 'getAll', "SELECT DISTINCT `Key` FROM `" . BX_SYS_PRE_VALUES_TABLE . "`");
}

function getPreValues ($sKey, $aFields = array()) {
    $sFields = "*";
    if (is_array($aFields) && !empty($aFields)) {
        foreach ($aFields as $sValue)
            $sFields .= "`$sValue`, ";
        $sFields = trim($sFields, ', ');
    }

    $oDb = BxDolDb::getInstance();
    $sQuery = $oDb->prepare("SELECT $sFields FROM `" . BX_SYS_PRE_VALUES_TABLE ."`
                WHERE `Key` = ?
                ORDER BY `Order` ASC", $sKey);
    return $oDb->getAllWithKey($sQuery, 'Value');
}

function getPreValuesCount ($sKey, $aFields = array()) {
    $oDb = BxDolDb::getInstance();
    $sQuery = $oDb->prepare("SELECT COUNT(*) FROM `" . BX_SYS_PRE_VALUES_TABLE . "` WHERE `Key` = ?", $sKey);
    return $oDb->getOne($sQuery);
}

function compilePreValues() {

    BxDolDb::getInstance()->cleanCache('sys_prevalues_keys');

    $aPreValues = array ();
    $aKeys = getPreKeys();

    foreach ($aKeys as $aKey) {

        $sKey = $aKey['Key'];
        $aPreValues[$sKey] = array ();

        $aRows = getPreValues($sKey);
        foreach ($aRows as $aRow) {

            $aPreValues[$sKey][$aRow['Value']] = array ();

            foreach ($aRow as $sValKey => $sValue) {
                if ($sValKey == 'Key' or $sValKey == 'Value' or $sValKey == 'Order')
                    continue; //skip key, value and order. they already used

                if (!strlen($sValue))
                    continue; //skip empty values

                $aPreValues[$sKey][$aRow['Value']][$sValKey] = $sValue;
            }

        }

    }

    $oCache = BxDolDb::getInstance()->getDbCacheObject();
    $oCache->setData (BxDolDb::getInstance()->genDbCacheKey('sys_pre_values'), $aPreValues);

    $GLOBALS['aPreValues'] = $aPreValues;
}

